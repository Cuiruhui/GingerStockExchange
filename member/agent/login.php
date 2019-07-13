<?php
define('Load_Info', 2);
session_start();
include_once("./Lib/xltm.class.php");
if($_POST['username'] && $_POST['password']){
	$xltm->User->login_user();
	$xltm->gourl('','./agent.php');
}else {
	$xltm->gourl("请输入账号和密码.","");
}
?>