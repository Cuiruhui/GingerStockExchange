<?php

/*
 * @Description 易宝支付产品通用接口范例 
 * @V3.0
 * @Author rui.xin
 */

#	商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得

session_start();
$p1_MerId = $_SESSION['webid'];																										
$merchantKey = $_SESSION['webpwd'];		

$logName	= "YeePay_HTML.log";

?> 