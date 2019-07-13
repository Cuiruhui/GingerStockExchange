<?php
header('P3P: CP="ALL ADM DEV PSAi COM OUR OTRo STP IND ONL"');
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$xltm->user_log(9,"浏览持股/卖出");
	$usercancash = $xltm->AvailableCash();
	$canmoney = round($xltm->AvailableCash(),2)* $xltm->config['cost_exchange_rate'];
	$isopen = $xltm->startTime()==1 ? true : false;
	//读取手续费率
	$cost_bull = $xltm->user_info['cost_bull']*1000;
	$cost_sell = $xltm->user_info['cost_sell']*1000;
	$cost_save = $xltm->user_info['cost_save']*1000;
	//本日交易量
	$today_trade_money = 0;
	if($row = $xltm->SQL->GetRow("select sum(money) as totalmoney from `buy_trust` where user='{$xltm->user_info['username']}' and app='2' and stock_trust_date='{$xltm->sys_date}'"))
	{
		if(is_numeric($row['totalmoney']) && $row['totalmoney']>0)
		{
			$today_trade_money = $row['totalmoney'];
		}
	}
	//本月交易量
	$month_trade_money = 0;
	$start_date = date('Y-m-1',time());
	$end_date = $xltm->sys_date;
	if($row = $xltm->SQL->GetRow("select sum(money) as totalmoney from `buy_trust` where user='{$xltm->user_info['username']}' and app='2' and (stock_trust_date>='{$start_date}' and stock_trust_date<='{$end_date}')"))
	{
		if(is_numeric($row['totalmoney']) && $row['totalmoney']>0)
		{
			$month_trade_money = $row['totalmoney'];
		}
	}

	//平仓单
	$pc=$xltm->SQL->GetRows("select * from `buy_deal` where sell='1' and user='{$xltm->user_info['username']}' and sell_trust_date='{$xltm->sys_date}'");

	//留仓单
	$lc=$xltm->SQL->GetRows("select * from `buy_deal` where sell='0' and user='{$xltm->user_info['username']}' order by bull_trust_time desc");
	$get_code="";
	foreach($lc as $k)
	{
		$get_code .=$k['stock_type'].$k['stock_code'].",";
	}
	$get_code=substr($get_code,0,-1);
	setcookie("deal_list", $get_code,time()+3600); //写入cookie以供前台调用即时数据
	$xltm->tpls->LoadTemplate("./wap/wapdeal.html");
	$xltm->tpls->MergeBlock('tr','array',$pc);
	$xltm->tpls->MergeBlock('tr1','array',$lc);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','waplogin.php');
}

function event_sum($BlockName,&$CurrRec,$RecNum){
	/*
	$stock_gain = 0;
	if($CurrRec['bull_type']==1) //买升
	{
		$stock_gain=$CurrRec['sell_money']-$CurrRec['bull_money']-$CurrRec['sell_cost_money']-$CurrRec['bull_cost_money']-$CurrRec['save_money']-$CurrRec['dc_money'];
	}
	else
	{
		$stock_gain=$CurrRec['bull_money']-$CurrRec['sell_money']-$CurrRec['sell_cost_money']-$CurrRec['bull_cost_money']-$CurrRec['save_money']-$CurrRec['dc_money'];
	}
	$CurrRec['yk'] = $stock_gain;
	*/
	$CurrRec['total_cost'] = $CurrRec['bull_cost_money'] + $CurrRec['sell_cost_money'];
}

function E_buy1($BlockName,&$CurrRec,$RecNum){
	global $xltm,$isopen;
	//$CurrRec['save_day']=$xltm->save_day($xltm->sys_date,$CurrRec['bull_trust_date']);
	if($CurrRec['can_sell_num']!=$CurrRec['bull_num'] || !$isopen)
	{
		$CurrRec['candeal']=' disabled';
	}
	else
	{
		$CurrRec['candeal']='';
	}
	if($CurrRec['buy_type']==1)
		$CurrRec['cost_price']=round((($CurrRec['bull_price']*$CurrRec['bull_num'])+($CurrRec['bull_price']*$CurrRec['bull_num']*$CurrRec['bull_cost']))/$CurrRec['bull_num'],2);
	else
		$CurrRec['cost_price']=round((($CurrRec['bull_price']*$CurrRec['bull_num'])-($CurrRec['bull_price']*$CurrRec['bull_num']*$CurrRec['bull_cost']))/$CurrRec['bull_num'],2);
}
?>