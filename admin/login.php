<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?
define('Load_Info', false);
session_start();
include_once("./Lib/xltm.class.php");
error_reporting(E_ALL ^ E_NOTICE);
$xltm->tpls->LoadTemplate("./tmpfiles/login.html");
$xltm->tpls->Show();
?>