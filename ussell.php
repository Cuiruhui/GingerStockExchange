<?php
define('Load_Info', true);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");
require_once(dirname(__FILE__)."/QueryList.class.php");


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
	
	if($sell_num==0 && $sell_num>$deal['bull_num'])
	{
		echo "<script type='text/javascript'>alert('卖出股票数量有误！'); history.go(-1);</script>";
		exit();
	}
	
	/*$Date_1 = date("Y-m-d");
    $Date_2 = $deal['bull_trust_date'];
    $d1 = strtotime($Date_1);
    $d2 = strtotime($Date_2);
    $Days = round(($d1-$d2)/3600/24);
	
	if($Days <1)
	{
		echo "<script type='text/javascript'>alert('当天不能卖，必须第二个交易日才能卖出！'); history.go(-1);</script>";
		exit();
	}
	*/
	if($price_type==2)
	{
		
			exit();
		
	}
	
	//卖出股票
	$result = $xltm->ussale_part($id,$sell_num,$price_type,$sell_price);
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
if(substr( $deal['stock_code'], 0, 1 ) =='0' || substr( $deal['stock_code'], 0, 1 ) =='3' || substr( $deal['stock_code'], 0, 1 ) =='6' )
{	
	if($quote = $xltm->Quote($deal['stock_type'].$deal['stock_code']))
	{
	$cur_price = ($deal['buy_type']=='1') ? $quote[8] : $quote[7];
	}
}else{
	//获取当前价
			$code =strtoupper($deal['stock_code']);
			$stockarea =$deal['stock_type'];
			$url = "http://gu.qq.com/us".$code.".".$stockarea."/gg";
	
			$reg = array("xianjia"=>array(".item-1 span:eq(0)","text"));
			$qy = new QueryList($url,$reg);
			$arr = $qy->jsonArr;
			$cur_price = floatval($arr[0][xianjia]);
}
$xltm->tpls->LoadTemplate("./tmpfiles/ussell.html",false);
$xltm->tpls->Show();


?>
