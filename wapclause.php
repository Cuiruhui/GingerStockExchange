<?php
session_start();

require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");
if($xltm->user_info['username'])
{
	
	
	$username=$xltm->user_info['username'];
	$row = $xltm->SQL->GetRow("Select * from system_text where name='system_rule'");
	$system_rule = htmlspecialchars_decode($row['value']);
	$xltm->tpls->LoadTemplate("./wap/wapclause.html");
	$xltm->tpls->Show();
}else {
	exit("<script>location.href='waplogin.php';</script>");
}
?>