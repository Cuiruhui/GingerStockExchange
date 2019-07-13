<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
if($xltm->user_info['username']){
	$PageSize = isset($_REQUEST['PageSize'])?$_REQUEST['PageSize']:30;
	$PageNum =isset($_REQUEST['PageNum'])?$_REQUEST['PageNum']:1;
	$RecCnt =isset($_REQUEST['RecCnt'])?intval($_REQUEST['RecCnt']):'-1';
	$demo = isset($_REQUEST['demo'])?$_REQUEST['demo']:'0';
	$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
	$referr_name = isset($_REQUEST['referr_name']) ? $_REQUEST['referr_name'] : '';		//上线代理帐号2012-4-13李兴添加


	$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
	$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',time()-7*24*3600);
	$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-d',time());
	if($_REQUEST['today']=='yes') $start_date = $xltm->sys_date;
	$where = "a.sell='1' and a.sell_trust_date>='{$start_date}' and a.sell_trust_date<='{$end_date}'";
	if($agent) $where .= " and a.agent='{$agent}'";

	if($referr_name) $where .=" and (b.referr_name='{$referr_name}' or b.username='{$referr_name}')";		//上线代理帐号2012-4-13李兴添加

	if($demo!='-1') $where .= "  and b.demo='{$demo}'";
	if($username)
	{
		$where .=" and a.user like '%{$username}%'";
	}
	//$rows=$xltm->SQL->GetRows("select a.* from `buy_deal` a left join `user_users` b on a.user=b.username where {$where} order by a.sell_trust_time desc , a.user asc,a.id asc");
	$rows=$xltm->SQL->GetRows("select a.* from `buy_deal` a left join `user_users` b on a.user=b.username where {$where} order by a.sell_trust_time desc");
	$agents = $xltm->SQL->GetRows("select * from `user_agent` where active='yes'");

	$referrs = array();
	if($agent){					//取二级代理帐号
		$referrs = $xltm->SQL->GetRows("select * from `user_users` where agent='{$agent}' and referrals=1");
	}

	$xltm->tpls->LoadTemplate("./tmpfiles/sell.html",false);
	$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
	$RecCnt = $xltm->tpls->MergeBlock('tr','array',$rows);
	$xltm->tpls->MergeBlock('ag','array',$agents);
	$xltm->tpls->MergeBlock('ref','array',$referrs);		//加入二级代理帐号

	$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
	$xltm->tpls->Show();
}
else
{
	$xltm->gourl('账号超时,请重新登陆','login.php');
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