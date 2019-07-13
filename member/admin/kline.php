<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
$stockcode = isset($_GET['stockcode']) ? $_GET['stockcode'] : '600000';
$stocktype = isset($_GET['stocktype']) ? $_GET['stocktype'] : 'sh';
$xltm->tpls->LoadTemplate("./tmpfiles/kline.html");
$xltm->tpls->Show();
?>