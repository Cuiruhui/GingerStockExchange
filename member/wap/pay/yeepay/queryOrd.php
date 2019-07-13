<?php

/*
 * @Description �ױ�֧����Ʒͨ��֧���ӿڷ��� 
 * @V3.0
 * @Author rui.xin
 */
 	
include 'yeepayCommon.php';
require_once 'HttpClient.class.php';
 		
$p0_Cmd 	= "QueryOrdDetail";	            #�ӿ�����
$p2_Order = $_POST['p2_Order'];						#�̻�������
#��ʽ�����ַ
#$QueryOrdURL_onLine	= "https://www.yeepay.com/app-merchant-proxy/command";			
#���������ַ					
$QueryOrdURL_onLine	= "http://tech.yeepay.com:8080/robot/debug.action";									
    
#	����ǩ������һ�������ĵ��б�����ǩ��˳�����
$sbOld ="";
#	���붩����ѯ���󣬹̶�ֵ"QueryOrdDetail"
$sbOld = $sbOld.$p0_Cmd;
#	�����̻����
$sbOld = $sbOld.$p1_MerId;
#	�����̻�������
$sbOld = $sbOld.$p2_Order;
                   
$hmac	 = null;
$hmac	 = HmacMd5($sbOld,$merchantKey);     
           
	logstr($p2_Order,$sbOld,HmacMd5($sbOld,$merchantKey));
           
#	����ǩ������һ�������ĵ��б�����ǩ��˳�����
#	���붩����ѯ���󣬹̶�ֵ"QueryOrdDetail"
$params = array('p0_Cmd' => $p0_Cmd,
#	�����̻����
'p1_MerId'	=>  $p1_MerId,
#	�����̻�������
'p2_Order'	=>  $p2_Order,
#	����У����
'hmac' 			=>  $hmac);


$pageContents = HttpClient::quickPost($QueryOrdURL_onLine, $params);

$result = explode("\n",$pageContents);

## ������ѯ���
	$r0_Cmd					= "";			#	ȡ��ҵ������
	$r1_Code				= "";     #	��ѯ���״̬��
	$r2_TrxId				= "";			#	�ױ�֧��������ˮ��
	$r3_Amt					= "";			#	֧�����
	$r4_Cur					= "";			#	���ױ���
	$r5_Pid					= "";			#	��Ʒ����
	$r6_Order				= "";			#	�̻�������
	$r8_MP					= "";			#	�̻���չ��Ϣ
	$rb_PayStatus		= "";			#	֧��״̬
	$rc_RefundCount	= "";			#	���˿����
	$rd_RefundAmt		= "";			#	���˿���
	$hmac						= "";     #	��ѯ�������ݵ�ǩ����
    		
	for($index=0;$index<count($result);$index++){//����ѭ��
		$result[$index] = trim($result[$index]);
		if (strlen($result[$index]) == 0) {
			continue;
		}
		$aryReturn = explode("=",$result[$index]);
		$sKey = $aryReturn[0];
		$sValue = $aryReturn[1];
		if($sKey=="r0_Cmd"){											#ҵ������ 
			$r0_Cmd = $sValue;
		}elseif($sKey=="r1_Code"){								#��ѯ���״̬��  
			$r1_Code = $sValue;
		}elseif($sKey == "r2_TrxId"){			        #�ױ�֧��������ˮ��
			$r2_TrxId = $sValue;
		}elseif($sKey == "r3_Amt"){			          #֧�����
			$r3_Amt = $sValue;
		}elseif($sKey == "r4_Cur"){			          #���ױ���
			$r4_Cur = $sValue;
		}elseif($sKey == "r5_Pid"){								#��Ʒ����
			$r5_Pid = $sValue;
		}elseif($sKey == "r6_Order"){							#�̻�������
			$r6_Order = $sValue;
		}elseif($sKey == "r8_MP"){							  #�̻���չ��Ϣ
			$r8_MP = $sValue;
		}elseif($sKey == "rb_PayStatus"){					#֧��״̬
			$rb_PayStatus = $sValue;
		}elseif($sKey == "rc_RefundCount"){				#���˿����
			$rc_RefundCount = $sValue;
		}elseif($sKey == "rd_RefundAmt"){					#���˿���
			$rd_RefundAmt = $sValue;
		}elseif($sKey == "hmac"){									#ȡ��У����
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
	#�����ѯ�����Ƿ�ɹ�
	$sbOld = $sbOld.$r1_Code;
	#�����ױ�֧��������ˮ��
	$sbOld = $sbOld.$r2_TrxId;
	#����֧�����
	$sbOld = $sbOld.$r3_Amt;	
	#���뽻�ױ���
	$sbOld = $sbOld.$r4_Cur;	
	#������Ʒ����
	$sbOld = $sbOld.$r5_Pid;	
	#�����̻�������
	$sbOld = $sbOld.$r6_Order;	
	#�����̻���չ��Ϣ
	$sbOld = $sbOld.$r8_MP;		              
	#����֧��״̬
	$sbOld = $sbOld.$rb_PayStatus;		              
	#�������˿����
	$sbOld = $sbOld.$rc_RefundCount;		              
	#�������˿���
	$sbOld = $sbOld.$rd_RefundAmt;		              
            	
  echo "[".$sbOld."]";
  
  //echo $sNewString;  
  //echo $sNewString;
  
	$sNewString = HmacMd5($sbOld,$merchantKey);
	
	logstr($r6_Order,$sbOld,HmacMd5($sbOld,$merchantKey));
	//У������ȷ
	if($sNewString==$hmac) {
	  if($r1_Code=="1"){
	      echo "<br>��ѯ�ɹ�!";
	      echo "<br>������:".$r6_Order;
	      echo "<br>�ױ�֧��������ˮ��:".$r2_TrxId;
		    echo "<br>��Ʒ����:".$r5_Pid;
	      echo "<br>֧�����:".$r3_Amt;
	      echo "<br>�̻���չ��Ϣ:".$r8_MP;
	      echo "<br>����״̬:".$rb_PayStatus;
	      echo "<br>���˿����:".$rc_RefundCount;
	      echo "<br>���˿���:".$rd_RefundAmt;
	  } else if($r1_Code=="50"){
	      echo "<br>�ö���������";
	      exit; 
	  } else{
	      echo "<br>��ѯʧ��";	
	      exit;       
	  }
	} else{
		echo "<br>localhost:".$sNewString;	
		echo "<br>YeePay:".$hmac;
		echo "<br>������Ϣ���۸�";
		exit; 
	}
 
     
?> 
<html>
<head>
<title>To YeePay Page</title>
</head>
<body>
</html>