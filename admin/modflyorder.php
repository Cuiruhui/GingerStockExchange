<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
set_time_limit(0);
$err = '';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
$act = $_REQUEST['act'];
//判断是否有权限卖出
if(!$deal = $xltm->SQL->GetRow("select * from buy_deal where sell='0' and id='{$id}'"))
{
	echo "<script type='text/javascript'>alert('该股票已经平仓！'); window.parent.ymPrompt.doHandler('error',true);</script>";
	exit();
}
if($act=='modfly')
{
	
	$bull_trust_time = $_REQUEST['bull_trust_time'];
	$bull_price = $_REQUEST['bull_price'];
	$bull_cost_money = $_REQUEST['bull_cost_money'];
	$dc_money = $_REQUEST['dc_money'];
	$sell_cost = $_REQUEST['sell_cost'];
	
	$bull_money0=$deal['bull_money'];
	
	$bull_money=$deal['bull_num']*100*$bull_price;
	$bull_cost =round($bull_cost_money/$bull_money,3);
	$dc_num=round($dc_money/$bull_money,3);
	
	$lvmoney=round($bull_money0-$bull_money,2);
	
	$SQL_deal=array(
				'bull_trust_time'=>$bull_trust_time, //设置为已经卖出
				'bull_price'=>$bull_price,
				'bull_cost_money'=>$bull_cost_money,
				'dc_money'=>$dc_money,
				'bull_money'=>$bull_money,
				'sell_cost'=>$sell_cost,
				'bull_cost'=>$bull_cost,
				'dc_num'=>$dc_num
				
				);
	
	if($bull_price<=0)
	{
		echo "<script type='text/javascript'>alert('您输入的价格有误！');window.parent.ymPrompt.doHandler('error',true);</script>";
		exit();
	}
	$update_deal=$xltm->array2str($SQL_deal);
	$xltm->SQL->Execute("Update `buy_deal` set {$update_deal} where sell='0' and id='{$id}'");

	//修改委托单
	$xltm->SQL->Execute("Update `buy_trust` set price='{$bull_price}',stock_trust_time='{$bull_trust_time}',stock_deal_time='{$bull_trust_time}',money='{$bull_money}' where  stock_trust_time='{$deal[bull_trust_time]}' and user='{$deal[user]}' and  code='$deal[stock_code]'");
	
	
	//返还用户可用值(以及保证金)	
	$xltm->SQL->Execute("UPDATE `user_users` set money=money+{$lvmoney},cash=cash+{$lvmoney} where username='{$deal['user']}'");
	
	echo "<script type='text/javascript'>alert('成功修改');window.parent.location.reload();</script>";
	exit();
}

//读取当前价格
$cur_price = '0.00';
if($quote = $xltm->Quote($deal['stock_type'].$deal['stock_code']))
{
	$cur_price = $quote[4];
}
$xltm->tpls->LoadTemplate("./tmpfiles/modflyorder.html",false);
$xltm->tpls->Show();
?>