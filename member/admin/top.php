<?php
error_reporting(E_ALL);
error_reporting(E_ALL || ~E_NOTICE);
define('Load_Info', 1);
session_start();
require_once("./Lib/xltm.class.php");
if($xltm->user_info['username']=='')
{
	exit('请先登录!');
}
else
{
	$xltm->tpls->LoadTemplate("./tmpfiles/top.html");
	$xltm->tpls->Show();
}
?>
