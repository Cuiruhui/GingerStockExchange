<?
date_default_timezone_set(PRC);
//平台商户ID，需要更换成自己的商户ID
$UserId="70022";


//接口密钥，需要更换成你自己的密钥，要跟后台设置的一致
//登录API平台，商户管理-->安全设置-->密钥设置，这里自己设置密钥
$SalfStr="420892ecaecbaacaad75801cd213a284 ";


//网关地址，要更新成你所在的平台网关地址
//比如：你在www.abc.com上接的接口，那么网关地址就是：http://www.abc.com/pay/gateway.asp
$gateWary="../pay/gateway.asp";


//充值结果后台通知地址
$result_url="http://".$_SERVER["HTTP_HOST"]."/php/result_url.php";


//充值结果用户在网站上的转向地址
$notify_url="http://".$_SERVER["HTTP_HOST"]."/php/notify_Url.php";
?>