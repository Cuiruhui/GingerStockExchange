<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$codelist='';
	$rows = $xltm->SQL->GetRows("Select * from `buy_trust` where app='1' and user='{$xltm->user_info['username']}' order by stock_trust_time asc");
	foreach($rows as $r)
	{	
		$codelist .= (empty($codelist)?'':',').$r['type'].$r['code'];
	}
	$xltm->tpls->LoadTemplate("./tmpfiles/entrust.html");
	$xltm->tpls->MergeBlock('tr','array',$rows);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>