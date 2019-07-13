<?php
include_once("config.php");

$P_UserId=$_REQUEST["userId"];
$P_CardId=$_REQUEST["cardId"];
$P_CardPass=$_REQUEST["cardPass"];
$P_FaceValue=$_REQUEST["faceValue"];
$P_ChannelId=$_REQUEST["channelId"];
$P_Subject=$_REQUEST["subject"];
$P_Price=$_REQUEST["price"];
$P_Quantity=$_REQUEST["quantity"];
$P_Description=$_REQUEST["description"];
$P_Notic=$_REQUEST["notic"];
$P_Result_url=$result_url;
$P_Notify_url=$notify_url;

$P_OrderId=getOrderId();
$preEncodeStr=$P_UserId."|".$P_OrderId."|".$P_CardId."|".$P_CardPass."|".$P_FaceValue."|".$P_ChannelId."|".$SalfStr;

$P_PostKey=md5($preEncodeStr);

$params="P_UserId=".$P_UserId;
$params.="&P_OrderId=".$P_OrderId;
$params.="&P_CardId=".$P_CardId;
$params.="&P_CardPass=".$P_CardPass;
$params.="&P_FaceValue=".$P_FaceValue;
$params.="&P_ChannelId=".$P_ChannelId;
$params.="&P_Subject=".$P_Subject;
$params.="&P_Price=".$P_Price;
$params.="&P_Quantity=".$P_Quantity;
$params.="&P_Description=".$P_Description;
$params.="&P_Notic=".$P_Notic;
$params.="&P_Result_url=".$P_Result_url;
$params.="&P_Notify_url=".$P_Notify_url;
$params.="&P_PostKey=".$P_PostKey;

//在这里对订单进行入库保存

//下面这句是提交到API
header("location:$gateWary?$params");
function getOrderId()
{
	return rand(100000,999999)."".date("YmdHis");
}
?>