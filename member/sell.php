<?php
define('Load_Info', true);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

$err = '';
$username = $xltm->user_info['username'];
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
$act = $_REQUEST['act'];
//判断是否有权限卖出
if(!$deal = $xltm->SQL->GetRow("select * from buy_deal where sell='0' and id='{$id}' and user='{$username}'"))
{
	echo "<script type='text/javascript'>alert('您无权操作该持仓记录！'); window.parent.ymPrompt.doHandler('error',true);</script>";
	exit();
}
if($act=='sell')
{
	$sell_num = is_numeric($_REQUEST['num'])?intval($_REQUEST['num']):0;
	$sell_price = isset($_REQUEST['sell_price']) ? round($_REQUEST['sell_price'],2) : 0;
	$price_type = isset($_REQUEST['price_type']) ? intval($_REQUEST['price_type']) : 1; //默认市价卖出
	$st = strpos(strtolower($deal['stock_name']),'st')===false ? 0 : 1;
	if($sell_num==0 && $sell_num>$deal['bull_num'])
	{
		echo "<script type='text/javascript'>alert('卖出股票数量有误！'); history.go(-1);</script>";
		exit();
	}
	if($price_type==2)
	{
		if($sell_price==0)
		{
			echo "<script type='text/javascript'>alert('请输入卖出委托价！'); history.go(-1);</script>";
			exit();
		}
		//判断输入的委托价是否合理
		$cj = round($deal['bull_price'] * ($st==1 ? 0.05 : 0.1),2);
		$lower = $deal['bull_price'] - $cj;
		$upper = $deal['bull_price'] + $cj;
		if($sell_price<$lower || $sell_price>$upper)
		{
			echo "<script type='text/javascript'>alert('你输入的委托价有误！最高:{$upper}元，最低:{$lower}元\\n\\n普通股涨跌幅10%，ST股涨跌幅5%！！！！'); history.go(-1);</script>";
			exit();
		}
	}
	
	//卖出股票
	$result = $xltm->buy->sale_stock_part($id,$sell_num,$price_type,$sell_price);
	if(strpos($result,'交易成功')===false)
	{
		$msg = $result;
	}
	else
	{
		if($price_type==1){
			$sell_price = $xltm->config['ttt_sell_price'] ;	//2012-4-12李兴 添加。市价卖出时，可能还计算了滑价，要更新价格。
		}
		$msg = "成功平仓{$sell_num}手股票({$deal['stock_name']}:{$deal['stock_code']})，平仓价格：{$sell_price}元！！";
	}
	//echo $msg;
	echo "<script type='text/javascript'>alert('{$msg}');window.parent.ymPrompt.doHandler('error',true);</script>";
	exit();
}
$note = '';
$bull_time = $deal['bull_trust_time'];
if(date('Y-m-d H:i:s',time()-$xltm->config['sel_max_time']*60)<$bull_time)
{
	$note = '<tr bgcolor="#FFFFFF"><td colspan="2"><font color="red">系统提示：该股票买入时间不足'.$xltm->config['sel_max_time'].'分钟，卖出将加收'.($xltm->config['cost_sell_limit']*100).'%的手续费！！</font> </td></tr>';
}
//读取当前价格
$cur_price = '0.00';
if($quote = $xltm->Quote($deal['stock_type'].$deal['stock_code']))
{
	$cur_price = ($deal['buy_type']=='1') ? $quote[8] : $quote[7];
}

$xltm->tpls->LoadTemplate("./tmpfiles/sell.html",false);
$xltm->tpls->Show();
?>
