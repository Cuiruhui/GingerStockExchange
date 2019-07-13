<?php
include_once('global.php');
$xltm->user_log(1,"浏览代理佣金结算记录");
$rows=$xltm->SQL->GetRows("select * from `agent_settlement` where agent='{$xltm->user_info['username']}' order by add_time desc");
$xltm->tpls->LoadTemplate("./tmpfiles/settlement.html",false);
$xltm->tpls->MergeBlock('tr','array',$rows);
$xltm->tpls->Show();

?>