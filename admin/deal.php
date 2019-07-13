<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
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
$buy_type = isset($_REQUEST['buy_type'])?$_REQUEST['buy_type']:'0';
$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
$referr_name = isset($_REQUEST['referr_name']) ? $_REQUEST['referr_name'] : '';		//上线代理帐号2012-4-13李兴添加

$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
$dealid = isset($_REQUEST['dealid']) ? $_REQUEST['dealid'] : '';
$start_date = isset($_REQUEST['start_date']) ? date('Y-m-d',strtotime($_REQUEST['start_date'])) :  date('Y-m-d',time());
$end_date = isset($_REQUEST['end_date']) ? date('Y-m-d',strtotime($_REQUEST['end_date'])) : date('Y-m-d',time());
if(strtotime($start_date)>strtotime($end_date))
{
	$xltm->showMsg("用户持仓","查询开始日期不能大于结束日期！",false,"back");
}
$randstr = rand(0,10000);
$codelist='';
$inquery = " and a.bull_trust_date>='{$start_date}' and a.bull_trust_date<='{$end_date}'";
if($demo !='-1') $inquery .=" and b.demo='$demo'";
if($agent) $inquery .=" and b.agent='{$agent}'";
if($referr_name) $inquery .=" and (b.referr_name='{$referr_name}' or b.username='{$referr_name}')";		//上线代理帐号2012-4-13李兴添加

if($username) $inquery .=" and b.username like '%{$username}%'";
if($dealid) $inquery .= " and a.id='{$dealid}'";
if($buy_type) $inquery .=" and a.buy_type='{$buy_type}'";
//$query = "SELECT a.*,b.cash FROM buy_deal a left join `user_users` b on a.user=b.username where a.sell='0' $inquery ORDER BY a.bull_trust_time DESC,a.user asc,b.agent asc ";
$query = "SELECT a.*,b.cash FROM buy_deal a left join `user_users` b on a.user=b.username where a.sell='0' $inquery ORDER BY a.bull_trust_time DESC ";


$deal=$xltm->SQL->GetRows($query);
$agents = $xltm->SQL->GetRows("select * from `user_agent` where active='yes'");

$referrs = array();
if($agent){					//取二级代理帐号
	$referrs = $xltm->SQL->GetRows("select * from `user_users` where agent='{$agent}' and referrals=1");
}

$xltm->tpls->LoadTemplate("./tmpfiles/deal.html");
$xltm->tpls->MergeBlock('ag','array',$agents);
$xltm->tpls->MergeBlock('ref','array',$referrs);		//加入二级代理帐号

$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
$RecCnt =$xltm->tpls->MergeBlock('tr','array',$deal);
$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
$showPage=($RecCnt>0)?1:'';
$xltm->tpls->Show();

function bull_event($BlockName,&$CurrRec,$RecNum){
	global $xltm,$codelist;
	$codelist .= (empty($codelist)?'':',').$CurrRec['stock_type'].$CurrRec['stock_code'];
	//$CurrRec['cancash'] = $xltm->AvailableCash($CurrRec['user']);
	//$CurrRec['save_day']=$xltm->save_day($xltm->sys_date,$CurrRec['bull_trust_date']);
}
?>