<?
define('Load_Info', false);
session_start();
include_once("./Lib/xltm.class.php");
error_reporting(E_ALL ^ E_NOTICE);
$xltm->tpls->LoadTemplate("./tmpfiles/login.html");
$xltm->tpls->Show();
?>