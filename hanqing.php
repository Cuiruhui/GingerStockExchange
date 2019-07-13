<?php
define('Load_Info', true);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");
if($xltm->user_info['username'])
{
	
	
	$username=$xltm->user_info['username'];
	
	$xltm->tpls->LoadTemplate("./tmpfiles/hangqing.html");
	$xltm->tpls->Show();
}else {
	exit("<script>location.href='login.php';</script>");
}

?>
