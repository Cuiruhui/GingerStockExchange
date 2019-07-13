<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	if($xltm->user_info['referrals']==0)
	{
		echo "<script>parent.ymPrompt.errorInfo({title:'下线持仓',message:'对不起，你没有开通下线会员权限！',handler:function(){self.location.href='stock_lists.php';}});</script>";
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
		echo "<script>parent.ymPrompt.errorInfo({title:'下线持仓',message:'对不起，开始日期不能大于结束日期！',handler:function(){history.go(-1);}});</script>";
		exit();
	}
	$query = "select a.* from `deal_save` a inner join `user_users` b on a.username=b.username where a.save_date>='{$start_date}' and a.save_date<='{$end_date}' and b.referr_name='{$xltm->user_info['username']}'";
	if($username)
	{
		$query .=" and b.username like '%{$username}%'";
	}
	$query .= " order by a.bull_trust_date desc";
	$rows = $xltm->SQL->GetRows($query);
	$xltm->tpls->LoadTemplate("./tmpfiles/referrals_deal.html",false);
	$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
	$RecCnt = $xltm->tpls->MergeBlock('tr','array',$rows);
	$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>