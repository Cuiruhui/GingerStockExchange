<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
$type=isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
$end_date = date('Y-m-d',time()-30*24*3600);

/*
还原爆仓
delete
update buy_deal set sell='0',can_sell_num=bull_num,sell_price='0',sell_cost_money='0',sell_money='0',sell_trust_id='0',sell_gain='0',gain='0',agent_sell_money='0',sell_trust_date='',sell_trust_time='', where id in ('00009659','00010146');
*/
if($type=='clear')
{
	$end_date1 = isset($_POST['end_date']) ? $_POST['end_date'] : $end_date;
	$flag_sell = isset($_POST['flag_sell']) ? 1 : 0;
	$flag_deal = isset($_POST['flag_deal']) ? 1 : 0;
	$flag_trust = isset($_POST['flag_trust']) ? 1 : 0;
	$flag_atm = isset($_POST['flag_atm']) ? 1 : 0;
	$flag_payment = isset($_POST['flag_payment']) ? 1 : 0;
	$flag_log = isset($_POST['flag_log']) ? 1 : 0;
	$flag_message = isset($_POST['flag_message']) ? 1 : 0;
	$flag_bill = isset($_POST['flag_bill']) ? 1 : 0;
	$flag_money = isset($_POST['flag_money']) ? 1 : 0;
	if(time()-7*24*3600<strtotime($end_date1))
	{
		echo "<script>alert('历史记录必须保存7天，请选择一个{$end_date}前的日期！');history.go(-1);</script>";
		exit();
	}
	//平仓记录
	if($flag_sell) $xltm->SQL->Execute("delete from `buy_deal` where sell='1' and sell_trust_date<='{$end_date1}'");
	//持仓记录
	if($flag_deal) $xltm->SQL->Execute("delete from `deal_save` where save_date<='{$end_date1}'");
	//委托记录
	if($flag_trust) $xltm->SQL->Execute("delete from `buy_trust` where stock_trust_date<='{$end_date1}'");
	//提款记录
	if($flag_atm) $xltm->SQL->Execute("delete from `atmlog` where add_time<='{$end_date1} 23:59:59'");
	//支付记录
	if($flag_payment) $xltm->SQL->Execute("delete from `payment` where add_time<='{$end_date1} 23:59:59'");
	//浏览记录
	if($flag_log) $xltm->SQL->Execute("delete from `buy_deal` where hit_time<='{$end_date1} 23:59:59'");
	//站内消息
	if($flag_message) $xltm->SQL->Execute("delete from `message` where isread='1' and add_time<='{$end_date1} 23:59:59'");
	//帐单
	if($flag_bill) $xltm->SQL->Execute("delete from `user_bill` where bill_date<='{$end_date1}'");
	//资金明细
	if($flag_money) $xltm->SQL->Execute("delete from `bill_log` where add_date<='{$end_date1}'");
	echo "<script>alert('清除{$end_date1}号之前的数据成功！');history.go(-1);</script>";
	exit();
}
else if($type=='clear')
{
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	if($row=$xltm->SQL->GetRow("select * from `message` where tousername='administrator' and id='{$id}'"))
	{
		//更新阅读状态
		$xltm->SQL->Execute("update `message` set isread='1' where id='{$id}'");
		$xltm->tpls->LoadTemplate("./tmpfiles/message_read.html",false);
		$xltm->tpls->show();
	}
	else
	{
		echo "<script>alert('不存在您要阅读的短信息！');window.parent.ymPrompt.doHandler('error',true);</script>";
		exit();
	}
}
else
{
	$xltm->tpls->LoadTemplate("./tmpfiles/tool.html");
	$xltm->tpls->show();
}
?>