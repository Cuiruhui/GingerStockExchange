<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");

if($xltm->user_info['username'])
{
	
	
	$username=$xltm->user_info['username'];
	$usercancash = $xltm->AvailableCash();
	$canmoney = round($usercancash,2)* $xltm->config['cost_exchange_rate'];
	//读取手续费率
	$cost_bull = $xltm->user_info['cost_bull']*1000;
	$cost_sell = $xltm->user_info['cost_sell']*1000;
	$cost_save = $xltm->user_info['cost_save']*1000;
	
	$start_date = date('Y-m-d',time());
	
	$xltm->tpls->LoadTemplate("./wap/wapinfo.html");
	$xltm->tpls->Show();
}else {
	exit("<script>location.href='waplogin.php';</script>");
}

?>