<?php
define('Load_Info', true);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$type=$_GET['type'];
setcookie("buy1", '');
setcookie("buy2", '');
setcookie("buy4", '');
switch ($type)
{
	case 'buy0':
		//委托交易
		//print_r($_COOKIE);
		$xltm->user_log(9,"浏览首页最近委托交易");
		$buylist=$xltm->SQL->GetRows("SELECT * FROM buy_trust WHERE user='{$xltm->user_info['username']}' and app=1 ORDER BY `stock_trust_time` DESC LIMIT 0,7");
		break;
	case 'buy1':
		//未平仓
		$xltm->user_log(9,"浏览首页最近未平仓交易");
		$buylist=$xltm->SQL->GetRows("SELECT * FROM buy_deal WHERE user='{$xltm->user_info['username']}' and sell=0 ORDER BY `bull_trust_time` DESC LIMIT 0,7");
		$get_code="";
		foreach ($buylist as $k)
		{
			//$buylist[$k['cost']]=123456;
			$get_code .=$k['stock_type'].$k['stock_code'].",";
		}
		$get_code=substr($get_code,0,-1);
		setcookie("buy1", $get_code,time()+3600);
		break;
	case 'buy2':
		//已平仓
		$xltm->user_log(9,"浏览首页最近已平仓交易");
		$buylist=$xltm->SQL->GetRows("SELECT * FROM buy_deal WHERE user='{$xltm->user_info['username']}' and sell=1 ORDER BY `sell_trust_time` DESC LIMIT 0,7");
		break;
	case 'buy3':
		//留仓
		$xltm->user_log(9,"浏览首页最近已平仓交易");
		$buylist=$xltm->SQL->GetRows("SELECT * FROM buy_deal WHERE user='{$xltm->user_info['username']}' and sell=0 and bull_trust_date<'{$xltm->sys_date}' ORDER BY `bull_trust_time` DESC LIMIT 0,7");
		$get_code="";
		foreach ($buylist as $k)
		{
			//$buylist[$k['cost']]=123456;
			$get_code .=$k['stock_type'].$k['stock_code'].",";
		}
		$get_code=substr($get_code,0,-1);
		setcookie("buy2", $get_code,time()+3600);
		break;
	case 'buy4':
		setcookie("stock_list", '');
		setcookie("buy1", '');
		setcookie("buy3", '');
		$bull=$xltm->SQL->GetRows("SELECT * FROM buy_deal where user='{$xltm->user_info['username']}' and sell='0'  ORDER BY `bull_trust_time` DESC ");
		$xltm->tpls->LoadTemplate("./tmpfiles/deal_record.html");
		$get_code="";
		foreach ($bull as $k)
		{
			$get_code .=$k['stock_type'].$k['stock_code'].",";
		}
		$get_code=substr($get_code,0,-1);
		setcookie("buy4", $get_code,time()+3600);
		$Tr_Count=$xltm->tpls->MergeBlock('tr','array',$bull);
		$xltm->tpls->Show();
		break;
	case 'cancel_buy':
		$id=$_GET['id'];
		if($buyRow=$xltm->SQL->GetRow("SELECT * FROM `buy_trust` WHERE user='{$xltm->user_info['username']}' and id='{$id}' and app='1'"))
		{
			$xltm->SQL->Execute("UPDATE `buy_trust` set app='3' where id='{$buyRow['id']}'");
			$xltm->SQL->Execute("UPDATE `user_users` set money=money+'{$buyRow['money']}' where username='{$buyRow['user']}'");
			exit("true|撤单成功！");
		}else {
			exit("false|该委托交易已经撤销了！");
		}
		break;
	case 'sale_buy':
		//用户平仓或卖出股票
		$id=$_GET['id'];
		if($buyRow=$xltm->SQL->GetRow("SELECT * FROM buy_deal WHERE user='{$xltm->user_info['username']}' and id='{$id}' and app='1'"))
		{
			$xltm->SQL->Execute("UPDATE buy_deal set app='2' where id='{$id}'");
			if($_COOKIE['buy4'])
			$xltm->ShowPrompt('平仓成功3!!',"$('#buytabs .selected').removeClass('selected');buylist('buy4');$('#buytabs div:nth-child(2)').addClass('selected');");
			else 
			$xltm->ShowPrompt('平仓成功4!!',"$('#buytabs .selected').removeClass('selected');buylist('buy1');$('#buytabs div:nth-child(2)').addClass('selected');");
		}else {
			$xltm->ShowPrompt('该委托交易已经平仓了!!',"$('#buytabs .selected').removeClass('selected');buylist('buy1');$('#buytabs div:nth-child(2)').addClass('selected');");
		}
		//print $id;
		exit;
		break;
}
$xltm->tpls->LoadTemplate("./tmpfiles/".$type.".html");
$xltm->tpls->MergeBlock('bl','array',$buylist);
$xltm->tpls->Show();

function E_buy3($BlockName,&$CurrRec,$RecNum){
	global $xltm;
	$CurrRec['save_day']=$xltm->save_day($xltm->sys_date,$CurrRec['bull_trust_date']);
}
function E_buy1($BlockName,&$CurrRec,$RecNum){
	global $xltm;
	$CurrRec['save_day']=$xltm->save_day($xltm->sys_date,$CurrRec['bull_trust_date']);
	if($CurrRec['buy_type']==1)
	$CurrRec['cost_price']=round((($CurrRec['bull_price']*$CurrRec['bull_num'])+($CurrRec['bull_price']*$CurrRec['bull_num']*$CurrRec['bull_cost']))/$CurrRec['bull_num'],2);
	else
	$CurrRec['cost_price']=round((($CurrRec['bull_price']*$CurrRec['bull_num'])-($CurrRec['bull_price']*$CurrRec['bull_num']*$CurrRec['bull_cost']))/$CurrRec['bull_num'],2);
}
function E_buy2($BlockName,&$CurrRec,$RecNum){
	global $xltm;
	$CurrRec['cost_totla']=$CurrRec['bull_cost_money']+$CurrRec['sell_cost_money']+$CurrRec['save_money'];
	$CurrRec['save_day']=$xltm->save_day($CurrRec['sell_trust_date'],$CurrRec['bull_trust_date']);
}
function E_buy4($BlockName,&$CurrRec,$RecNum){
	//global $xltm;
	//$CurrRec['save_day']=$xltm->save_day($xltm->sys_date,$CurrRec['bull_trust_date']);
}
?>