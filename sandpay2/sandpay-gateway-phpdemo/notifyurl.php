<?php 
require('common.php');
date_default_timezone_set("Asia/Shanghai");

$pubkey = loadX509Cert(PUB_KEY_PATH);
if($_POST){
	$sign = $_POST['sign']; //签名
	$signType = $_POST['signType']; //签名方式
	$data = stripslashes($_POST['data']); //支付数据
	$charset = $_POST['charset']; //支付编码
	$result = json_decode($data,true); //data数据

	if (verify($data, $sign,$pubkey)) {
		//签名验证成功
	    file_put_contents ("temp/sd_notifyUrl_log.txt", date ( "Y-m-d H:i:s" ) . "  "."异步通知返回报文：" .$data. "\r\n", FILE_APPEND );
	    echo "respCode=000000";
	    exit;
	} else {
		//签名验证失败
	    exit;
	}
}
