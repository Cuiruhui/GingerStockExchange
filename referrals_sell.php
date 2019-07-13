<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	if($xltm->user_info['referrals']==0)
	{
		echo "<script>parent.ymPrompt.errorInfo({title:'下线平仓',message:'对不起，你没有开通下线会员权限！',handler:function(){self.location.href='stock_lists.php';}});</script>";
		exit();
	}
	$today = date('Y-m-d',time());
	$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : $today;
	$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : $today;
	$PageSize = isset($_REQUEST['PageSize'])?$_REQUEST['PageSize']:30;
	$PageNum =isset($_REQUEST['PageNum'])?$_REQUEST['PageNum']:1;
	$RecCnt =isset($_REQUEST['RecCnt'])?intval($_REQUEST['RecCnt']):'-1';
	$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
	if($start_date>$end_date)
	{
		echo "<script>parent.ymPrompt.errorInfo({title:'下线平仓',message:'对不起，开始日期不能大于结束日期！',handler:function(){history.go(-1);}});</script>";
		exit();
	}
	$query = "select a.* from `buy_deal` a inner join `user_users` b on a.user=b.username where a.sell='1' and a.sell_trust_date>='{$start_date}' and a.sell_trust_date<='{$end_date}' and b.referr_name='{$xltm->user_info['username']}'";
	if($username)
	{
		$query .=" and b.username like '%{$username}%'";
	}
	$query .= " order by a.sell_trust_time desc";
	$rows = $xltm->SQL->GetRows($query);
	$xltm->tpls->LoadTemplate("./tmpfiles/referrals_sell.html",false);
	$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
	$RecCnt = $xltm->tpls->MergeBlock('tr','array',$rows);
	$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
function event_sum($BlockName,&$CurrRec,$RecNum){
	$CurrRec['total_cost'] = $CurrRec['bull_cost_money'] + $CurrRec['sell_cost_money'];
	if($CurrRec['gain']>0)
	{
		$CurrRec['gaincolor']='#ff0000';
	}
	else if($CurrRec['gain']<0)
	{
		$CurrRec['gaincolor']='green';
	}
	else
	{
		$CurrRec['gaincolor']='#000000';
	}
}
?>