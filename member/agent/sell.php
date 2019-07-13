<?php
include_once('global.php');
$xltm->user_log(1,"浏览用户当日平仓列表");

$demo = isset($_REQUEST['demo']) ? $_REQUEST['demo'] : 0;
$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
$where = "a.sell='1' and b.demo='{$demo}' and b.agent='{$xltm->user_info['username']}' and a.bull_trust_date='{$xltm->sys_date}' ";
if($username)
{
	$where .=" and a.user like '%{$username}%'";
}
$rows=$xltm->SQL->GetRows("select a.* from `buy_deal` a left join `user_users` b on a.user=b.username where {$where} order by a.user asc,a.id asc");
$xltm->tpls->LoadTemplate("./tmpfiles/sell.html",false);
$xltm->tpls->MergeBlock('tr','array',$rows);
$xltm->tpls->Show();

function event_sum($BlockName,&$CurrRec,$RecNum){
	$CurrRec['total_cost'] = $CurrRec['bull_cost_money'] + $CurrRec['sell_cost_money'];
}
?>