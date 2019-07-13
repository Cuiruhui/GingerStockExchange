<?php
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");
$client = isset($_REQUEST['client']) ? $_REQUEST['client'] : 'false';

$message=$xltm->SQL->GetRows("SELECT * FROM `newcaijin`  order by add_time desc limit 6");
$note=$xltm->SQL->GetRows("SELECT * FROM `common`  order by add_time desc limit 7");


$xltm->tpls->LoadTemplate("./tmpfiles/login2.html");
$xltm->tpls->MergeBlock('li','array',$message);
$xltm->tpls->MergeBlock('tr','array',$note);
$xltm->tpls->Show();
?>