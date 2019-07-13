<?php
define('Load_Info', 0);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$client = isset($_GET['client']) ? $_GET['client'] : 'false';
$xltm->tpls->LoadTemplate("./tmpfiles/login.html");
$xltm->tpls->Show();
?>