<?php
session_start();
define('Load_Info', 1);
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$client = isset($_REQUEST['client']) ? $_REQUEST['client'] : 'false';

	$row = $xltm->SQL->GetRow("Select * from system_text where name='system_rule'");
	$system_rule = htmlspecialchars_decode($row['value']);

$xltm->tpls->LoadTemplate("./tmpfiles/clause.html");
$xltm->tpls->Show();
?>