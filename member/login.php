<?php
define('Load_Info', false);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$client = isset($_REQUEST['client']) ? $_REQUEST['client'] : 'false';
$xltm->tpls->LoadTemplate("./tmpfiles/login.html");
$xltm->tpls->Show();
?>