<?
include_once("config.php");
$UserId=$_REQUEST["P_UserId"];
$OrderId=$_REQUEST["P_OrderId"];
$CardId=$_REQUEST["P_CardId"];
$CardPass=$_REQUEST["P_CardPass"];
$FaceValue=$_REQUEST["P_FaceValue"];
$ChannelId=$_REQUEST["P_ChannelId"];

$subject=$_REQUEST["P_Subject"];
$description=$_REQUEST["P_Description"]; 
$price=$_REQUEST["P_Price"];
$quantity=$_REQUEST["P_Quantity"];
$notic=$_REQUEST["P_Notic"];
$ErrCode=$_REQUEST["P_ErrCode"];
$PostKey=$_REQUEST["P_PostKey"];
$payMoney=$_REQUEST["P_PayMoney"];

$preEncodeStr=$UserId."|".$OrderId."|".$CardId."|".$CardPass."|".$FaceValue."|".$ChannelId."|".$SalfStr;

$encodeStr=md5($preEncodeStr);

echo "errCode=0";
if($PostKey==$encodeStr)
{
	if($ErrCode=="0") //支付成功
	{
		//设置为成功订单,主意订单的重复处理
	}
	else
	{
		//支付失败
		echo "-err";
	}
}
else
{
	echo "-数据被传改";
}
?>