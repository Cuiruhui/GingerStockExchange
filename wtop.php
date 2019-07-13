<?php
define('Load_Info', 1);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username'])
{	
	$mdate=date("D M j G:i:s T Y");
	$client = isset($_REQUEST['client']) ? $_REQUEST['client'] : 'false';
	$user_name=$xltm->user_info['username'];
	$num_min=$xltm->user_info['num_min']*1;       //最小单手数
	$num_max=$xltm->user_info['num_max']*1;     //最大单手数
	$common=$xltm->SQL->GetRows("SELECT * FROM common where type='1' ORDER BY `add_time` DESC ");
	
	$cancash = round($xltm->AvailableCash(),2);
	$money = round($cancash * $xltm->config['cost_exchange_rate'],2);
	
	
	$xltm->tpls->LoadTemplate("./tmpfiles/wtop.html");
	$xltm->tpls->MergeBlock('cr','array',$common);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>
