<?php

  require_once(dirname(dirname(__FILE__))."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
	
	
include 'payCommon.php';	
	
#	ֻ��֧���ɹ�ʱAPI֧���Ż�֪ͨ�̻�.
##֧���ɹ��ص������Σ�����֪ͨ������֧����������е�p8_Url�ϣ�������ض���;��������Ե�ͨѶ.

#	�������ز���.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	�жϷ���ǩ���Ƿ���ȷ��True/False��
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	���ϴ���ͱ�������Ҫ�޸�.
	 	
#	У������ȷ.



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
			//����֧��������Ϣ
			$xltm->SQL->Execute("update `payment` set result='1',trxid='{$r3_Amt}',uid='{$r8_MP}',return_time='{$xltm->sys_time}',params='{$params}' where orderno='{$r6_Order}'");
			//�����û��Ŀ������ͱ�֤��
			$money = ceil($xltm->config['cost_exchange_rate'] * $m_oamount);
			$xltm->SQL->Execute("update `user_users` set money=money + {$r3_Amt},cash=cash+{$r3_Amt} where username='{$username}'");
			//����û��ʵ���Ϣ
			$xltm->SQL->Execute("insert into `bill_log` set bill_type='��ֵ',agent='{$agent}',username='{$username}',money='{$r3_Amt}',remark='����֧�����',add_time='{$xltm->sys_time}',add_date='{$xltm->sys_date}'");
			exit("true");
		}
	
	
	
	
	
	
		
		if($r9_BType=="1"){
		
		
		


 

			
			
			
 echo "<Script language=javascript>alert('֧���ɹ�');location.href='/trade.php?client=false';</script>";
exit;	
			
		
		
		
		 
		}elseif($r9_BType=="2"){
			#�����ҪӦ�����������д��,��success��ͷ,��Сд������.
			echo "success";
  			 
		}
	}
	
}else{
	echo "������Ϣ���۸�";
}
   
?>
 