<?php
define('Load_Info', 1);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username'])
{
	$username=$xltm->user_info['username'];
	$usercancash = $xltm->AvailableCash();
	
	
	$start_date = date('Y-m-d',time());
	$xltm->tpls->LoadTemplate("./tmpfiles/wleft.html",false);
	$xltm->tpls->Show();
	
}else {
	$xltm->gourl('','login.php');
}
?>
