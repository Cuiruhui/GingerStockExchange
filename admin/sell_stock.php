<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");

$err = '';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
$act = $_REQUEST['act'];
$deal = $xltm->SQL->GetRow("select * from buy_deal where sell='0' and id='{$id}'");
if($act=='sell')
{
	$sell_price = $_REQUEST['sell_price'];
	$sell_time = $_REQUEST['sell_time'];
	if($sell_price<=0)
	{
		echo "<script type='text/javascript'>alert('您输入的卖出价格有误！');window.parent.ymPrompt.doHandler('error',true);</script>";
		exit();
	}
	$msg = $xltm->stock_sell($id,$sell_price,$sell_time);
	echo "<script type='text/javascript'>alert('{$msg}');window.parent.location.reload();</script>";
	exit();
}

if($act=='xiugai')
{
	$xltm->tpls->LoadTemplate("./tmpfiles/xiugaideal.html",false);
	$xltm->tpls->Show();
	exit();
}


if($act=='xiugai2')
{
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	$sell_price = $_REQUEST['sell_price'];
	$sell_time = $_REQUEST['sell_time'];
	$sell_date = $_REQUEST['sell_date'];
	$sell_num = $_REQUEST['sell_num'];
	$dc_money = $_REQUEST['dc_money'];
	$sell_money =$sell_price*$sell_num*100;
	if($sell_price<=0)
	{
		echo "<script type='text/javascript'>alert('您输入的卖出价格有误！');window.parent.ymPrompt.doHandler('error',true);</script>";
		exit();
	}
	$xltm->SQL->Execute("UPDATE buy_deal set bull_price='{$sell_price}',bull_money='{$sell_money}',bull_trust_time='{$sell_time}',bull_trust_date='{$sell_date}',dc_money='{$dc_money}' where id='{$id}'");
	
	$deals = $xltm->SQL->GetRow("select * from buy_deal where sell='0' and id='{$id}'");
	
	if($dc_money>=$deal['dc_money'])
	{
		$chajia=$dc_money-$deal['dc_money'];
		$chajiabeishu=$xltm->config['cost_exchange_rate'] *$chajia;
		$xltm->SQL->Execute("UPDATE `user_users` set money=money-{$chajiabeishu},cash=cash-{$chajia} where username='{$deals['user']}'");
	}else{
		$chajia=$deal['dc_money']-$dc_money;
		$chajiabeishu=$xltm->config['cost_exchange_rate'] *$chajia;
		$xltm->SQL->Execute("UPDATE `user_users` set money=money+{$chajiabeishu},cash=cash+{$chajia} where username='{$deals['user']}'");
	}
	
	
	
	echo "<script type='text/javascript'>alert('修改订单成功');window.parent.location.reload();</script>";
	exit();
}

//读取当前价格
$cur_price = '0.00';
if($quote = $xltm->Quote($deal['stock_type'].$deal['stock_code']))
{
	$cur_price = $quote[4];
}
$xltm->tpls->LoadTemplate("./tmpfiles/sell_stock.html",false);
$xltm->tpls->Show();
?>