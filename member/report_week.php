<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$week = isset($_GET['week']) && is_numeric($_GET['week']) ? intval($_GET['week']) : '0';
	$data = array();
	$weekname = array('0'=>'星期一','1'=>'星期二','2'=>'星期三','3'=>'星期四','4'=>'星期五','5'=>'<font color=green>星期六<font>','6'=>'<font color=red>星期日</font>');
	$weekday = date('w');
	if($weekday ==0 ) $weekday = 6;
	if($week=='-1') //上周
	{
		$start_date = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-$weekday+1-7,date("Y"))); 
		$end_date = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-$weekday+7-7,date("Y")));
	}
	else
	{
		$start_date = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-$weekday+1,date("Y")));
		$end_date = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-$weekday+7,date("Y")));
	}
	for($i=0; $i<7; $i++)
	{
		$trade_total = 0;
		$save_total = 0;
		$cost_total = 0;
		$save_cost_total = 0;
		$gain = 0;
		$date = date('Y-m-d',strtotime(DateAdd('d',$i,$start_date)));
		if($i<5)
		{
			if($row=$xltm->SQL->GetRow("select * from `user_bill` where username='{$xltm->user_info['username']}' and bill_date='{$date}'"))
			{
				$trade_total = $row['today_trade_money_total'];
				$save_total = $row['save_money_total'];
				$save_cost_total = $row['cost_save_money_total'];
				$cost_total = $row['cost_trade_money_total'];
				$save_cost_total = $row['cost_save_money_total'];
				$gain = $row['gain'];
			}
		}
		else
		{
			$trade_total = 0;
			$save_total = 0;
			$cost_total = 0;
			$save_cost_total = 0;
			$gain = 0;
		}
		$data[] = array('week'=>$weekname[$i],'date'=>$date,'trade_total'=>$trade_total,'save_total'=>$save_total,'cost_total'=>$cost_total,'save_cost_total'=>$save_cost_total,'gain'=>$gain);
	}
	$xltm->tpls->LoadTemplate("./tmpfiles/report_week.html",false);
	$xltm->tpls->MergeBlock('tr','array',$data);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}

function row_even($BlockName,&$CurrRec,$RecNum)
{
	$CurrRec['cost_total'] = 0-$CurrRec['cost_total'];
	$CurrRec['save_cost_total'] = 0-$CurrRec['save_cost_total'];
	if($CurrRec['gain']>0)
	{
		$CurrRec['gain_color'] = '#ff0000';
	}
	else if($CurrRec['gain']<0)
	{
		$CurrRec['gain_color']='green';
	}
	else
	{	
		$CurrRec['gain_color']='#000000';
	}
}

?>