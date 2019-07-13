<?php
require_once('global.php');
$mdate=date("D M j G:i:s T Y");
$news=$xltm->SQL->GetRows("SELECT * FROM common where type='2' ORDER BY `add_time` DESC");
$xltm->tpls->LoadTemplate("./tmpfiles/news.html");
$xltm->tpls->MergeBlock('tr','array',$news);
$xltm->tpls->Show();
?>