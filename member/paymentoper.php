<?php
define('Load_Info', true);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$xltm->tpls->LoadTemplate("./tmpfiles/paymentoper.html");
$xltm->tpls->Show();
?>