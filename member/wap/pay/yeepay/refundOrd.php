<?php

/*
 * @Description �ױ�֧����Ʒͨ�ýӿڷ��� 
 * @V3.0
 * @Author rui.xin
 */
 	
include 'yeepayCommon.php';
require_once 'HttpClient.class.php';

#�˿�ӿ���ʽ�����ַ
#$reqURL_RefOrd	= "https://www.yeepay.com/app-merchant-proxy/command";								
#�˿�ӿڲ��������ַ
$reqURL_RefOrd	= "http://tech.yeepay.com:8080/robot/debug.action";
$p0_Cmd 	= "RefundOrd";	            #�ӿ�����
$pb_TrxId = $_POST['pb_TrxId'];				#�ױ�֧��������ˮ��
$p3_Amt		= $_POST['p3_Amt'];					#�˿���
$p4_Cur		=	"CNY";										#���ױ���,�̶�ֵ"CNY".
$p5_Desc  = $_POST['p5_Desc'];			  #��ϸ�����˿�ԭ�����Ϣ.
    
#	����ǩ������һ�������ĵ��б�����ǩ��˳�����
$sbOld ="";
#	���붩����ѯ���󣬹̶�ֵ"QueryOrdDetail"
$sbOld = $sbOld.$p0_Cmd;
#	�����̻����
$sbOld = $sbOld.$p1_MerId;
#	�����ױ�֧��������ˮ��
$sbOld = $sbOld.$pb_TrxId;
#	�����˿���
$sbOld = $sbOld.$p3_Amt;
#	���뽻�ױ���
$sbOld = $sbOld.$p4_Cur;
#	�����˿�˵��
$sbOld = $sbOld.$p5_Desc;
      
$hmac	 = null;
$hmac	 = HmacMd5($sbOld,$merchantKey);     
           
	logstr($pb_TrxId,$sbOld,HmacMd5($sbOld,$merchantKey));
           
#	����ǩ������һ�������ĵ��б�����ǩ��˳�����
#	���붩����ѯ���󣬹̶�ֵ"QueryOrdDetail"
$params = array('p0_Cmd' => $p0_Cmd,
#	�����̻����
'p1_MerId'	=>  $p1_MerId,
#	�����ױ�֧��������ˮ��
'pb_TrxId'	=>  $pb_TrxId,
#	�����ױ�֧��������ˮ��
'p3_Amt'		=>  $p3_Amt,
#	�����ױ�֧��������ˮ��
'p4_Cur'		=>  $p4_Cur,
#	�����ױ�֧��������ˮ��
'p5_Desc'		=>  $p5_Desc,
#	����У����
'hmac' 			=>  $hmac);

$pageContents = HttpClient::quickPost($reqURL_RefOrd, $params);
$result = explode("\n",$pageContents);

## ������ѯ���
	$r0_Cmd					= "";			#	ҵ������
	$r1_Code				= "";     #	�˿�������
	$r2_TrxId				= "";			#	�ױ�֧��������ˮ��
	$r3_Amt					= "";			#	�˿���
	$r4_Cur					= "";			#	���ױ���
	$hmac						= "";     #	ǩ������
  #echo "result.count:".count($result);
	for($index = 0;$index < count($result);$index++){//����ѭ��
		$result[$index] = trim($result[$index]);
		if (strlen($result[$index]) == 0) {
			continue;
		}
		$aryReturn = explode("=",$result[$index]);
		$sKey = $aryReturn[0];
		$sValue = $aryReturn[1];
		if($sKey=="r0_Cmd"){											#ҵ������ 
			$r0_Cmd = $sValue;
		}elseif($sKey=="r1_Code"){								#�˿�������  
			$r1_Code = $sValue;
		}elseif($sKey == "r2_TrxId"){			        #�ױ�֧��������ˮ��
			$r2_TrxId = $sValue;
		}elseif($sKey == "r3_Amt"){			          #�˿���
			$r3_Amt = $sValue;
		}elseif($sKey == "r4_Cur"){			          #���ױ���
			$r4_Cur = $sValue;
		}elseif($sKey == "hmac"){									#ȡ��ǩ������
			$hmac = $sValue;	      
		}else{
			echo $result[$index];
			return;
		}
	}
		

	#����У������ ȡ�ü���ǰ���ַ���
	$sbOld="";
	#����ҵ������
	$sbOld = $sbOld.$r0_Cmd;
	#�����˿������Ƿ�ɹ�
	$sbOld = $sbOld.$r1_Code;
	#�����ױ�֧��������ˮ��
	$sbOld = $sbOld.$r2_TrxId;
	#�����˿���
	$sbOld = $sbOld.$r3_Amt;	
	#���뽻�ױ���
	$sbOld = $sbOld.$r4_Cur;	
            	 
	$sNewString = HmacMd5($sbOld,$merchantKey);
	
	logstr($r2_TrxId,$sbOld,HmacMd5($sbOld,$merchantKey));
	//У������ȷ
	if($sNewString==$hmac) {
	  if($r1_Code=="1"){
	      echo "<br>�����˿�����ɹ�!";
	      echo "<br>�ױ�֧��������ˮ��:".$r2_TrxId;
	      echo "<br>�˿���:".$r3_Amt;
	  } else{
	      echo "<br>�����˿�����ʧ��";	
	      exit;       
	  }
	} else{
		echo "<br>localhost::".$sNewString;	
		echo "<br>YeePay:".$hmac;
		echo "<br>����ǩ����Ч.";
		exit; 
	}
?> 