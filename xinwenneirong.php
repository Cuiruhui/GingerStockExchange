<?php
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");

$id =(isset($_GET['id']))?$_GET['id']:44;
$message=$xltm->SQL->GetRow("SELECT * FROM `newcaijin`  where id={$id}");
$note=$xltm->SQL->GetRows("SELECT * FROM `common`  order by add_time desc limit 7");


$xltm->tpls->LoadTemplate("./tmpfiles/xinwenneirong.html",false);
$xltm->tpls->MergeBlock('tr','array',$note);
$xltm->tpls->Show();
?>