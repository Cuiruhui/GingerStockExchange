<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");


if($xltm->user_info['username'])
{
	$getstockcode=isset($_GET['code']) ?  $_GET['code']: '600111';
	$isopen = $xltm->startTime();
	$username=$xltm->user_info['username'];
	$cancash = round($xltm->AvailableCash(),2)* $xltm->config['cost_exchange_rate'];	//ÀîÐËÐÞ¸Ä¡£2012-4-14
	$xltm->tpls->LoadTemplate("./wap/waporder.html");
	$xltm->tpls->Show();
}else {
	exit("<script>location.href='waplogin.php';</script>");
}


?>