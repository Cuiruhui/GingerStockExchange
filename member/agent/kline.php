<?php
include_once('global.php');
$stockcode = isset($_GET['stockcode']) ? $_GET['stockcode'] : '600000';
$stocktype = isset($_GET['stocktype']) ? $_GET['stocktype'] : 'sh';
$xltm->user_log(1,"Ʊ{$stocktype}{$stockcode}Kͼ)");
$xltm->tpls->LoadTemplate("./tmpfiles/kline.html");
$xltm->tpls->Show();
?>