<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$cost_save = $xltm->user_info['cost_save']*1000;
	$cost_sell_limit = $xltm->config['cost_sell_limit']*100;
	
	
	$row = $xltm->SQL->GetRow("Select * from system_text where name='system_rule'");
	$system_rule = htmlspecialchars_decode($row['value']);
	$xltm->tpls->LoadTemplate("./tmpfiles/w2rules.html");
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','wlogin.php');
}
?>