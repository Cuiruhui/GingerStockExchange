<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");


if($xltm->user_info['username'])
{
	
	$codes=$_GET['codes'];
	if(substr($codes,0,1)== "6")
		$area_str = "sh";
	if (substr($codes,0,1) == "0" || substr($codes,0,1) == "3")
		$area_str = "sz";
	$codeall=$area_str.$codes;
	
		
	$username=$xltm->user_info['username'];
	
	$xltm->tpls->LoadTemplate("./wap/waphang2.html");
	$xltm->tpls->Show();
}else {
	exit("<script>location.href='waplogin.php';</script>");
}



?>