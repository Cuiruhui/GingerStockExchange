<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$isopen = $xltm->startTime();
	$xltm->tpls->LoadTemplate("./tmpfiles/wstock_lists.html",false);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}

?>