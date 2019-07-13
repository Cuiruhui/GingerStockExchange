<?php
include_once('global.php');
$xltm->user_log(1,"浏览汇总帐单");

$demo = isset($_GET['demo']) ? $_GET['demo'] : 0;
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',time()-7*24*3600);
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : $xltm->sys_date;
$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
if($start_date>$end_date)
{
	$xltm->errorInfo('汇总帐单','开始日期必须小于等于结束日期！','history.go(-1);');
}
$where = "b.demo='{$demo}' and b.agent='{$xltm->user_info['username']}'";
if($username)
{
	$where .=" and a.username like '%{$username}%'";
}
if($start_date && $end_date)
{
	$where .=" and a.bill_date>='{$start_date}' and a.bill_date<='{$end_date}'";
}
$rows=$xltm->SQL->GetRows("select a.* from `user_bill` a left join `user_users` b on a.username=b.username where {$where} order by a.bill_date asc, a.username asc");
$xltm->tpls->LoadTemplate("./tmpfiles/user_bill.html",false);
$xltm->tpls->MergeBlock('tr','array',$rows);
$xltm->tpls->Show();

function event_load($BlockName,&$CurrRec,$RecNum){
	//$CurrRec['cost_trade_money_total'] = $CurrRec['cost_trade_money_total']>0 ? '-'.$CurrRec['cost_trade_money_total']:$CurrRec['cost_trade_money_total'];
}
?>