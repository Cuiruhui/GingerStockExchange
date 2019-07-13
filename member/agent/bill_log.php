<?php
include_once('global.php');
$type=$_GET['type'];
$start_date=($_GET['start_date'])?$_GET['start_date']:$xltm->sys_date;
$start_date=$xltm->dateFrom($start_date);
$end_date=($_GET['end_date'])?$_GET['end_date']:$xltm->sys_date;
$end_date=$xltm->dateFrom($end_date);
$dateArr=array($start_date,$end_date);
$s_date=min($dateArr);
$e_date=max($dateArr);
switch ($type)
{
	case 'all':
		$xltm->user_log(1,"会员明细报表[代理商]:{$s_date}-{$e_date}");
		$list = array();
		$demos = '';
		$inquery = '';
		$i=0;
		$demo = isset($_REQUEST['demo']) ? $_REQUEST['demo'] : '-1';
		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
		if($demo!='-1') $inquery .= " and b.demo='$demo'";
		if($username) $inquery .=" and b.username='$username'";
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
		$totalmoney = 0;
		$query="select a.* from `bill_log` a left join user_users b on a.username=b.username where a.agent='{$MyUser}' $inquery and a.add_time>='$s_date 0:0:0' and a.add_time<='$e_date 23:59:59' order by add_time asc";
		//exit($query);
		$row=$xltm->SQL->GetRows($query);
		if($row['count']>0){
			$count = $row['count'];
			$toalmoney = $row['toalmoney'];
		}
		$xltm->tpls->LoadTemplate("./tmpfiles/bill_log_all.html");
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
	default:
		$week1=date('w');
		$dateList=array();
		$week=array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');

		$xltm->tpls->LoadTemplate("./tmpfiles/bill_log.html");
		$xltm->tpls->Show();
		break;
}
?>
