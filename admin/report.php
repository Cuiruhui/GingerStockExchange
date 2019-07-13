<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();

include_once("./Lib/xltm.class.php");
$type=$_REQUEST['type'];
$agent=$_GET['agent'];
$member=$_GET['member'];
$start_date=$_GET['start_date'];
$end_date=$_GET['end_date'];
$sell=isset($_GET['sell'])?$_GET['sell']:-1;
$demo = isset($_GET['demo']) ? intval($_GET['demo']) : -1;
if($demo !=-1) $dquery = " and b.demo='$demo'";
$demos = '';
if($demo == 0)
{
	$demos = '正式';
}
else if($demo == 1)
{
	$demos = '试玩';
}
else
{
	$demos = '全部';
}
$from_date=($sell==1)?'sell_trust_date':'bull_trust_date';
$start_date=$xltm->dateFrom($start_date);
$end_date=$xltm->dateFrom($end_date);
$dateArr=array($start_date,$end_date);
$s_date=min($dateArr);
$e_date=max($dateArr);
$sum="sum(a.gain) as gain,sum(a.bull_cost_money) as bull_cost_money,sum(a.sell_cost_money) as sell_cost_money,sum(a.dc_money) as dc_money,sum(a.save_money) as save_money";
$sumagent="sum(a.agent_bull_money) as agent_bull_money,sum(a.agent_sell_money) as agent_sell_money,sum(a.agent_save_money) as agent_save_money";

switch ($type)
{
	case 'Get_bull':
		include_once("./Lib/curl_http.php");
		$from="select * from buy_deal where user='$member' and $from_date>='$s_date' and $from_date<='$e_date' and sell='$sell'";
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
	case 'agent':
		$Rows=$xltm->SQL->GetRows("SELECT username FROM user_agent where active='yes'");
		$list=array();
		$i=0;
		foreach ($Rows as $gin)
		{
			$from="select count(*) as count,sum(a.bull_money) as bull_money,sum(a.sell_money) as sell_money,$sum,$sumagent from buy_deal a left join user_users b on a.user=b.username where a.agent='{$gin['username']}' $dquery and a.$from_date>='$s_date' and a.$from_date<='$e_date'";
			if($sell>-1)
			{
			 	$from .=" and a.sell='$sell'";
			}
			//exit($from);
			$port=$xltm->SQL->GetRow($from);
			if($port['count']>0){
				$port['t_cost']=$port['agent_bull_money']+$port['agent_sell_money'];
				
				$port['t_gain']=$port['agent_bull_money']+$port['agent_sell_money']+$port['dc_money']+$port['agent_save_money'];
				$list[$i]=array_merge($gin, $port);
				$i++;
			}
		}
		$xltm->tpls->LoadTemplate("./tmpfiles/report_agent.html");
		$xltm->tpls->MergeBlock('tr','array',$list);
		$xltm->tpls->show();
		break;
	case 'member':
		$Rows=$xltm->SQL->GetRows("SELECT username FROM user_users where active='yes' and l3_user='$login3'");
		$list=array();
		$i=0;
		foreach ($Rows as $gin)
		{
			$from="select count(*) as count,sum(bull_money) as bull_money,sum(sell_money) as sell_money,$sum,$sum3,$sum2,$sum1 from buy_deal where user='{$gin['username']}' and $from_date>='$s_date' and $from_date<='$e_date' and sell='$sell'";
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
		$xltm->tpls->LoadTemplate("./tmpfiles/report_member_view.html");
		$xltm->tpls->MergeBlock('tr','array',$port);
		$xltm->tpls->show();
		break;
	default:
		$dateList=array();
		$week=array('日','一','二','三','四','五','六');
		for ($i=-6;$i<1;$i++)
		{
			$new_date=date('Y-m-d',time()+($i*(24*60*60)));
			$dateList[$i]['week']=$week[date('w',time()+($i*(24*60*60)))];
			$bull=$xltm->SQL->GetRow("select count(*) as bull_count,sum(bull_money) as bull_money from buy_deal where bull_trust_date='$new_date' and sell='0'");
			$sell=$xltm->SQL->GetRow("select count(*) as sell_count,sum(sell_money) as sell_money from buy_deal where sell_trust_date='$new_date' and sell='1'");
			$dateList[$i]['bull_count']=($bull['bull_count'])?$bull['bull_count']:0;
			$dateList[$i]['bull_money']=($bull['bull_money'])?$bull['bull_money']:'0.00';
			$dateList[$i]['sell_count']=($sell['sell_count'])?$sell['sell_count']:0;
			$dateList[$i]['sell_money']=($sell['sell_money'])?$sell['sell_money']:'0.00';
			$dateList[$i]['date']=$new_date;
		}
		$xltm->tpls->LoadTemplate("./tmpfiles/report.html");
		$xltm->tpls->MergeBlock('tr','array',$dateList);
		$xltm->tpls->show();
		break;
}
function view_event($BlockName,&$CurrRec,$RecNum){
	$CurrRec['cost']=$CurrRec['bull_cost_money']+$CurrRec['sell_cost_money']+$CurrRec['save_money'];
	$CurrRec['member_yk']=$CurrRec['gain']-$CurrRec['bull_cost_money']-$CurrRec['sell_cost_money']-$CurrRec['dc_money']-$CurrRec['save_money'];
	
	$CurrRec['agent_gain']=($CurrRec['agent_gain']>0)?0-$CurrRec['agent_gain']:-$CurrRec['agent_gain'];
	$CurrRec['agent_yk']=$CurrRec['agent_gain']+$CurrRec['agent_bull_money']+$CurrRec['agent_sell_money']+$CurrRec['dc_money']+$CurrRec['agent_save_money'];
}
?>