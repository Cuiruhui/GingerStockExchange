<?php
  // 公共函数定义
  function StrToHex($string)
  {
     $hex="";
     for ($i=0;$i<strlen($string);$i++)
         $hex.=dechex(ord($string[$i]));
     $hex=strtoupper($hex);
     return $hex;
  }
  
  
session_start();

$time = date("Y-m-d H:i:s",time());
$username = trim($_REQUEST["username"]);
$money = trim($_REQUEST["money"]);
$orderid = rand(1000,9999).time().rand(1000,9999);
$bankname = trim($_REQUEST["bankname"]);
$callbackurl = trim($_REQUEST["callbackurl"]);
$_SESSION['webid'] = trim($_REQUEST["webid"]);
$_SESSION['webpwd'] = trim($_REQUEST["webpwd"]);
  

	$m_id		=	$_SESSION['webid'];	
	$m_orderid	=	$orderid;
	$m_oamount	=	$money;
	$m_ocurrency  = 1;
	$m_url		=	$callbackurl;
	$m_language	=	1;
	$s_name		=	$username;
	$s_addr		=	'ajfa';
	$s_postcode	=	'283823';
	$s_tel		=	'1832877328';
	$s_eml		=	$username.'@163.com';
	$r_name		=	$username;
	$r_addr		=	'ajfoawjfeoaw';
	$r_postcode	=	'1832877328';
	$r_tel		=	'1832877328';
	$r_eml		=	$username.'@163.com';
	$m_ocomment	=	'afkeafeoaw';
	$modate		=	$time;
	$m_status	= 	0;

	//组织订单信息
	$m_info = $m_id."|".$m_orderid."|".$m_oamount."|".$m_ocurrency."|".$m_url."|".$m_language;
	$s_info = $s_name."|".$s_addr."|".$s_postcode."|".$s_tel."|".$s_eml;
	$r_info = $r_name."|".$r_addr."|".$r_postcode."|".$r_tel."|".$r_eml."|".$m_ocomment."|".$m_status."|".$modate;

	$OrderInfo = $m_info."|".$s_info."|".$r_info;

//	echo $OrderInfo;
//	exit();

	//订单信息先转换成HEX，然后再加密
	$key = $_SESSION['webpwd'];     //<--支付密钥--> 注:此处密钥必须与商家后台里的密钥一致

	$OrderInfo = StrToHex($OrderInfo);
	$digest = strtoupper(md5($OrderInfo.$key));
	
	//对订单进行入库处理....
	$refer = $_SERVER['HTTP_REFERER'];
	$_SESSION['refer'] = $refer;
	
	$url = $refer.'?type=add';
	$url .='&orderno='.$orderid;
	$url .='&agent='.$_REQUEST['agent'];
	$url .='&merid='.$_REQUEST['merid'];
	$url .='&money='.$money;
	$url .='&add_time='.$time;
	$url .='&payid='.$_REQUEST['payid'];
	echo '<script src="'.$url.'"></script>';
	
	
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=gb2312'>
</head>
<body onload='document.FORM.submit();'>
<form name='FORM' method='post' action='https://payment.dinpay.com/PHPReceiveMerchantAction.do'>
	<input type='hidden' name='OrderMessage' value='<?echo $OrderInfo?>'>
	<input type='hidden' name='digest' value='<?echo $digest?>'>
	<input type='hidden' name='M_ID' value='<?echo $m_id?>'>
        <input type="hidden" name="s" value="submit">
</form>
</body>
</html>
