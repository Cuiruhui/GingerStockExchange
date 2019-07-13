<?php
include_once('global.php');
$xltm->user_log(1,"浏览用户持仓列表(历史)");

$demo = isset($_GET['demo']) ? $_GET['demo'] : 0;
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',time()-24*3600);
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-d',time()-24*3600);
$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
if($start_date>$end_date)
{
	$xltm->errorInfo('会员持仓','开始日期必须小于等于结束日期！','history.go(-1);');
}
$where = "b.demo='{$demo}' and b.agent='{$xltm->user_info['username']}'";
if($username)
{
	$where .=" and a.username like '%{$username}%'";
}
if($start_date && $end_date)
{
	$where .=" and a.save_date>='{$start_date}' and a.save_date<='{$end_date}'";
}
$rows=$xltm->SQL->GetRows("select a.* from `deal_save` a left join `user_users` b on a.username=b.username where {$where} order by a.bull_trust_date asc, a.username asc");
$xltm->tpls->LoadTemplate("./tmpfiles/deal_history.html",false);
$xltm->tpls->MergeBlock('tr','array',$rows);
$xltm->tpls->Show();

?>