<?php

session_start();

$_SESSION['webid'] = trim($_REQUEST["webid"]);
$_SESSION['webpwd'] = trim($_REQUEST["webpwd"]);

include_once("config.php");


$time = date('Y-m-d H:i:s',time());
$username = trim($_REQUEST["username"]);
$orderid = date('YmdHis',time()).rand(100,200);

//$orderid=getOrderId();
$parter=$UserId;
$banktype=$_REQUEST["ka_type"];
$amount=$_REQUEST["p3_Amt"];
$callbackurl=$result_url;

$refer = $_SERVER['HTTP_REFERER'];
$_SESSION['refer'] = $refer;

$url = $refer.'?type=add';
$url .='&orderno='.$orderid;
$url .='&agent='.$_REQUEST['agent'];
$url .='&merid='.$_REQUEST['merid'];
$url .='&money='.$amount;
$url .='&add_time='.$time;
$url .='&payid='.$_REQUEST['payid'];


$preEncodeStr="parter=".$parter."&type=".$banktype."&value=".$amount."&orderid=".$orderid."&callbackurl=".$callbackurl;

$P_PostKey=md5($preEncodeStr.$SalfStr);

$url2 = $gateWary."?".$preEncodeStr."&sign=".$P_PostKey;

//在这里对订单进行入库保存
echo '<script src="'.$url.'"></script>';
echo '<script>location.href="'.$url2.'";</script>';
exit();


//下面这句是提交到API
//header("Location:".$gateWary."?".$preEncodeStr."&sign=".$P_PostKey);

function getOrderId()
{
	return rand(100000,999999)."".date("YmdHis");
}
?>