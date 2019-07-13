<?php
session_start();
date_default_timezone_set(PRC);

//平台商户ID，需要更换成自己的商户ID
$UserId = $_SESSION['webid'];

//接口密钥，需要更换成你自己的密钥，要跟后台设置的一致
//登录API平台，商户管理-->安全设置-->密钥设置，这里自己设置密钥

$SalfStr = $_SESSION['webpwd'];


//网关地址，要更新成你所在的平台网关地址

$gateWary="http://do.jftpay.net/chargebank.aspx";


//充值结果后台通知地址

$result_url="http://gp.356158.com/pay/jftpay/result_url.php";


//充值结果用户在网站上的转向地址

$notify_url="http://gp.356158.com/pay/jftpay/notify_Url.php";
?>