 <?php

/*
 * @Description �ױ�֧��B2C����֧���ӿڷ��� 
 * @V3.0
 * @Author rui.xin
 */
 
include 'yeepayCommon.php';	
	
#	ֻ��֧���ɹ�ʱ�ױ�֧���Ż�֪ͨ�̻�.
##֧���ɹ��ص������Σ�����֪ͨ������֧����������е�p8_Url�ϣ�������ض���;��������Ե�ͨѶ.

#	�������ز���.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	�жϷ���ǩ���Ƿ���ȷ��True/False��
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	���ϴ���ͱ�������Ҫ�޸�.
	 	
#	У������ȷ.
if($bRet){
	if($r1_Code=="1"){
		
	#	��Ҫ�ȽϷ��صĽ�����̼����ݿ��ж����Ľ���Ƿ���ȣ�ֻ����ȵ�����²���Ϊ�ǽ��׳ɹ�.
	#	������Ҫ�Է��صĴ������������ƣ����м�¼�������Դ����ڽ��յ�֧�����֪ͨ���ж��Ƿ���й�ҵ���߼�������Ҫ�ظ�����ҵ���߼�������ֹ��ͬһ�������ظ��������������.      	  	
		
		if($r9_BType=="1"){
		//	echo "���׳ɹ�";
		//	echo  "<br />����֧��ҳ�淵��"; payment.php?type=cz&orderno=98297120130416221755&payno=98297120130416221755
			
						$orderid = $r6_Order;
				$payno = $orderid;
				
				$url = '../payment.php?type=cz&orderno='.$orderid.'&payno='.$payno;	
				echo '<script src="'.$url.'"></script>';
				
				
				
				
				
				
				
				
				
				echo "<script>alert('��ϲ������ֵ�ɹ���');location.href='../trade.php?client=false';</script>";
				exit();
				
				
				
				
				
				
				
				
				
				
				
				
				
			
			
		}elseif($r9_BType=="2"){
			#�����ҪӦ�����������д��,��success��ͷ,��Сд������.
					$orderid = $r6_Order;
				$payno = $orderid;
				echo "success";
				$url = '../payment.php?type=cz&orderno='.$orderid.'&payno='.$payno;	
				echo '<script src="'.$url.'"></script>';
			
			     			 
		}
	}
	
}else{
	echo "������Ϣ���۸�";
}
   
?>
 