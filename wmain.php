<?php
//ini_set('session.save_path','./temp');
/*
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$mdate=date("D M j G:i:s T Y");
if(isset($_GET['login']) && $_GET['login']=='logout')
{
	$xltm->User->logout_user();
	exit;
}
if($xltm->user_info['username']){
	$time=date("Y-d-m h:i:s",time()+6000);
	$user_name=$xltm->user_info['username'];
	$num_min=$xltm->user_info['num_min']*1;       //最小单手数
	$num_max=$xltm->user_info['num_max']*1;     //最大单手数
	$common=$xltm->SQL->GetRows("SELECT * FROM common where type='1' ORDER BY `add_time` DESC ");
	$xltm->tpls->LoadTemplate("./tmpfiles/index.html");
	$xltm->tpls->MergeBlock('cr','array',$common);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
*/
define('Load_Info', 1);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username'])
{
	$initStockCode = $_GET['do']=='order' && $_GET['stockcode'] ? $_GET['stockcode'] : '600111';
	$isopen = $xltm->startTime();
	$cancash = round($xltm->AvailableCash(),2)* $xltm->config['cost_exchange_rate'];	//李兴修改。2012-4-14
	
	//留仓单
	$lc=$xltm->SQL->GetRows("select * from `buy_deal` where sell='0' and user='{$xltm->user_info['username']}' order by bull_trust_time desc");
	//更新留仓费和减去可用金额
	$lcday="";
	$lcfy="";
	foreach($lc as $liucan)
	{ 
	  $lcday=$xltm->save_day($xltm->sys_date,$liucan['bull_trust_date']); 
	  if($lcday>0)
	  {
	   $lcfy=$lcday*$liucan['save_cost']*$liucan['bull_money'];
	   
	   $xltm->SQL->Execute("UPDATE `buy_deal` set save_day='$lcday',save_money='$lcfy' where sell='0' and user='{$xltm->user_info['username']}'  order by bull_trust_time desc");
	   
	  }
	}
	
	
	
	
	$get_code="";
	foreach($lc as $k)
	{
		$get_code .=$k['stock_type'].$k['stock_code'].",";
	}
	$get_code=substr($get_code,0,-1);
	setcookie("deal_list", $get_code,time()+3600); //写入cookie以供前台调用即时数据
	
	
	$xltm->tpls->LoadTemplate("./tmpfiles/wmain.html");
	$xltm->tpls->MergeBlock('tr1','array',$lc);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
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
