<?php
define('Load_Info', false);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");

$xltm->tpls->LoadTemplate("./tmpfiles/wlogin.html");
$xltm->tpls->Show();
?>