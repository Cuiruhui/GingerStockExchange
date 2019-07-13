<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';

if($type=='pay')
{
	$id = $_REQUEST['id'];
	if(!isset($id) || !is_numeric($id))
	{
		$xltm->showMsg("佣金结算","请指定正确的ID值！",false,"back");
	}
	else
	{
		$atmlog = $xltm->SQL->GetRow("Select * from `agent_settlement` where id='{$id}'");
		if(!is_array($atmlog))
		{
			$xltm->showMsg("佣金结算","找不到要打款的记录！",false,"back");
		}
		else if($atmlog['ispay']=='1')
		{
			$xltm->showMsg("佣金结算","该笔佣金记录已经打款！",false,"back");
		}
		else
		{
			$xltm->SQL->Execute("update `agent_settlement` set ispay='1',pay_date='{$xltm->sys_date}' where id='{$id}'");
			$xltm->showMsg("佣金结算","设置打款状态成功！",true,"agent_settlement.php");
		}
	}
}
else if($type=='batch')
{
	$ids="";
	foreach ($_POST['id'] as $d)
	{
		$ids .="'".$d."',";
	}
	$ids .=substr($ids,0,-1);
	if($ids)
	{
		$xltm->SQL->Execute("Update `agent_settlement` set ispay='1',pay_date='{$xltm->sys_date}' where ispay='0' and id in ({$ids})");
	}
}
//分页设置
if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
$PageSize = 20;
$inquiry="";
$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
$ispay = isset($_REQUEST['ispay']) ? $_REQUEST['ispay'] : '999';
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-1',time());
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-d',time());
if($start_date > $end_date)
{
	$xltm->showMsg("佣金结算","开始日期不能大于结算日期！","false","back");
}
if($agent)
{
	$inquiry .=" and agent='{$agent}'";
}
if($ispay!='999')
{
	$inquiry .=" and ispay='{$ispay}'";
}
$inquiry .=" and start_date>='{$start_date}' and end_date<='{$end_date}'";
$atmlog=$xltm->SQL->GetRows("SELECT * FROM `agent_settlement` where id>0 $inquiry order by add_time desc");
$agent_list=$xltm->SQL->GetRows("SELECT username FROM `user_agent` where active='yes' order by id");
$xltm->tpls->LoadTemplate("./tmpfiles/agent_settlement.html",false);
$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
$xltm->tpls->MergeBlock('ag','array',$agent_list);
$RecCnt =$xltm->tpls->MergeBlock('tr','array',$atmlog);
$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
$showPage=($RecCnt>0)?1:'';
$xltm->tpls->show();

function event_row($BlockName,&$CurrRec,$RecNum)
{
	if($CurrRec['ispay']=='1')
	{
		$CurrRec['status'] = '<font color=red>已打款</font>';
		$CurrRec['disabled'] = ' disabled';
	}
	else
	{
		$CurrRec['status'] = '未打款';
		$CurrRec['disabled'] = '';
	}
}
?>
