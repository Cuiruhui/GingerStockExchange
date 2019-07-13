<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : 0;
	$codelist='';
	$where = '';
	if($status>0)
	{
		$where = " and app='{$status}'";
	}
	$rows = $xltm->SQL->GetRows("Select * from `buy_trust` where user='{$xltm->user_info['username']}' {$where} and stock_trust_date='{$xltm->sys_date}' order by stock_trust_time desc");
	foreach($rows as $r)
	{	
		$codelist .= (empty($codelist)?'':',').$r['type'].$r['code'];
	}
	$xltm->tpls->LoadTemplate("./tmpfiles/entrust_search.html",false);
	$xltm->tpls->MergeBlock('tr','array',$rows);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}

function event_status($BlockName,&$CurrRec,$RecNum)
{
	if($CurrRec['app']=='1')
	{
		$CurrRec['status'] = '委托中';
	}
	else if($CurrRec['app']=='2')
	{
		$CurrRec['status'] = '<font color=red>已成交</font>';
	}
	else if($CurrRec['app']=='3')
	{
		$CurrRec['status'] = '<font color=gray><em>已撤单(用户)</em></font>';
	}
	else
	{
		$CurrRec['status'] = '<font color=gray><em>已撤单(系统)</em></font>';
	}
}














?>