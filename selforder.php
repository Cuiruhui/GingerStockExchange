<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$xltm->tpls->LoadTemplate("./tmpfiles/selforder.html");
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>