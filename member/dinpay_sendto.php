<?php
  // 公共函数定义
session_start();

$_SESSION['webid'] = trim($_REQUEST["webid"]);
$_SESSION['webpwd'] = trim($_REQUEST["webpwd"]);  
  
require_once("dinpay_key.php");
  
  function StrToHex($string)
  {
     $hex="";
     for ($i=0;$i<strlen($string);$i++)
         $hex.=dechex(ord($string[$i]));
     $hex=strtoupper($hex);
     return $hex;
  }

 



	
	
		
	$m_orderid	=  rand(1000,9999).time().rand(1000,9999);
	$m_oamount	=	$_POST['money'];
	$m_ocurrency    ="1";
	$m_url		=	"http://".$_SERVER["HTTP_HOST"]."/dinpay_callback.php";
	$m_language	=	"1";
	$s_name		=	$_POST['pa_MP'];
	$s_addr		=	"深圳市快汇宝信息技术有限公司罗湖区东门百货广场东座2308";
	$s_postcode	=	"100088";
	$s_tel		=	"0755-82384511";
	$s_eml		=	"service@dinpay.com";
	$r_name		=	$_POST['pa_MP'];
	$r_addr		=	"深圳市快汇宝信息技术有限公司罗湖区东门百货广场东座2308";
	$r_postcode	=	"100088";
	$r_tel		=	"0755-82384511";
	$r_eml		=	"service@dinpay.com";
	$m_ocomment	=	$_POST['pa_MP'];
	$modate		=	date('Y-m-d H:i:s',time());
	$m_status	= 	0;

	//组织订单信息
	$m_info = $m_id."|".$m_orderid."|".$m_oamount."|".$m_ocurrency."|".$m_url."|".$m_language;
	$s_info = $s_name."|".$s_addr."|".$s_postcode."|".$s_tel."|".$s_eml;
	$r_info = $r_name."|".$r_addr."|".$r_postcode."|".$r_tel."|".$r_eml."|".$m_ocomment."|".$m_status."|".$modate;

	$OrderInfo = $m_info."|".$s_info."|".$r_info;

	//echo $OrderInfo;

	//订单信息先转换成HEX，然后再加密





//对订单进行入库处理....
$refer = $_SERVER['HTTP_REFERER'];
$_SESSION['refer'] = $refer;

$time = date("Y-m-d H:i:s",time());
$username = trim($_REQUEST["username"]);
$url = $refer.'?type=add';
$url .='&orderno='.$m_orderid;
$url .='&agent='.$_REQUEST['agent'];
$url .='&merid='.$_REQUEST['merid'];
$url .='&money='.$m_oamount;
$url .='&add_time='.$time;
$url .='&payid='.$_REQUEST['payid'];
echo '<script src="'.$url.'"></script>';


 



	$OrderInfo = StrToHex($OrderInfo);
	$digest = strtoupper(md5($OrderInfo.$key));
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
