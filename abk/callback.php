<?php

  require_once(dirname(dirname(__FILE__))."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
	
	
include 'payCommon.php';	
	
#	只有支付成功时API支付才会通知商户.
##支付成功回调有两次，都会通知到在线支付请求参数中的p8_Url上：浏览器重定向;服务器点对点通讯.

#	解析返回参数.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	判断返回签名是否正确（True/False）
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	以上代码和变量不需要修改.
	 	
#	校验码正确.



	 $logName="e11111111111111111.txt";
 
 
 $james=fopen($logName,"a+");
fwrite($james,"\r\n".date("Y-m-d H:i:s")."|bRet=".$bRet."|order_no[".$order_no."]|r3_Amt=[".$r3_Amt."]|r6_Order=[".$r6_Order."]|r8_MP=[".$r8_MP."]");
fclose($james);







if($bRet){
	if($r1_Code=="1"){
   	
	
	
	
 	
$row = $xltm->SQL->GetRow("Select * from `payment` where orderno='{$r6_Order}'");			
 
		if($row['result']=='0')
		{	
			$username = $row['username']; 
			$urow = $xltm->SQL->GetRow("Select agent from `user_users` where username='{$username}'");
			$agent = $urow['agent'];
			//更新支付订单信息
			$xltm->SQL->Execute("update `payment` set result='1',trxid='{$r3_Amt}',uid='{$r8_MP}',return_time='{$xltm->sys_time}',params='{$params}' where orderno='{$r6_Order}'");
			//更新用户的可用余额和保证金
			$money = ceil($xltm->config['cost_exchange_rate'] * $m_oamount);
			$xltm->SQL->Execute("update `user_users` set money=money + {$r3_Amt},cash=cash+{$r3_Amt} where username='{$username}'");
			//添加用户帐单信息
			$xltm->SQL->Execute("insert into `bill_log` set bill_type='充值',agent='{$agent}',username='{$username}',money='{$r3_Amt}',remark='在线支付入款',add_time='{$xltm->sys_time}',add_date='{$xltm->sys_date}'");
			exit("true");
		}
	
	
	
	
	
	
		
		if($r9_BType=="1"){
		
		
		


 

			
			
			
 echo "<Script language=javascript>alert('支付成功');location.href='/trade.php?client=false';</script>";
exit;	
			
		
		
		
		 
		}elseif($r9_BType=="2"){
			#如果需要应答机制则必须回写流,以success开头,大小写不敏感.
			echo "success";
  			 
		}
	}
	
}else{
	echo "交易信息被篡改";
}
   
?>
 