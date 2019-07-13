<?php
define('Load_Info', 1);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$week1 = date('w');
$day1 = date('d');
$day2 = date('t');
$tmp = date('Y-m-1',time()-($day1+1)*24*3600);
$day3 = date('t',$tmp);
$defDate=date('Y-m-d',time()-(6*24*60*60));
$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
$PageSize = 15;
$start_date=($_GET['start_date'])?$_GET['start_date']:$xltm->sys_date;
$end_date=$_GET['end_date']?$_GET['end_date']:$xltm->sys_date;
$detail = $_GET['detail'];
if($detail) //ϸ
{
	$id = $_GET['id'];
	if($id == -1)
	{
		$date = $xltm->sys_date;
	}
	else
	{
		$bill = $xltm->SQL->GetRow("Select * from `bill` where id='{$id}'");
		$date = date('Y-m-d',strtotime($bill['add_time']));
	}
	$billlist = $xltm->SQL->GetRows("select * from `bill_log` where username='{$xltm->user_info['username']}' and (add_time between '{$date} 0:0:0' and '{$date} 23:59:59') order by add_time desc");
	$xltm->tpls->LoadTemplate("./tmpfiles/bill_log.html");
	$xltm->tpls->MergeBlock('tr','array',$billlist);
	$xltm->tpls->Show();
}
else //
{
	//??
	$billlist = $xltm->SQL->GetRows("select * from `bill` where username='{$xltm->user_info['username']}' and (add_time between '{$start_date} 0:0:0' and '{$end_date} 23:59:59') order by add_time desc");
	if($start_date==$end_date && $start_date==$xltm->sys_date) //????
	{
		if(!$billlist)//?????
		{
			//????????????
			$totalmoney = 0;
			$color = '';
			$row1 = $xltm->SQL->GetRow("select sum(money) as tt from `bill_log` where username='{$xltm->user_info['username']}' and bill_type<>'??' and (add_time between '{$xltm->sys_date} 0:0:0' and '{$xltm->sys_date} 23:59:59')");
			if(is_array($row1))
			{
				$totalmoney = round($row1['tt'],2);
				$color = $totalmoney>0 ? 'red' : 'green';
			}
			$billlist=array('0'=>array(
				'id'=>-1,
				'add_time'=>$xltm->sys_date,
				'username'=>$xltm->user_info['username'],
				'money'=>$totalmoney,
				'color'=>$color
			));
		}
	}
	$xltm->tpls->LoadTemplate("./tmpfiles/bill.html");
	$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
	$RecCnt = $xltm->tpls->MergeBlock('tr','array',$billlist);
	$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
	$showPage=($RecCnt>0)?1:'';
	$xltm->tpls->Show();
}

function bill_event($BlockName,&$CurrRec,$RecNum){	
	if($CurrRec['money']!=0)
	{
		$CurrRec['color'] = $CurrRec['money']>0 ? 'red' : 'green';
	}
	else
	{
		$CurrRec['color'] = '';
	}
}
?>