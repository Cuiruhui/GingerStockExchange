<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");

if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
$PageNum =isset($_REQUEST['PageNum'])?$_REQUEST['PageNum']:1;
$RecCnt =isset($_REQUEST['RecCnt'])?intval($_REQUEST['RecCnt']):'-1';
$demo = isset($_REQUEST['demo'])?$_REQUEST['demo']:'0';
$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '0';
$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
$start_date = isset($_REQUEST['start_date']) ? date('Y-m-d',strtotime($_REQUEST['start_date'])) :  date('Y-m-d',time());
$end_date = isset($_REQUEST['end_date']) ? date('Y-m-d',strtotime($_REQUEST['end_date'])) : date('Y-m-d',time());
if(strtotime($start_date)>strtotime($end_date))
{
	$xltm->showMsg("用户委托","查询开始日期不能大于结束日期！",false,"back");
}
$randstr = rand(0,10000);
$codelist='';
$inquery = " a.stock_trust_date>='{$start_date}' and a.stock_trust_date<='{$end_date}'";
if($demo !='-1') $inquery .=" and b.demo='$demo'";
if($agent) $inquery .=" and b.agent='{$agent}'";
if($username) $inquery .=" and b.username like '%{$username}%'";
if($status!='0') $inquery .=" and a.app='{$status}'";
$PageSize = 25;
$query = "SELECT a.* FROM `buy_trust` a left join `user_users` b on a.user=b.username where {$inquery} ORDER BY a.stock_trust_time DESC ";
$trust=$xltm->SQL->GetRows($query);
$agents = $xltm->SQL->GetRows("select * from `user_agent` where active='yes'");
$xltm->tpls->LoadTemplate("./tmpfiles/trust.html",false);
$xltm->tpls->MergeBlock('ag','array',$agents);
$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
$RecCnt =$xltm->tpls->MergeBlock('tr','array',$trust);
$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
$showPage=($RecCnt>0)?1:'';
$xltm->tpls->Show();

function trust_event($BlockName,&$CurrRec,$RecNum){
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