<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");

$err = '';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
$act = $_REQUEST['act'];
//判断是否有权限卖出
if(!$deal = $xltm->SQL->GetRow("select * from buy_deal where sell='0' and id='{$id}'"))
{
	echo "<script type='text/javascript'>alert('该股票已经平仓！'); window.parent.ymPrompt.doHandler('error',true);</script>";
	exit();
}
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

//读取当前价格
$cur_price = '0.00';
if($quote = $xltm->Quote($deal['stock_type'].$deal['stock_code']))
{
	$cur_price = $quote[4];
}
$xltm->tpls->LoadTemplate("./tmpfiles/sell_stock.html",false);
$xltm->tpls->Show();
?>
