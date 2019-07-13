<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
$open_shi=($xltm->startTIME())?'开市':'休市';
$on_agent=$xltm->SQL->GetRow("select count(id) as online from `user_agent`");
$online1=$on_agent['online'];
$on_login4=$xltm->SQL->GetRow("select count(id) as online from `user_users`");
$online4=$on_login4['online'];
$bull=$xltm->SQL->GetRow("select count(*) as count,sum(bull_money) as money from `buy_deal` where sell='0'");
$bull_count=($bull['count'])?$bull['count']:0;
$bull_money=($bull['money'])?$bull['money']:0;

$trust_time = 0;
$total_money = 0;
if($row=$xltm->SQL->GetRow("select count(*) as turst_time,sum(money) as money from `buy_trust` where app<'3'"))
{
	$trust_time = $row['turst_time'];
	$total_money = is_numeric($row['money']) ? $row['money'] : 0;
}

$curday = date('d');
$mstartdate = date('Y:m-1');
$menddate = date('Y-m-d');
$curxq = date('w')-1;
$wstartdate = date('Y:m-d',time()-$curxq*24*3600);
$wenddate = date('Y:m-d',time()+(6-$curxq)*24*3600);
$dstartdate = date('Y:m-d');
$denddate = date('Y:m-d');

/*代理商总水费(买入手续费+卖出手续费+留仓费*/
/*
$allagcharge = 0;
$row = $xltm->SQL->GetRow("select sum(a.agent_bull_money+a.agent_sell_money+a.agent_save_money) as tm from `buy_deal` a left join `user_users` b on a.user=b.username where b.demo='0'");
$allagcharge = $row['tm']?$row['tm']:0;
*/
/*当月代理商水费*/
/*
$monthagcharge = 0;
$row = $xltm->SQL->GetRow("select sum(a.agent_bull_money+a.agent_sell_money+a.agent_save_money) as tm from `buy_deal` a left join `user_users` b on a.user=b.username  where (a.bull_trust_time between '{$mstartdate} 0:0:0' and '{$menddate} 23:59:59')");
$monthagcharge = $row['tm']?$row['tm']:0;
*/
/*本周代理商水费*/
/*
$weekagcharge = 0;
$row = $xltm->SQL->GetRow("select sum(a.agent_bull_money+a.agent_sell_money+a.agent_save_money) as tm from `buy_deal` a left join `user_users` b on a.user=b.username where (a.bull_trust_time between '{$wstartdate} 0:0:0' and '{$wenddate} 23:59:59')");
$weekagcharge = $row['tm']?$row['tm']:0;
*/
/*本日代理商水费*/
/*
$dayagcharge = 0;
$row = $xltm->SQL->GetRow("select sum(a.agent_bull_money+a.agent_sell_money+a.agent_save_money) as tm from `buy_deal` a left join `user_users` b on a.user=b.username where (a.bull_trust_time between '{$dstartdate} 0:0:0' and '{$denddate} 23:59:59')");
$dayagcharge = $row['tm']?$row['tm']:0;
*/

/*总充值金额*/
$allpay = 0;
$row = $xltm->SQL->GetRow("select sum(money) as tm from `payment` where result='1'");
$allpay = $row['tm']?$row['tm']:0;
/*当月充值金额*/
$monthpay = 0;
$row = $xltm->SQL->GetRow("select sum(money) as tm from `payment` where result='1' and (add_time between '{$mstartdate} 0:0:0' and '{$menddate} 23:59:59')");
$monthpay = $row['tm']?$row['tm']:0;
/*本周充值金额*/
$weekpay = 0;
$row = $xltm->SQL->GetRow("select sum(money) as tm from `payment` where result='1' and (add_time between '{$wstartdate} 0:0:0' and '{$wenddate} 23:59:59')");
$weekpay = $row['tm']?$row['tm']:0;
/*本日充值金额*/
$row = $xltm->SQL->GetRow("select sum(money) as tm from `payment` where result='1' and (add_time between '{$dstartdate} 0:0:0' and '{$denddate} 23:59:59')");
$daypay = $row['tm']?$row['tm']:0;

/*用户总盈亏*/
$alluseragin = 0;
$row = $xltm->SQL->GetRow("select sum(a.money) as tm from `bill_log` a left join `user_users` b on a.username=b.username where a.bill_type<>'充值' and b.demo='0'");
$alluseragin = $row['tm']?$row['tm']:0;
$alluseragin = 0-$alluseragin;
/*当月用户总盈亏*/
$monthuseragin = 0;
$row = $xltm->SQL->GetRow("select sum(a.money) as tm from `bill_log` a left join `user_users` b on a.username=b.username where a.bill_type<>'充值' and b.demo='0' and (a.add_time between '{$mstartdate} 0:0:0' and '{$menddate} 23:59:59')");
$monthuseragin = $row['tm']?$row['tm']:0;
$monthuseragin = 0-$monthuseragin;
/*本周用户总盈亏*/
$weekuseragin = 0;
$row = $xltm->SQL->GetRow("select sum(a.money) as tm from `bill_log` a left join `user_users` b on a.username=b.username where a.bill_type<>'充值' and b.demo='0' and (a.add_time between '{$wstartdate} 0:0:0' and '{$wenddate} 23:59:59')");
$weekuseragin = $row['tm']?$row['tm']:0;
$weekuseragin = 0-$weekuseragin;
/*本日用户总盈亏*/
$row = $xltm->SQL->GetRow("select sum(a.money) as tm from `bill_log` a left join `user_users` b on a.username=b.username where a.bill_type<>'充值' and b.demo='0' and (add_time between '{$dstartdate} 0:0:0' and '{$denddate} 23:59:59')");
//exit("select sum(a.money) as tm from `bill_log` a left join `user_users` b on a.username=b.username where b.demo='0' and (add_time between '{$dstartdate} 0:0:0' and '{$denddate} 23:59:59')");
$dayuseragin = $row['tm']?$row['tm']:0;
$dayuseragin = 0-$dayuseragin;

$xltm->tpls->LoadTemplate("./tmpfiles/main.html");
$xltm->tpls->Show();
?>
