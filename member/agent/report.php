<?php
include_once('global.php');
setcookie("bull_list",'');
$type=$_GET['type'];
$agent=$_GET['agent'];
$member=$_GET['member'];
$start_date=($_GET['start_date'])?$_GET['start_date']:$xltm->sys_date;
$start_date=$xltm->dateFrom($start_date);
$end_date=($_GET['end_date'])?$_GET['end_date']:$xltm->sys_date;
$end_date=$xltm->dateFrom($end_date);
$sell=$_GET['sell'];
$from_date=($sell==1)?'sell_trust_date':'bull_trust_date';
$dateArr=array($start_date,$end_date);
$s_date=min($dateArr);
$e_date=max($dateArr);
$sum="sum(a.gain) as gain,sum(a.bull_cost_money) as bull_cost_money,sum(a.sell_cost_money) as sell_cost_money,sum(a.dc_money) as dc_money,sum(a.save_money) as save_money";
$agentsum="sum(a.agent_bull_money) as agent_bull_money,sum(a.agent_sell_money) as agent_sell_money,sum(a.agent_save_money) as agent_save_money";

switch ($type)
{
	case 'get_stock':
		include_once("../Lib/curl_http.php");
		$bull=$xltm->SQL->GetRows("SELECT id,stock_code,stock_type FROM buy_deal where user='$member' and $from_date>='$s_date' and $from_date<='$e_date' and sell='$sell'");
		$list='';
		foreach ($bull as $tr)
		{
			$list .=$tr['stock_type'].$tr['stock_code'].',';
		}
		$list=substr($list,0,-1);
		$curl=&new Curl_HTTP_Client();
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$list,"",10);
		$html_data=iconv("GB2312","UTF-8",$html_data);
		preg_match_all("/str_(.+?)\";/is",$html_data,$data);
		$messages=str_replace('="',",",$data[1]);
		$list="0,0|*|";
		$iv=0;
		foreach($messages as $l)
		{
			$N=split(',',$l,12);
			$N[0]=substr($N[0],2);
			$temp=$bull[$iv]['id'].",";
			for($i=0;$i<8;$i++)
			{
				$temp .=$N[$i].",";
			}
			$list .=substr($temp,0,-1)."|*|";
			$iv++;
		}
		echo $list;
		break;
	case 'all':
		$xltm->user_log(1,"查询报表[代理商]:{$s_date}-{$e_date}");
		$list = array();
		$demos = '';
		$inquery = '';
		$i=0;
		$sell = isset($_REQUEST['sell']) ? $_REQUEST['sell'] : -1;
		$demo = isset($_REQUEST['demo']) ? $_REQUEST['demo'] : '-1';
		if($sell>-1) $inquery .= " and a.sell='$sell'";
		if($demo!='-1') $inquery .= " and b.demo='$demo'";
		if($demo =='0')
		{
			$demos = '正式帐号';
		}
		else if ($demo == '1')
		{
			$demos = '试玩帐号';
		}
		else
		{
			$demos = '全部';
		}
		$count = 0;
		$bull_money = 0;
		$sell_money = 0;
		$totalcost = 0;
		$save_money = 0;
		$totalgain = 0;
		$totalagentcost = 0;
		$from="select count(*) as count,sum(a.bull_money) as bull_money,sum(a.sell_money) as sell_money,$sum,$agentsum from buy_deal a left join user_users b on a.user=b.username where a.agent='{$MyUser}' $inquery and a.$from_date>='$s_date' and a.$from_date<='$e_date'";
		//exit($from);
		$row=$xltm->SQL->GetRow($from);
		if($row['count']>0){
			$count = $row['count'];
			$bull_money = $row['bull_money'];
			$sell_money = $row['sell_money'];
			$totalcost = $row['bull_cost_money']+$row['sell_cost_money']+$row['save_money'] + $row['dc_money'];
			$totalagentcost = $row['agent_bull_money']+$row['agent_sell_money']+$row['agent_save_money'];
			$totalgain = $row['gain'] - $totalcost;
		}
		$xltm->tpls->LoadTemplate("./tmpfiles/report_all.html");
		$xltm->tpls->MergeBlock('tr','array',$row);
		$xltm->tpls->show();
		break;
	case 'member':
		$xltm->user_log(1,"查询报表[会员]:{$s_date}-{$e_date}");
		$Rows=$xltm->SQL->GetRows("SELECT username FROM user_users where active='yes' and l3_user='$login3'");
		$list=array();
		$i=0;
		foreach ($Rows as $gin)
		{
			$from="select count(*) as count,sum(bull_money) as bull_money,sum(sell_money) as sell_money,$sum,$sum3,$sum2,$sum1 from buy_deal where agent='{$user_info['username']}' and user='{$gin['username']}' and $from_date>='$s_date' and $from_date<='$e_date' and sell='$sell'";
			$port=$xltm->SQL->GetRow($from);
			if($port['count']>0){
				$port['t_cost']=$port['bull_cost_money']+$port['sell_cost_money'];
				//转换输赢正负数
				$port['agent_gain']=($port['agent_gain']>0)?0-$port['agent_gain']:abs($port['agent_gain']);
				$port['L2_gain']=($port['L2_gain']>0)?0-$port['L2_gain']:abs($port['L2_gain']);
				$port['L3_gain']=($port['L3_gain']>0)?0-$port['L3_gain']:abs($port['L3_gain']);

				$port['L3_yk']=$port['L3_gain']+$port['L3_bull_money']+$port['L3_sell_money']+$port['L3_save_money'];
				$port['L2_yk']=$port['L2_gain']+$port['L2_bull_money']+$port['L2_sell_money']+$port['L2_save_money'];
				$port['agent_yk']=$port['agent_gain']+$port['agent_bull_money']+$port['agent_sell_money']+$port['dc_money']+$port['agent_save_money'];

				$port['t_gain']=$port['gain']-$port['bull_cost_money']-$port['sell_cost_money']-$port['dc_money']-$port['save_money'];
				$port['up']=$port['t_gain'];
				$port['up']=($port['up']>0)?0-$port['up']:abs($port['up']);
				$port['sj']=($port['up']>0)?'交予:':'收取:';
				$list[$i]=array_merge($gin, $port);
				$i++;
			}
		}
		$xltm->tpls->LoadTemplate("./tmpfiles/report_member.html");
		$xltm->tpls->MergeBlock('tr','array',$list);
		$xltm->tpls->show();
		break;
	case 'view':
		$from="select * from buy_deal where user='$member' and $from_date>='$s_date' and $from_date<='$e_date' and sell='$sell'";
		$port=$xltm->SQL->GetRows($from);
		$xltm->tpls->LoadTemplate("./tmpfiles/report_view.html");
		$xltm->tpls->MergeBlock('tr','array',$port);
		$xltm->tpls->show();
		break;
	default:
		$week1=date('w');
		$dateList=array();
		$week=array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');

		$xltm->tpls->LoadTemplate("./tmpfiles/report.html");
		$xltm->tpls->Show();
		break;
}
function view_event($BlockName,&$CurrRec,$RecNum){
	global $xltm;
	$CurrRec['cost']=$CurrRec['bull_cost_money']+$CurrRec['sell_cost_money']+$CurrRec['save_money'];
	$CurrRec['member_yk']=$CurrRec['gain']-$CurrRec['bull_cost_money']-$CurrRec['sell_cost_money']-$CurrRec['dc_money']-$CurrRec['save_money'];

	$CurrRec['L3_gain']=($CurrRec['L3_gain']>0)?0-$CurrRec['L3_gain']:-$CurrRec['L3_gain'];
	$CurrRec['L2_gain']=($CurrRec['L2_gain']>0)?0-$CurrRec['L2_gain']:-$CurrRec['L2_gain'];
	$CurrRec['agent_gain']=($CurrRec['agent_gain']>0)?0-$CurrRec['agent_gain']:-$CurrRec['agent_gain'];

	$CurrRec['L3_yk']=$CurrRec['L3_gain']+$CurrRec['L3_bull_money']+$CurrRec['L3_sell_money']+$CurrRec['L3_save_money'];
	$CurrRec['L2_yk']=$CurrRec['L2_gain']+$CurrRec['L2_bull_money']+$CurrRec['L2_sell_money']+$CurrRec['L2_save_money'];
	$CurrRec['agent_yk']=$CurrRec['agent_gain']+$CurrRec['agent_bull_money']+$CurrRec['agent_sell_money']+$CurrRec['dc_money']+$CurrRec['agent_save_money'];
}
?>
