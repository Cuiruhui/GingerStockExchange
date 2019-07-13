<?php

require_once($_SERVER['DOCUMENT_ROOT']."/common.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Lib/xltm.class.php");

$resp_code=$_POST['resp_code'];
$amount=$_POST['amount'];
$out_trade_no=$_POST['out_trade_no'];
$sign=$_POST['sign'];

if($resp_code=='' || $sign=='')
{
	exit;
}
    if($resp_code=='00'){
	
			//成功，写入数据库
			$outer_trade_no=$out_trade_no;
			$trade_amount=$amount;
	$row = $xltm->SQL->GetRow("Select * from `payment` where orderno='{$outer_trade_no}'");
			if(is_array($row))
			{
						if($row['result']=='0' )
						{	
							$username = $row['username']; 
							$urow = $xltm->SQL->GetRow("Select agent from `user_users` where username='{$username}'");
							$agent = $urow['agent'];
						
							//更新支付订单信息
							$zhuantai=$xltm->SQL->Execute("update payment set result='1',return_time='{$xltm->sys_time}',params='haiopay' where orderno='{$outer_trade_no}'");
							//更新用户的可用余额和保证金
							$money = ceil($xltm->config['cost_exchange_rate'] * $trade_amount);
							$zhuantai=$xltm->SQL->Execute("update user_users set money=money + {$money},cash=cash+{$trade_amount} where username='{$username}'");
							//添加用户帐单信息
							$zhuantai=$xltm->SQL->Execute("insert into bill_log set bill_type='充值',agent='{$agent}',username='{$username}',money='{$trade_amount}',remark='在线支付入款',add_time='{$xltm->sys_time}',add_date='{$xltm->sys_date}'");
		                } 
						
			} 
	
        echo '支付成功';
    } else {
        echo '支付失败';
    }

?>
