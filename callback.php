<?php
date_default_timezone_set('PRC'); 
header("Content-type: text/html; charset=utf-8");
 
  require_once($_SERVER['DOCUMENT_ROOT']."/common.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/Lib/xltm.class.php");       
		
		$key= "as7fipszpay23";//加密密钥
		$input = file_get_contents("php://input"); //接收POST数据

		
		
		file_put_contents('callback.log', $input.PHP_EOL, FILE_APPEND);
	      
 //amount=12&app_id=2017111500000003&result=1&signature=6D117F0CB17F49DD&trans_id=2017112901000138
         parse_str($input,$dic); 

		 $context = base64_decode($dic["context"]);
	 
	     parse_str($context,$dic); 

		 $strSign=$dic["signature"];

         $Signstr = "{" . $dic["app_id"] . "}|{" . $dic["result"] . "}|{" . $dic["amount"] . "}|{" . $key . "}";
		
 		
 		if($dic["result"]=='1'){	
	
		//成功，写入数据库
					$app_id=$dic["app_id"];
					$amount=$dic["amount"]/100;//精确到元
					$row = $xltm->SQL->GetRow("Select * from `payment` where orderno='{$app_id}'");
					if(is_array($row))
					{
						if($row['result']=='0' )
						{	
							$username = $row['username']; 
							$urow = $xltm->SQL->GetRow("Select agent from `user_users` where username='{$username}'");
							$agent = $urow['agent'];
						
							//更新支付订单信息
							$zhuantai=$xltm->SQL->Execute("update payment set result='1',return_time='{$xltm->sys_time}',params='haiopay' where orderno='{$app_id}'");
							//更新用户的可用余额和保证金
							$money = ceil($xltm->config['cost_exchange_rate'] * $amount);
							$zhuantai=$xltm->SQL->Execute("update user_users set money=money + {$money},cash=cash+{$amount} where username='{$username}'");
							//添加用户帐单信息
							$zhuantai=$xltm->SQL->Execute("insert into bill_log set bill_type='充值',agent='{$agent}',username='{$username}',money='{$amount}',remark='在线支付入款',add_time='{$xltm->sys_time}',add_date='{$xltm->sys_date}'");
						
					
						}
					}
				
		}
 
            echo 'SUCCESS';
		
            return;


 

 
        
 
?>

