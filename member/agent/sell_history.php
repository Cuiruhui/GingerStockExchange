<?php
include_once('global.php');
$xltm->user_log(1,"浏览用户平仓列表(历史)");

$demo = isset($_GET['demo']) ? $_GET['demo'] : 0;
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',time()-7*24*3600);
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-d',time());
$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
if($start_date>$end_date)
{
	$xltm->errorInfo('会员平仓单','开始日期必须小于等于结束日期！','history.go(-1);');
}
$where = "a.sell='1' and b.demo='{$demo}' and b.agent='{$xltm->user_info['username']}'";
if($username)
{
	$where .=" and a.user like '%{$username}%'";
}
if($start_date && $end_date)
{
	$where .=" and a.sell_trust_date>='{$start_date}' and a.sell_trust_date<='{$end_date}'";
}
$rows=$xltm->SQL->GetRows("select a.* from `buy_deal` a left join `user_users` b on a.user=b.username where {$where} order by a.sell_trust_time asc, a.user asc");
$xltm->tpls->LoadTemplate("./tmpfiles/sell_history.html",false);
$xltm->tpls->MergeBlock('tr','array',$rows);
$xltm->tpls->Show();

function event_sum($BlockName,&$CurrRec,$RecNum){
	$CurrRec['gaincolor'] = '#000000';
	if($CurrRec['gain']>0)
	{
		$CurrRec['gaincolor'] = '#ff0000';
	}
	else if($CurrRec['gain']<0)
	{
		$CurrRec['gaincolor'] = 'green';
	}
	$CurrRec['total_cost'] = $CurrRec['bull_cost_money'] + $CurrRec['sell_cost_money'];
}
?>