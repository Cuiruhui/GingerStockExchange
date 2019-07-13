<?php
define('Load_Info', 1);
set_time_limit(0);
session_start();
ob_end_clean();
include_once("./Lib/xltm.class.php");
include_once("./Lib/curl_http.php");
echo str_pad(" ", 4096);
$step = isset($_REQUEST['step']) ? intval($_REQUEST['step']) : 1;
$gourl = "settlement.php";
echo "<style>*{font-size:12px; font-family:'宋体';} a {color:#0000FF; text-decoration:none;} a:hover {color:#ff0000; text-decoration:underline;}</style>";
if($xltm->startTIME() || date('H',time())<15)
{
	exit("<font color=red>目前处于开市时间，不能进行清算！</font>");
}
/**休市结算**/
//清除所有当天未成交的委托单
echo "<p><img src='./images/tips.gif' border='0' align='absmiddle' hspace='3' /><font color=red>本页面执行时间可能比较久，请耐心等待，直至弹出”统计完毕“对话框！</font></p>";

if($step == 1) //1/4
{
	echo "<p><img src='./images/loading.gif' border='0' align='absmiddle' hspace='3' />1/4 正在清理当天未成交的委托单，请稍候...</p>";
	clearTrust();
	$gourl = "settlement.php?step=2";
}
else if($step == 2)//自动平仓超过系统设置的时间数
{
	echo "<p><img src='./images/loading.gif' border='0' align='absmiddle' hspace='3' />2/4 正在平仓{$xltm->calc_day()}前的留仓单，请稍候...</p>";
	autoSell();
	$gourl = "settlement.php?step=3";
}
else if ($step == 3)//计算留仓费用
{
	echo "<p><img src='./images/loading.gif' border='0' align='absmiddle' hspace='3' />3/4 正在扣除留仓费用，请稍候...</p>";
	$gourl = "settlement.php?step=4";
	calcDealSave();
}
else if($step ==4)//计算当天的收益值
{
	echo "<p><img src='./images/loading.gif' border='0' align='absmiddle' hspace='3' />4/4 正在生成用户当天的帐单，请稍候...</p>";
	$gourl = "settlement.php?step=5";
	userBill();
}

if($step == 5) //统计完成
{
	echo "<p><font color=red>统计完毕！</font><script>alert('统计完毕，你可以离开本页了！');</script></p>";
}
else
{
	echo "<p><a href='{$gourl}'>如果你的浏览器没反应，请点击这里...</a></p>";
	echo "<script>function JumpUrl(){location='{$gourl}';}setTimeout('JumpUrl()',2000);</script>";
}
//代理佣金帐单
//echo '5.正在生成代理商佣金帐单…<br/>';
//agent_income();

//代理佣金结算(周五)
if(date('w',time())==5)
{
	//agent_settlement();
}

function agent_settlement()
{
	global $xltm;
	$start_date = date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
	$end_date = date("Y-m-d ",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
	if($rows=$xltm->SQL->GetRows("select * from `user_agent`"))
	{
		foreach($rows as $r)
		{
			$agent_cost_money = 0;
			if($row=$xltm->SQL->GetRow("select sum(money) as totalmoney from `agent_income` where agent='{$r['username']}' and income_date>='{$start_date}' and income_date<='{$end_date}'"))
			{
				if(is_numeric($row['totalmoney']) && $row['totalmoney']>0)
				{
					$agent_cost_money = $row['totalmoney'];
				}
			}
			//添加记录
			$qary=array(
				'agent'=>$r['username'],
				'start_date'=>$start_date,
				'end_date'=>$end_date,
				'money'=>$agent_cost_money,
				'ispay'=>0,
				'add_time'=>$xltm->sys_time
			);
			$query = $xltm->array2str($qary);
			$xltm->SQL->Execute("insert into `agent_settlement` set {$query}");
		}
	}
}

function clearTrust()
{
	global $xltm;
	if($rows=$xltm->SQL->GetRows("SELECT * FROM `buy_trust` WHERE app='1'"))
	{
		foreach ($rows as $r)
		{
			$xltm->SQL->Execute("UPDATE `buy_trust` set app='4',stock_deal_time='{$xltm->sys_time}' where id='{$r['id']}'");
			//返还用户的委托股票的资金(买入委托)
			if($r['trust_type']==1)
			{
				$xltm->SQL->Execute("UPDATE `user_users` set money=money+'{$r['money']}' where username='{$r['user']}'");
			}
			else
			{
				$xltm->SQL->Execute("UPDATE `buy_deal` set can_sell_num=can_sell_num+{$r['num']} where id='{$r['deal_id']}'");
			}
		}
	}
}

function userBill()
{
	global $xltm,$gourl;
	$pagesize = 20; //每次只统计20条
	$totalpage = 1;
	$totalcount = (isset($_REQUEST['total']) && is_numeric($_REQUEST['total'])) ? intval($_REQUEST['total']) : 0;
	$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
	if($totalcount == 0)
	{
		if($row=$xltm->SQL->GetRow("select count(*) as totalr from `user_users`"))
		{
			$totalcount = $row['totalr'];
		}
		else
		{
			return false;
		}
	}
	if($totalcount)
	{
		if($totalcount % $pagesize ==0)
		{
			$totalpage = floor($totalcount / $pagesize);
		}
		else
		{
			$totalpage = floor($totalcount / $pagesize) + 1;
		}
	}
	if($users = $xltm->SQL->GetRows("select username from `user_users` order by id asc limit ".($page-1)*$pagesize.",".$pagesize))
	{
		foreach($users as $r)
		{
			//计算用户当日平仓盈亏
			$pc_total_gain = 0;
			if($row=$xltm->SQL->GetRow("select sum(gain) as pc_total_gain from `buy_deal` where sell='1' and user='{$r['username']}' and sell_trust_date='{$xltm->sys_date}'"))
			{
				if(is_numeric($row['pc_total_gain']))
				{
					$pc_total_gain = $row['pc_total_gain'];
				}
			}
			
			//计算用户留仓盈亏,总留仓费,留仓量
			$save_gain_total = 0;
			$cost_save_money_total = 0; //总留仓费
			$save_money_total = 0; //总留仓量
			if($row=$xltm->SQL->GetRow("select sum(gain) as save_gain_total,sum(cost_save_money) as cost_save_money_total,sum(save_money) as save_money_total from `deal_save` where username='{$r['username']}' and save_date='{$xltm->sys_date}'"))
			{
				if(is_numeric($row['save_gain_total']))
				{
					$save_gain_total = $row['save_gain_total'];
				}
				if(is_numeric($row['cost_save_money_total']) && $row['cost_save_money_total']>0)
				{
					$cost_save_money_total = $row['cost_save_money_total'];
				}
				if(is_numeric($row['save_money_total']) && $row['save_money_total']>0)
				{
					$save_money_total = $row['save_money_total'];
				}
			}
			
			//本日交易手续费
			$cost_trade_money_total = 0;
			$cost_bull_money_total = 0;
			$cost_sell_money_total = 0;
			if($row=$xltm->SQL->GetRow("select sum(bull_cost_money) as cost_bull_money_total from `buy_deal` where user='{$r['username']}' and bull_trust_date='{$xltm->sys_date}'"))
			{
				if(is_numeric($row['cost_bull_money_total']) && $row['cost_bull_money_total']>0)
				{
					$cost_bull_money_total = $row['cost_bull_money_total'];
				}
			}
			if($row=$xltm->SQL->GetRow("select sum(sell_cost_money) as cost_sell_money_total from `buy_deal` where user='{$r['username']}' and sell_trust_date='{$xltm->sys_date}'"))
			{
				if(is_numeric($row['cost_sell_money_total']) && $row['cost_sell_money_total']>0)
				{
					$cost_sell_money_total = $row['cost_sell_money_total'];
				}
			}
			$cost_trade_money_total = $cost_bull_money_total + $cost_sell_money_total;
			
			//本日交易量
			$today_trade_money_total = 0;
			if($row = $xltm->SQL->GetRow("select sum(money) as today_trade_money_total from `buy_trust` where user='{$r['username']}' and app='2' and stock_trust_date='{$xltm->sys_date}'"))
			{
				if(is_numeric($row['today_trade_money_total']) && $row['today_trade_money_total']>0)
				{
					$today_trade_money_total = $row['today_trade_money_total'];
				}
			}
			
			//本月交易量
			$month_trade_money_total = 0;
			$start_date = date('Y-m-1',time());
			$end_date = $xltm->sys_date;
			if($row = $xltm->SQL->GetRow("select sum(money) as month_trade_money_total from `buy_trust` where user='{$r['username']}' and app='2' and (stock_trust_date>='{$start_date}' and stock_trust_date<='{$end_date}')"))
			{
				if(is_numeric($row['month_trade_money_total']) && $row['month_trade_money_total']>0)
				{
					$month_trade_money_total = $row['month_trade_money_total'];
				}
			}
			
			//可用保证金
			$cancash = $xltm->AvailableCash($r['username']);
			//本日损益
			$today_gain = $pc_total_gain + $save_gain_total;
			//本日净利=本日损益-留仓费
			$gain = $today_gain - $cost_save_money_total;
			
			//写入数据库
			$qary=array(
				'username'=>$r['username'],
				'bill_date'=>$xltm->sys_date,
				'today_trade_money_total'=>$today_trade_money_total,
				'month_trade_money_total'=>$month_trade_money_total,
				'save_money_total'=>$save_money_total,
				'cost_trade_money_total'=>$cost_trade_money_total,
				'cost_save_money_total'=>$cost_save_money_total,
				'gain'=>$gain,
				'today_gain'=>$today_gain,
				'cancash'=>$cancash,
				'add_time'=>$xltm->sys_time
			);
			$query = $xltm->array2str($qary);
			//判断是否存在相同的记录，如果存在则更新数据，反之添加数据
			if($row=$xltm->SQL->GetRow("select * from `user_bill` where username='{$r['username']}' and bill_date='{$xltm->sys_date}'"))
			{
				$xltm->SQL->Execute("update `user_bill` set {$query} where id='{$row['id']}'");
			}
			else
			{
				$xltm->SQL->Execute("insert into `user_bill` set {$query}");
			}
		}
	}
	if($page<$totalpage)
	{
		echo "<p><font color=red>当前第{$page}/{$totalpage}页，已完成".round($page/$totalpage*100,2)."%。。。</font></p>";
		$gourl = "settlement.php?step=4&total={$totalcount}&page=".($page+1);
	}
	else
	{
		echo "<p><font color=red>已完成100%，共生成用户帐单成功({$totalcount})</font></p>";
	}
	//echo "&nbsp;&nbsp;&nbsp;<font color=red>生成用户帐单成功({$total})！</font><br />";
}

function autoSell()
{
	global $xltm;
	$saveDate = $xltm->calc_day();
	$sell=$xltm->SQL->GetRows("select id from `buy_deal` where bull_trust_date<'$saveDate' and sell='0'");
	foreach($sell as $r)
	{
		$xltm->auto_sell($r['id']);
	}
}

function calcDealSave()
{
	global $xltm,$gourl;
	$pagesize = 20; //每次只统计20条
	$totalpage = 1;
	$totalcount = (isset($_REQUEST['total']) && is_numeric($_REQUEST['total'])) ? intval($_REQUEST['total']) : 0;
	$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
	if($totalcount == 0)
	{
		if($row=$xltm->SQL->GetRow("select count(*) as totalr from `buy_deal` where sell='0'"))
		{
			$totalcount = $row['totalr'];
		}
		else
		{
			return false;
		}
	}
	if($totalcount)
	{
		if($totalcount % $pagesize ==0)
		{
			$totalpage = floor($totalcount / $pagesize);
		}
		else
		{
			$totalpage = floor($totalcount / $pagesize) + 1;
		}
	}
	if($rows=$xltm->SQL->GetRows("select * from `buy_deal` where sell='0' order by id limit ".($page-1)*$pagesize.",".$pagesize))
	{
		$codelist = '';
		$total = count($rows);
		foreach($rows as $r)
		{
			$codelist .= (empty($codelist)?'':',').$r['stock_type'].$r['stock_code'];
		}
		$curl=&new Curl_HTTP_Client();
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$codelist,"",10);
		$html_data=iconv("GB2312","UTF-8",$html_data);
		if($html_data)
		{
			preg_match_all("/str_(.+?)\";/is",$html_data,$data);
			$quotes = str_replace('="',',',$data[1]);
		}
		else
		{
			echo "<font color=red>获取行情失败!</font><br />";
			return false;
		}
		$i = 0;
		foreach($rows as $r)
		{
			$cur_price = 0;
			$save_price = 0;
			$cost_bull_money = 0; //买入手续费，只有是当天持仓才计算
			$gain = 0;
			$quote=$quotes[$i];
			$Net = explode(',',$quote);
			if($Net[4]>0)
			{
				$cur_price = $Net[4]; //现价
			}
			else
			{
				$cur_price = $Net[3];
			}
			if($Net[3] && $r['bull_trust_date']<$xltm->sys_date) //非本日购买留仓价
			{
				$save_price = $Net[3]; //昨收价（留仓价）
			}
			else //本日购买 买入价
			{
				$save_price = $r['bull_price'];
			}
			//留仓量
			$save_money = $cur_price * $r['bull_num']*100;
			//留仓手续率
			$cost_save = $r['save_cost'];
			//留仓费用
			$cost_save_money = round($save_money*$cost_save,2);
			//留仓天数
			$save_day = $xltm->save_day($xltm->sys_date,$r['bull_trust_date']) + 1;
			//判断今天是否为周五并且后台设置节假日照收留仓库费
			/**/
			if(date('w',time())==5 && $xltm->config['rest_filter']=='1')
			{
				$save_day = $save_day + 2;
				$cost_save_money = $cost_save_money * 3; //周五、六、日一起收
			}
			//代理商获得的留仓费用
			$agent_cost_save_money = round($cost_save_money * ($r['agent_ret'] / 100),2); //80%
			//留仓盈亏
			if($cur_price==0 || $save_price==0) //停牌
			{
				$gain = 0;
			}
			else //计算盈亏
			{
				if($r['buy_type']==1) //买升
				{
					$gain = ($cur_price-$save_price)*$r['bull_num']*100;
				}
				else //买跌
				{
					$gain = ($save_price-$cur_price)*$r['bull_num']*100;
				}
				//判断是否为本日购买，如果是则扣除手续费
				if($r['bull_trust_date']==$xltm->sys_date)
				{
					$cost_bull_money = $r['bull_cost_money'];
					$gain = $gain - $cost_bull_money;
				}
				$gain = $gain - $cost_save_money;
			}
			//写入留仓记录
			$qary = array(
			'username'=>$r['user'],
			'deal_id'=>$r['id'],
			'stock_code'=>$r['stock_code'],
			'stock_name'=>$r['stock_name'],
			'stock_type'=>$r['stock_type'],
			'save_date'=>$xltm->sys_date,
			'save_num'=>$r['bull_num'],
			'save_price'=>$save_price,
			'cur_price'=>$cur_price,
			'save_day'=>$save_day,
			'save_money'=>$save_money,
			'cost_save_money'=>$cost_save_money,
			'cost_bull_money'=>$cost_bull_money,
			'gain'=>$gain,
			'bull_trust_date'=>$r['bull_trust_date'],
			'buy_type'=>$r['buy_type'],
			'add_time'=>$xltm->sys_time
			);
			$query = $xltm->array2str($qary);
			//判断当天记录是否存在，如果存在则更新
			if($row=$xltm->SQL->GetRow("select * from `deal_save` where deal_id='{$r['id']}' and save_date='{$xltm->sys_date}'"))
			{
				$xltm->SQL->Execute("update `deal_save` set {$query} where id='{$row['id']}'");
			}
			else //添加新记录
			{
				$xltm->SQL->Execute("insert into `deal_save` set {$query}");
				//更新留仓的费用和代理商留仓费的佣金
				$xltm->SQL->Execute("Update `buy_deal` set save_day='{$save_day}',save_money=save_money+{$cost_save_money},agent_save_money=agent_save_money+{$agent_cost_save_money} where id='{$r['id']}'");
				// 扣除用户的保证金
				$xltm->SQL->Execute("Update `user_users` set cash=cash-{$cost_save_money},money=money-{$cost_save_money} where username='{$r['user']}'");
				//添加用户帐单信息
				$xltm->bill_log($r['user'],$r['agent'],'留仓费',(0-$cost_save_money),"股票{$r['stock_name']}({$r['stock_code']}){$xltm->sys_date}日的留仓费！当天收盘价:{$cur_price}元, 数量:{$r['bull_num']}手！");
			}
			$i++;
		}
	}
	if($page<$totalpage)
	{
		echo "<p><font color=red>当前第{$page}/{$totalpage}页，已完成".round($page/$totalpage*100,2)."%。。。</font></p>";
		$gourl = "settlement.php?step=3&total={$totalcount}&page=".($page+1);
	}
	else
	{
		echo "<p><font color=red>已完成100%，正在进行下一项任务。</font></p>";
	}
	//echo "&nbsp;&nbsp;&nbsp;<font color=red>处理留仓单成功({$total})！</font><br />";
}

function agent_income()
{
	global $xltm;
	if($rows=$xltm->SQL->GetRows("select * from `user_agent`"))
	{
		foreach($rows as $r)
		{
			$cost_money = 0;
			if($row=$xltm->SQL->GetRow("select sum(a.cost_trade_money_total+a.cost_save_money_total) as total from `user_bill` a left join `user_users` b on a.username=b.username where b.agent='{$r['username']}' and b.demo='0' and a.bill_date='{$xltm->sys_date}'"))
			{
				if(is_numeric($row['total']) && $row['total']>0)
				{
					$cost_money = $row['total'];
				}
				
				$cost_money = round($cost_money * $r['cost_ret'] / 100,2);
				
				if($row=$xltm->SQL->GetRow("select * from `agent_income` where agent='{$r['username']}' and income_date='{$xltm->sys_date}'")) //存在更新
				{
					$xltm->SQL->Execute("update `agent_income` set money='{$cost_money}' where id='{$row['id']}'");
				}
				else //添加
				{
					$xltm->SQL->Execute("insert into `agent_income` set agent='{$r['username']}',income_date='{$xltm->sys_date}',money='{$cost_money}',add_time='{$xltm->sys_time}'");
				}
				
			}
		}
	}
}
?>
