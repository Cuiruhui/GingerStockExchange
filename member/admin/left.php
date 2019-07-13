<?php
define('Load_Info', 1);
session_start();
require_once("./Lib/xltm.class.php");
if($xltm->user_info['username']=='')
{
	exit('请先登录!');
}
else
{
	$xltm->tpls->LoadTemplate("./tmpfiles/left.html");
	$xltm->tpls->Show();
}
?>
