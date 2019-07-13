<?php
define('Load_Info', true);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$currentusername = $xltm->user_info['username'];
$isdemo = $xltm->user_info['demo'];
$realname = $xltm->user_info['realname'];
if($currentusername=='')
{
	$xltm->gourl('','login.php');
	exit();
}
$types = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'add';
switch($types)
{
	case 'check_atmpwd':
		$atmpwd = $_GET['atmpwd'];
		$username = $currentusername;
		$row = $xltm->SQL->GetRow("Select * from `user_users` where username='{$username}' and atmpwd='{$atmpwd}'");
		if(is_array($row))
		{
			echo "true";
		}
		else
		{
			echo "false";
		}
		break;
	case 'log':
		//分页设置
		if (!isset($_REQUEST)) $_REQUEST=&$HTTP_GET_VARS ;
		$PageNum =(isset($_REQUEST['PageNum']))?$_REQUEST['PageNum']:1;
		$RecCnt =(isset($_REQUEST['RecCnt']))?intval($_REQUEST['RecCnt']):'-1';
		$PageSize = 150000;
		//End page
		$rows=$xltm->SQL->GetRows("select * from `atmlog` where username='{$currentusername}' order by add_time desc");
		$xltm->tpls->LoadTemplate("./tmpfiles/payment_atmlog.html",false);
		$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
		$RecCnt = $xltm->tpls->MergeBlock('tr','array',$rows);
		$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
		$showPage=($RecCnt>0)?1:'';
		$xltm->tpls->Show();
		break;
	default:
		if($isdemo)
		{
			mb("您目前级别为试玩帐号，不能进行提款操作！","",3);
			exit();		
		}
		
	
		if(!$xltm->AtmTime())
		{
			mb("对不起，提款时间段为开盘日的 下午{$xltm->config['atm_PM_Start']}-{$xltm->config['atm_PM_End']} 敬请谅解！","",0);
			exit();		
		}
		

		
		//本日交易量
		$today_trade_money = 0;
		if($row = $xltm->SQL->GetRow("select sum(money) as totalmoney from `buy_trust` where user='{$xltm->user_info['username']}' and app='2' and stock_trust_date='{$xltm->sys_date}'"))
		{
			if(is_numeric($row['totalmoney']) && $row['totalmoney']>0)
			{
				$today_trade_money = $row['totalmoney'];
			}
		}		
	
		//用户持仓
		$total_bull_money = 0;
		$total_bull_money_cash = 0;
		if($row = $xltm->SQL->GetRow("Select sum(bull_money) as tt from `buy_deal` where sell='0' and user='{$xltm->user_info['username']}'"))
		{
			$total_bull_money = round($row['tt'] ,2);
			$total_bull_money_cash = round($total_bull_money * 0.1,2);
		}
		
		//用户委托
		$total_trust_money = 0;
		$total_trust_money_cash = 0;
		if($row=$xltm->SQL->GetRow("select sum(money) as tru from `buy_trust` where app='1' and trust_type='1' and user='{$xltm->user_info['username']}'"))
		{
			$total_trust_money = round($row['tru'],2);
			$total_trust_money_cash = round($total_trust_money*0.1,2);
		}	
		
		$liuchange = $total_bull_money + $total_trust_money ;
		
		//判断用户是否已提款
		$today_money_pay = 0;
		$m_data = date('Y-m-d');
		if($row = $xltm->SQL->GetRow("select sum(pay_money) as paymoney from `atm` where username='{$xltm->user_info['username']}' and update_time='$m_data'"))
		{
			if(is_numeric($row['paymoney']) && $row['paymoney']>0)
			{
				$today_money_pay = $row['paymoney'];
			}
		}	
		
	
		if( $today_money_pay > 0){
			
			mb("对不起，每天只能提款一次！","",0);

			}			
		
		
		if($types=='AddAtm')
		{
			//判断可用保证金是否足够
			$cash = $xltm->user_info['cash'];
			$cancash = $xltm->AvailableAtmCash();
			
			if($today_trade_money == 0 || $liuchange != 0){
				$cancash = $cancash * 0.3;
				}
			$today_money = $cancash - $atmmoney;
			$frozencash = $xltm->user_info['frozencash'];
			$atmmoney = (isset($_POST['money']) && is_numeric($_POST['money'])) ? intval($_POST['money']) : 0;
			$atmpwd = $_POST['atmpwd'];
			
			if($atmmoney < 100)
			{
				mb("对不起，您的提现金额度金额必须大于等于100！","",0);
				exit();
			}
			
			
			if($cancash<$atmmoney)
			{
				mb("对不起，您的可提现金额度小于要提取的金额！","",0);
				exit();
			}
			
			if($atmpwd=='')
			{
				mb("对不起，请输入提款密码！","",0);
				exit();
			}
			if($atmpwd!=$xltm->user_info['atmpwd'])
			{
				mb("对不起，提款密码不正确！","",0);
				exit();	
			}
			//更新用户的可用余额以及不可用余额 
			$xltm->SQL->Execute("update `user_users` set cash=cash-{$atmmoney},frozencash=frozencash+{$atmmoney} where username='{$currentusername}'");
			
			// INSERT INTO `yishiadata`.`atm` (`id`, `username`, `pay_money`, `update_time`, `agent`) VALUES (NULL, 'u131', '50', '2013-08-30', NULL);			
			$xltm->SQL->Execute("insert into `atm` set username='{$currentusername}',pay_money='{$atmmoney}',can_money='{$today_money}',update_time='{$xltm->sys_time}'");
			
			$xltm->SQL->Execute("insert into `atmlog` set agent='{$xltm->user_info['agent']}',add_time='{$xltm->sys_time}',username='{$currentusername}',money='{$atmmoney}',bank='{$_POST['bank']}',bankname='{$_POST['bankname']}',bankrealname='{$_POST['bankrealname']}',bankno='{$_POST['bankno']}',ispay='0'");
			//插入短信息
			$xltm->SQL->Execute("insert into `message` set username='{$xltm->user_info['username']}',tousername='administrator',title='用户【{$xltm->user_info['username']}】申请提款{$atmmoney}元！请及时处理！',content='<p>申请提款信息如下：</p><p>用户名：{$xltm->user_info['username']}</p><p>代 理 商：{$xltm->user_info['agent']}</p><p>提款金额：{$atmmoney}元</p><p>申请时间：{$xltm->sys_time}</p>',isread='0',add_time='{$xltm->sys_time}'");
			mb("提款信息保存成功.请耐心等待管理员打款!","atm.php?type=log",1);
			exit();
		}
		
		$usermoney = $xltm->user_info['money'];
		$usercash = $xltm->user_info['cash']; //用户当前保证金
		$total_bull_money = 0;
		$usercancash = 0;
		if($xltm->user_info['demo']=='0') //正式帐号才计算
		{
			$usercancash = $xltm->AvailableAtmCash();
			
			if($today_trade_money == 0 || $liuchange != 0){
				$usercancash = $usercancash * 0.3;
				}
				
			
		}
		$xltm->tpls->LoadTemplate("./tmpfiles/payment_atm.html");
		$xltm->tpls->Show();
		break;
}

function event_row($BlockName,&$CurrRec,$RecNum)
{
	if($CurrRec['ispay']=='1')
	{
		$CurrRec['status'] = '<font color=red>已打款</font>';
	}
	else if($CurrRec['ispay']=='-1')
	{
		$CurrRec['status'] = '<font color=gray>回退</font>';
	}
	else
	{
		$CurrRec['status'] = '未处理';
	}
}




?>
