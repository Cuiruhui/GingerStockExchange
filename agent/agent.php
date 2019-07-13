<?php
require_once('global.php');
$mdate=date("D M j G:i:s T Y");
$common=$xltm->SQL->GetRows("SELECT * FROM common where type='2' ORDER BY `add_time` DESC limit 0,3");
$xltm->tpls->LoadTemplate("./tmpfiles/agent.html");
$xltm->tpls->MergeBlock('news','array',$common);
$xltm->tpls->Show();
?>