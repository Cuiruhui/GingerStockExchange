<?php
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");


$message=$xltm->SQL->GetRows("SELECT * FROM `newcaijin`  order by add_time desc limit 6");
$note=$xltm->SQL->GetRows("SELECT * FROM `common`  order by add_time desc limit 2");


$xltm->tpls->LoadTemplate("./tmpfiles/xinwen.html");
$xltm->tpls->MergeBlock('li','array',$message);
$xltm->tpls->MergeBlock('tr','array',$note);
$xltm->tpls->Show();
?>