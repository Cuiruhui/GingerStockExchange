<?php
//header('P3P: CP="ALL ADM DEV PSAi COM OUR OTRo STP IND ONL"');
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$mdate=date("D M j G:i:s T Y");
$client = isset($_REQUEST['client']) ? $_REQUEST['client'] : 'false';
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
?>
