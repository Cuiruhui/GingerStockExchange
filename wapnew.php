<?php
session_start();

require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");
if($xltm->user_info['username'])
{
	
	
	$username=$xltm->user_info['username'];
	$xltm->tpls->LoadTemplate("./wap/wapnew.html");
$news=$xltm->SQL->GetRows("SELECT * FROM common where type='1' ORDER BY `add_time` DESC ");
$xltm->tpls->MergeBlock('tr','array',$news);
$xltm->tpls->Show();
}else {
	exit("<script>location.href='waplogin.php';</script>");
}
?>