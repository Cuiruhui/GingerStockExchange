<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$xltm->user_log(9,"浏览持股/卖出");
	$today = $xltm->sys_date;
	$yestoday = date('Y-m-d',time()-24*3600);
	$qdate = (isset($_GET['date']) && strtotime($_GET['date'])) ? $_GET['date'] : $today;
	$alert = '';
	$iscount = 0;
	if(date('w',time())==6 || date('w',time())==0)
	{
		$iscount = 1;
	}
	else if($row=$xltm->SQL->GetRow("select * from `gala_config` where gala_date='{$today}'"))
	{
		$iscount = 1;
	}
	else if($row=$xltm->SQL->GetRow("select * from `user_bill` where bill_date='{$today}' limit 1"))
	{
		$iscount=1;
	}
	if($qdate>=$xltm->sys_date && !$iscount)
	{
		$alert = "<script>parent.ymPrompt.confirmInfo({title:'日报',message:'很抱歉当天日报还未统计，请等待15:00收盘后管理员统计！<br><br>您可以在“持仓/卖出”查看当天的数据，现在就去？',handler:function(fn){if(fn=='ok'){self.location.href='deal.php';}}});</script>";
	}
	/*
	if($qdate>=$xltm->sys_date)
	{
		print "<script>parent.ymPrompt.alert({title:'日报',message:'只允许查询当日前一天之前的报表！',handler:function(){self.location.href='report_day.php';}});</script>";
		exit();
	}
	*/
	//读取手续费率
	$cost_bull = $xltm->user_info['cost_bull']*1000;
	$cost_sell = $xltm->user_info['cost_sell']*1000;
	$cost_save = $xltm->user_info['cost_save']*1000;
	//最近5日查询
	$date_list = '<table border="0" width="98%" cellspacing="1" cellpadding="3" align="center" bgcolor="#CCCCCC"><tr>';
	for($i=0;$i<5;$i++)
	{
		$date = date('Y-m-d',time()-$i*3600*24);
		$date_list .='<td align="center" width="20%" height="20" bgcolor="#FAFAFA"><a href="?type=date&date='.$date.'">';
		if($qdate==$date)
		{
			$date_list .= '<b>'.$date.'</b>';
		}
		else
		{
			$date_list .= $date;
		}
		$date_list .='</a></td>';
	}
	$date_list .='</tr></table>';
	
	//帐单信息
	$today_gain = 0;
	$gain = 0;
	$today_trade_money_total = 0;
	$month_trade_money_total = 0;
	$save_money_total = 0;
	$cost_trade_money_total=0;
	$cost_save_money_total = 0;
	$cancash = 0;
	if($row=$xltm->SQL->GetRow("select * from `user_bill` where username='{$xltm->user_info['username']}' and bill_date='{$qdate}'"))
	{
		$today_gain = $row['today_gain'];
		$gain = $row['gain'];
		$today_trade_money_total = $row['today_trade_money_total'];
		$month_trade_money_total = $row['month_trade_money_total'];
		$save_money_total = $row['save_money_total'];
		$cost_trade_money_total=$row['cost_trade_money_total'];
		$cost_save_money_total = $row['cost_save_money_total'];
		$cancash = $row['cancash'];
	}
	$canmoney = $cancash * 10;
	

	//平仓单
	$pc=$xltm->SQL->GetRows("select * from `buy_deal` where sell='1' and user='{$xltm->user_info['username']}' and sell_trust_date='{$qdate}'");

	//留仓单

	$lc=$xltm->SQL->GetRows("select a.id,a.bull_trust_date,a.bull_trust_time,a.stock_code,a.stock_name,a.buy_type,a.bull_price,a.bull_num,a.bull_cost_money,a.sell_cost,a.dc_money,b.save_price,b.cur_price,b.save_day,b.save_money,b.cost_save_money,b.gain from `buy_deal` a left join `deal_save` b on a.id=b.deal_id where a.sell='0' and a.user='{$xltm->user_info['username']}' and b.save_date='{$qdate}' order by a.bull_trust_time desc");
	$xltm->tpls->LoadTemplate("./tmpfiles/report_day.html",false);
	$xltm->tpls->MergeBlock('tr','array',$pc);
	$xltm->tpls->MergeBlock('tr1','array',$lc);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}

function event_sum($BlockName,&$CurrRec,$RecNum){
	$stock_gain = 0;
	$CurrRec['total_cost'] = $CurrRec['bull_cost_money'] + $CurrRec['sell_cost_money'];
}

function E_buy1($BlockName,&$CurrRec,$RecNum){
	global $xltm,$qdate;
	//手续费
	if($CurrRec['bull_trust_date']<$qdate) //当天买入显示手续费
	{
		$CurrRec['bull_cost_money']='0';
	}

	//$CurrRec['save_day']=$xltm->save_day($xltm->sys_date,$CurrRec['bull_trust_date']);
	//if($CurrRec['buy_type']==1)
	//$CurrRec['cost_price']=round((($CurrRec['bull_price']*$CurrRec['bull_num'])+($CurrRec['bull_price']*$CurrRec['bull_num']*$CurrRec['bull_cost']))/$CurrRec['bull_num'],2);
	//else
	//$CurrRec['cost_price']=round((($CurrRec['bull_price']*$CurrRec['bull_num'])-($CurrRec['bull_price']*$CurrRec['bull_num']*$CurrRec['bull_cost']))/$CurrRec['bull_num'],2);
}
?>