<?php
define('Load_Info', true);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$currentusername = $xltm->user_info['username'];
$agent = $xltm->user_info['agent'];
$types = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
if($currentusername=='')
{
	$xltm->gourl('','login.php');
	exit();
}
switch($types)
{	
	case 'add':
	
	$addtimes=date('Y-m-d H:i:s',time());
	$xltm->SQL->Execute("insert into `payment` set orderno='{$_REQUEST['orderno']}',agent='{$_REQUEST['agent']}',username='{$currentusername}',money='{$_REQUEST['money']}',add_time='{$addtimes}',mp='{$_REQUEST['bankname']}',cur='{$_REQUEST['realname']}'");
	mb("已提交成功，联系在线客服确认您的款项!","",0);
		exit();
	case 'log':
	if (!isset($_REQUEST)) $_REQUEST=&$HTTP_GET_VARS ;
		$PageNum =(isset($_REQUEST['PageNum']))?$_REQUEST['PageNum']:1;
		$RecCnt =(isset($_REQUEST['RecCnt']))?intval($_REQUEST['RecCnt']):'-1';
		$PageSize = 150000;
		//End page
		$rows=$xltm->SQL->GetRows("select * from `payment` where username='{$currentusername}' order by add_time desc");
		$xltm->tpls->LoadTemplate("./tmpfiles/payment_log.html",false);
		$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
		$RecCnt = $xltm->tpls->MergeBlock('tr','array',$rows);
		$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
		$showPage=($RecCnt>0)?1:'';
		$xltm->tpls->Show();
		break;
	default:
	if($xltm->user_info['username'])
	{
		$r6=$xltm->config['minmoney'];//最低充值金额
		$order_no = date('YmdHis',time()).rand(100,200);
		$currentusername=$currentusername;
		$isopen = $xltm->startTime();
		$cancash = round($xltm->AvailableCash(),2)* $xltm->config['cost_exchange_rate'];	
		$xltm->tpls->LoadTemplate("./tmpfiles/saoma.html");
		$xltm->tpls->Show();
	}else {
		$xltm->gourl('','login.php');
	}
}
?>