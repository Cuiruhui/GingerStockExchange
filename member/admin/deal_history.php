<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
set_time_limit(0);

if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
$PageSize = isset($_REQUEST['PageSize'])?$_REQUEST['PageSize']:30;
$PageNum =isset($_REQUEST['PageNum'])?$_REQUEST['PageNum']:1;
$RecCnt =isset($_REQUEST['RecCnt'])?intval($_REQUEST['RecCnt']):'-1';
$demo = isset($_REQUEST['demo'])?$_REQUEST['demo']:'0';
$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',time()-7*24*3600);
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-d',time());
if($start_date>$end_date)
{
	$xltm->showMsg("用户持仓","查询开始日期不能大于结束日期！",false,"back");
}
$randstr = rand(0,10000);
$inquery = " a.save_date>='{$start_date}' and a.save_date<='{$end_date}'";
if($demo !='-1') $inquery .=" and b.demo='$demo'";
if($agent) $inquery .=" and b.agent='{$agent}'";
if($username) $inquery .=" and b.username like '%{$username}%'";
$query = "SELECT a.*,b.agent FROM `deal_save` a left join `user_users` b on a.username=b.username where $inquery ORDER BY a.bull_trust_date asc, a.username asc ";

$deal=$xltm->SQL->GetRows($query);
$agents = $xltm->SQL->GetRows("select * from `user_agent` where active='yes'");
$xltm->tpls->LoadTemplate("./tmpfiles/deal_history.html");
$xltm->tpls->MergeBlock('ag','array',$agents);
$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
$RecCnt =$xltm->tpls->MergeBlock('tr','array',$deal);
$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
$showPage=($RecCnt>0)?1:'';
$xltm->tpls->Show();

function deal_event($BlockName,&$CurrRec,$RecNum){
	global $xltm;
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
