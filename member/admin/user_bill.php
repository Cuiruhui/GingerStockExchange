<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
if($xltm->user_info['username']){
	$PageSize = isset($_REQUEST['PageSize'])?$_REQUEST['PageSize']:30;
	$PageNum =isset($_REQUEST['PageNum'])?$_REQUEST['PageNum']:1;
	$RecCnt =isset($_REQUEST['RecCnt'])?intval($_REQUEST['RecCnt']):'-1';
	$demo = isset($_GET['demo']) ? $_GET['demo'] : '0';
	$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',time()-7*24*3600);
	$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : $xltm->sys_date;
	$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
	$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
	if($start_date>$end_date)
	{
		$xltm->showMsg("用户帐单","查询开始日期不能大于结束日期！",false,"back");
	}
	$where .=" a.bill_date>='{$start_date}' and a.bill_date<='{$end_date}'";
	if($username)
	{
		$where .=" and a.username like '%{$username}%'";
	}
	if($demo!='-1')
	{
		$where .=" and b.demo='{$demo}'";
	}
	if($agent) $where .= " and b.agent='{$agent}'";
	$rows=$xltm->SQL->GetRows("select a.* from `user_bill` a left join `user_users` b on a.username=b.username where {$where} order by a.bill_date asc, a.username asc");
	$agents = $xltm->SQL->GetRows("select * from `user_agent` where active='yes'");
	$xltm->tpls->LoadTemplate("./tmpfiles/user_bill.html",false);
	$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
	$RecCnt = $xltm->tpls->MergeBlock('tr','array',$rows);
	$xltm->tpls->MergeBlock('ag','array',$agents);
	$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
	$xltm->tpls->Show();
}
else
{
	$xltm->gourl('账号超时,请重新登陆','login.php');
}

function event_load($BlockName,&$CurrRec,$RecNum){
	//$CurrRec['cost_trade_money_total'] = $CurrRec['cost_trade_money_total']>0 ? '-'.$CurrRec['cost_trade_money_total']:$CurrRec['cost_trade_money_total'];
}
?>