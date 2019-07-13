<?php
define('Load_Info', true);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
//file_put_contents("ssss.txt",json_encode($xltm->user_info));
//print_r($xltm->user_info);exit;
$currentusername = $xltm->user_info['username'];
$agent = $xltm->user_info['agent'];
$types = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
switch($types)
{	
	case 'add':
	
	$xltm->SQL->Execute("insert into `payment` set orderno='{$_REQUEST['orderno']}',agent='{$_REQUEST['agent']}',username='{$currentusername}',money='{$_REQUEST['money']}',add_time='{$_REQUEST['add_time']}'");
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
		$payway1=$xltm->SQL->GetRow("select * from `pay_config` where id=39");
                $cardno=$payway1["account"];
                $cardname=$payway1["payee"];
		$r6=$xltm->config['minmoney']; //×îµÍ³äÖµ½ð¶î
		
		$currentusername=$currentusername;
		$isopen = $xltm->startTime();
		$cancash = round($xltm->AvailableCash(),2)* $xltm->config['cost_exchange_rate'];	
		$xltm->tpls->LoadTemplate("./wap/wappay.html");
		$xltm->tpls->Show();
	}else {
		$xltm->gourl('','waplogin.php');
	}
}
?>
