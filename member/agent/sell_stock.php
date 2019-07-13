<?php
include_once('global.php');
$xltm->user_log(1,"平仓股票");
$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
if($id==0) exit();
if($_REQUEST['act']=='sell')
{
	exit("<script>alert('无平仓权限！');history.go(-1);</script>");
	$sell_price = $_REQUEST['sell_price'];
	$sell_time = $_REQUEST['sell_time'];
	
	if($sell_price=='')
	{
		exit("<script>alert('请正确输入平仓价格！');history.go(-1);</script>");
	}
	if($sell_time=="")
	{
		$sell_time = $xltm->sys_time;
	}
	$result = $xltm->auto_sell($id,$sell_price,$sell_time);
	$results = explode('|',$result);
	if($results[0]=='true')
	{
		exit("<script>alert('{$results[1]}');window.parent.ymPrompt.doHandler('error',true);</script>");
	}
	else
	{
		exit("<script>alert('{$results[1]}');history.go(-1);</script>");
	}
}
else
{
	if($row=$xltm->SQL->GetRow("select * from `buy_deal` where id='{$id}' and sell='0' and agent='{$xltm->user_info['username']}'"))
	{
		//获取当前价格
		$cur_price = '0.00';
		if($quote = $xltm->Quote($row['stock_type'].$row['stock_code']))
		{
			$cur_price = ($row['buy_type']=='1') ? $quote[8] : $quote[7];
		}
		$xltm->tpls->LoadTemplate("./tmpfiles/sell_stock.html",false);
		$xltm->tpls->Show();
	}
	else
	{
		exit("<script>alert('不存在该记录或该持仓记录已经平仓！');window.parent.ymPrompt.doHandler('error',true);</script>");
	}
}
?>