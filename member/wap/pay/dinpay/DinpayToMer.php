<? header("content-Type: text/html; charset=gb2312");?> 
<?php

session_start();


  // ������������
  function HexToStr($hex)
  {
     $string="";
     for ($i=0;$i<strlen($hex)-1;$i+=2)
         $string.=chr(hexdec($hex[$i].$hex[$i+1]));
     return $string;
  } 

//=========================== ���̼ҵ������Ϣ����ȥ =======================
					
	$m_id		= 	'';			 //�̼Һ�	
	$m_orderid	= 	'';			//�̼Ҷ�����
	$m_oamount	= 	'';			//֧�����
	$m_ocurrency= 	'';			//����		
	$m_language	= 	'';			//����ѡ��
	$s_name		= 	'';			//����������
	$s_addr		= 	'';			//������סַ
	$s_postcode	= 	''; 		//��������
	$s_tel		= 	'';			//��������ϵ�绰
	$s_eml		= 	'';			//�������ʼ���ַ
	$r_name		= 	'';			//����������
	$r_addr		= 	'';			//�ջ���סַ
	$r_postcode	= 	''; 		//�ջ�����������
	$r_tel		= 	'';			//�ջ�����ϵ�绰
	$r_eml		= 	'';			//�ջ��˵��ӵ�ַ
	$m_ocomment	= 	''; 		//��ע
	$modate		=	'';			//��������
	$State		=	'';			//֧��״̬2�ɹ�,3ʧ��
	
	//��������ļ���
	$OrderInfo	=	$_POST['OrderMessage'];			//����������Ϣ

	$signMsg 	=	$_POST['Digest'];				//�ܳ�
	//�����µ�md5������֤

	//���ǩ��
	$key = $_SESSION['webpwd'];   //<--֧����Կ--> ע:�˴���Կ�������̼Һ�̨�����Կһ��
	//$digest = $MD5Digest->encrypt($OrderInfo.$key);
	$digest = strtoupper(md5($OrderInfo.$key));

?>
<?php
	if ($digest == $signMsg)
	{
		//����
		//$decode = $DES->Descrypt($OrderInfo, $key);
		$OrderInfo = HexToStr($OrderInfo);
		//=========================== �ֽ��ַ��� ====================================
		$parm=explode("|", $OrderInfo);

		$m_id		= 	$parm[0];				
		$m_orderid	= 	$parm[1];		
		$m_oamount	= 	$parm[2];			
		$m_ocurrency= 	$parm[3];				
		$m_language	= 	$parm[4];			
		$s_name		= 	$parm[5];				
		$s_addr		= 	$parm[6];				
		$s_postcode	= 	$parm[7];		
		$s_tel		= 	$parm[8];			
		$s_eml		= 	$parm[9];			
		$r_name		= 	$parm[10];			
		$r_addr		= 	$parm[11];				
		$r_postcode	= 	$parm[12];			
		$r_tel		= 	$parm[13];			
		$r_eml		= 	$parm[14];			
		$m_ocomment	= 	$parm[15];
		$modate		=	$parm[16];
		$State		=	$parm[17];
			
		if ($State == 2)
			{
			
				session_start();
				$url = $_SESSION['refer'];
				
				$orderid = $m_orderid;
				$payno = $orderid;
				
				$url = $url.'?type=cz&orderno='.$orderid.'&payno='.$payno;
				
				echo '<script src="'.$url.'"></script>';
				echo '<script>alert("OK!"); window.close();</script>';
				exit();

				echo "֧���ɹ�".'<br>';
				echo "�̼Һ�=".$m_id.'<br>';
				echo "������=".$m_orderid.'<br>';
				echo "���=".$m_oamount.'<br>';
				echo "����=".$m_ocurrency.'<br>';
						echo ".................";
			}
		else 
			{
				echo "֧��ʧ��";
			}
?>
<?php
	}else{
?>
	ʧ�ܣ���Ϣ���ܱ��۸�
<?php
	}
?>
<!--
����ʹ��dinpayʵʱ�����ӿڵ��̻���ע�⣺
    Ϊ�˴Ӹ����Ͻ������֧���ɹ����̻��ղ���������Ϣ������(��Ƶ���).
�ҹ�˾��������Ϣ��������ʵ�з������˶Է������˵ķ�����ʽ.���ͻ�֧������.
����ϵͳ����̻�����վ��������֧����Ϣ�ķ���(����ͬһ�ʶ�����Ϣ�������η���).
��һ���Ƿ������˶Է������˵ķ���.�ڶ�������ҳ�����ʽ����.���η�����ʱ�Ӳ���10��֮��.
    ���̻��Ǳ����ö����Ƿ�����Ϣ�Ĵ���. ������ϵͳ������ͬ�Ķ�����Ϣ���Ǳ�ֻ
    ��һ�δ���Ϳ�����.��ȷ�������ߵ�ÿһ�ʶ�����Ϣ�����Ǳ�ֻ�õ�һ����Ӧ�ķ���!!
-->
