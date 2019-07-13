<?php 
            header("Content-type:text/html; charset=GB2312");
			ini_set("display_errors", "On");
			error_reporting(E_ALL | E_STRICT);
			require_once(dirname(__FILE__)."/common.php");
			require_once(ROOT."/Lib/xltm.class.php");
			
			
			$partner = "16990";//商户ID
      $Key = "00ac07fc673f6e97314b1e0bbc9f4f61";//商户KEY
            $orderstatus = $_GET["orderstatus"];
            $ordernumber = $_GET["ordernumber"];
            $paymoney = $_GET["paymoney"];
            $sign = $_GET["sign"];
            $attach = $_GET["attach"];
            $signSource = sprintf("partner=%s&ordernumber=%s&orderstatus=%s&paymoney=%s%s", $partner, $ordernumber, $orderstatus, $paymoney, $Key); 
            if ($sign == md5($signSource))//签名正确
            {
               if ($orderstatus == 1)
				{
				$r6_Order = $ordernumber;
				$r3_Amt = $paymoney;
				$row = $xltm->SQL->GetRow("Select * from `payment` where orderno='{$r6_Order}'");
				if(is_array($row))
				{
					if($row['result']=='0')
					{	
						$username = $row['username']; 
						$urow = $xltm->SQL->GetRow("Select agent from `user_users` where username='{$username}'");
						$agent = $urow['agent'];
						//更新支付订单信息
						$xltm->SQL->Execute("update `payment` set result='1',return_time='{$xltm->sys_time}',params='{$sign}' where orderno='{$r6_Order}'");
						//更新用户的可用余额和保证金
						$money = ceil($xltm->config['cost_exchange_rate'] * $r3_Amt);
						$xltm->SQL->Execute("update `user_users` set money=money + {$money},cash=cash+{$r3_Amt} where username='{$username}'");
						//添加用户帐单信息
						$xltm->SQL->Execute("insert into `bill_log` set bill_type='充值',agent='{$agent}',username='{$username}',money='{$r3_Amt}',remark='在线支付入款',add_time='{$xltm->sys_time}',add_date='{$xltm->sys_date}'");
						echo('true');
					}
					else
					{
						exit("false:002");
					}
				}
        	}
			echo('ok');exit;

?>