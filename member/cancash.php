<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$usercancash = 0;
	//用户持仓
	$total_bull_money = 0;
	$total_bull_money_cash = 0;
	if($row = $xltm->SQL->GetRow("Select sum(bull_money) as tt from `buy_deal` where sell='0' and user='{$xltm->user_info['username']}'"))
	{
		$total_bull_money = round($row['tt'] ,2);
		$total_bull_money_cash = round($total_bull_money * 0.1,2);
	}
	//用户委托
	$total_trust_money = 0;
	$total_trust_money_cash = 0;
	if($row=$xltm->SQL->GetRow("select sum(money) as tru from `buy_trust` where app='1' and trust_type='1' and user='{$xltm->user_info['username']}'"))
	{
		$total_trust_money = round($row['tru'],2);
		$total_trust_money_cash = round($total_trust_money*0.1,2);
	}
	$usercancash = round($xltm->user_info['cash'],2) - $total_bull_money_cash - $total_trust_money_cash;
	$xltm->tpls->LoadTemplate("./tmpfiles/cancash.html",false);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}

?>