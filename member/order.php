<?php
//ini_set('session.save_path','./temp');
/*
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$mdate=date("D M j G:i:s T Y");
if(isset($_GET['login']) && $_GET['login']=='logout')
{
	$xltm->User->logout_user();
	exit;
}
if($xltm->user_info['username']){
	$time=date("Y-d-m h:i:s",time()+6000);
	$user_name=$xltm->user_info['username'];
	$num_min=$xltm->user_info['num_min']*1;       //最小单手数
	$num_max=$xltm->user_info['num_max']*1;     //最大单手数
	$common=$xltm->SQL->GetRows("SELECT * FROM common where type='1' ORDER BY `add_time` DESC ");
	$xltm->tpls->LoadTemplate("./tmpfiles/index.html");
	$xltm->tpls->MergeBlock('cr','array',$common);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
*/
define('Load_Info', 1);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username'])
{
	$initStockCode = $_GET['do']=='order' && $_GET['stockcode'] ? $_GET['stockcode'] : '0';
	$isopen = $xltm->startTime();
	
	$username=$xltm->user_info['username'];
	$res=$xltm->SQL->GetRow( "SELECT `is_man` FROM `user_users` WHERE `username` = '$username'");
	$is_man=$res["is_man"];

	$cancash = round($xltm->AvailableCash(),2)* $xltm->config['cost_exchange_rate'];	//李兴修改。2012-4-14
	$xltm->tpls->LoadTemplate("./tmpfiles/order.html");
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>
