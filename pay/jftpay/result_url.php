<?
include_once("config.php");


$orderid=$_REQUEST["orderid"];
$opstate=$_REQUEST["opstate"];
$ovalue=$_REQUEST["ovalue"];
$sign=$_REQUEST["sign"];

$preEncodeStr="orderid=".$orderid."&opstate=".$opstate."&ovalue=".$ovalue;

$P_PostKey=md5($preEncodeStr.$SalfStr);

if($sign==$P_PostKey)
{
	if($opstate=="0") //支付成功
	{
	//	echo "opstate=".$opstate;
		//设置为成功订单,主意订单的重复处理
		$orderid = $orderid;
		$money = $ovalue;
		
		session_start();
		$url = $_SESSION['refer'];
		
		$url = $url.'?type=cz&orderno='.$orderid.'&money='.$money;
		
		echo '<script src="'.$url.'"></script>';
		echo '<script>alert("OK!"); window.close();</script>';
		exit();
		
	}
	else if($opstate=="-1")
	{
		//支付失败
		echo "请求参数错误";
	}
}
else
{
	echo "签名错误";
}
?>