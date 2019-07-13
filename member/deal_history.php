<?
define('Load_Info', 1);
session_start();
setcookie("stock_list", '');
setcookie("buy1", '');
setcookie("buy2", '');
setcookie("buy3", '');
setcookie("buy4", '');
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$type=$_REQUEST['type'];
switch ($type)
{
	case 'view':
		$viewDate=$_GET['viewDate'];
		$start_date=($_GET['start_date']);
		$end_date=$_GET['end_date'];
		$sell=$xltm->SQL->GetRows("select * from buy_deal where user='{$xltm->user_info['username']}' and sell_trust_date='$viewDate' and sell='1' ORDER BY `sell_trust_time` DESC");
		$xltm->tpls->LoadTemplate("./tmpfiles/deal_history_view.html");
		$xltm->tpls->MergeBlock('tr','array',$sell);
		$xltm->tpls->Show();
		break;
	default:
		$week1=date('w');
		$defDate=date('Y-m-d',time()-(6*24*60*60));
		$start_date=($_GET['start_date'])?$_GET['start_date']:$defDate;
		$end_date=$_GET['end_date']?$_GET['end_date']:$xltm->sys_date;
		$dateArr=array($start_date,$end_date);
		$s_date=min($dateArr);
		$e_date=max($dateArr);
		$day=$xltm->save_day($s_date,$e_date);
		//print $day;
		$dateList=array();
		$week=array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
		for ($i=$day;$i<1;$i++)
		{
			$new_date=date('Y-m-d',strtotime($e_date)+($i*(24*60*60)));
			$dateList[$i]['week']=$week[date('w',strtotime($e_date)+($i*(24*60*60)))];
			$sell=$xltm->SQL->GetRow("select count(*) as sell_count,sum(sell_money) as sell_money,sum(sell_gain) as sell_gain,sum(gain) as gain,sum(bull_cost_money) as bull_cost_money,sum(sell_cost_money) as sell_cost_money,sum(dc_money) as dc_money,sum(save_money) as save_money from buy_deal where user='{$xltm->user_info['username']}' and sell_trust_date='$new_date' and sell='1'");
			$dateList[$i]['sell_count']=($sell['sell_count'])?$sell['sell_count']:0;
			$dateList[$i]['sell_money']=($sell['sell_money'])?$sell['sell_money']:'0.00';
			$dateList[$i]['sell_gain']=($sell['sell_gain'])?$sell['sell_gain']:'0.00';
			$dateList[$i]['totla_gain']=$sell['gain']-$sell['bull_cost_money']-$sell['sell_cost_money']-$sell['dc_money']-$sell['save_money'];
			//print_r($sell);
			$dateList[$i]['date']=$new_date;
			$dateList[$i]['view']=($sell['sell_count']>0)?1:'';
		}
		$xltm->tpls->LoadTemplate("./tmpfiles/deal_history.html");
		$xltm->tpls->MergeBlock('tr','array',$dateList);
		$xltm->tpls->Show();
		break;
}
function view_ex($BlockName,&$CurrRec,$RecNum){
	global $xltm;
	$CurrRec['cost_totla']=$CurrRec['bull_cost_money']+$CurrRec['sell_cost_money'];
	$CurrRec['member_yk']=$CurrRec['gain']-$CurrRec['bull_cost_money']-$CurrRec['sell_cost_money']-$CurrRec['dc_money']-$CurrRec['save_money'];
	$CurrRec['save_day']=$xltm->save_day($CurrRec['sell_trust_date'],$CurrRec['bull_trust_date']);
}
?>