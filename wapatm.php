<?php
define('Load_Info', true);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");
$currentusername = $xltm->user_info['username'];
$isdemo = $xltm->user_info['demo'];
$realname = $xltm->user_info['realname'];
if($currentusername=='')
{
	$xltm->gourl('','waplogin.php');
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
		$xltm->tpls->LoadTemplate("./wap/wapatmlog.html",false);
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
		//判断是否休市时间
		if(!$xltm->AtmTime())
		{
			mb("对不起，提款时间段为开盘日的下午{$xltm->config['atm_PM_Start']}-{$xltm->config['atm_PM_End']} 敬请谅解！","",0);
			exit();		
		}
		if($types=='AddAtm')
		{
			//判断可用保证金是否足够
			$cash = $xltm->user_info['cash'];
			$cancash = $xltm->AvailableAtmCash();
			$frozencash = $xltm->user_info['frozencash'];
			$atmmoney = (isset($_POST['money']) && is_numeric($_POST['money'])) ? intval($_POST['money']) : 0;
			$atmpwd = $_POST['atmpwd'];
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
			$xltm->SQL->Execute("insert into `atmlog` set agent='{$xltm->user_info['agent']}',add_time='{$xltm->sys_time}',username='{$currentusername}',money='{$atmmoney}',bank='{$_POST['bank']}',bankname='{$_POST['bankname']}',bankrealname='{$_POST['bankrealname']}',bankno='{$_POST['bankno']}',ispay='0',banksheng='{$_POST['selProvince']}',bankshi='{$_POST['selCity']}'");
			//插入短信息
			$xltm->SQL->Execute("insert into `message` set username='{$xltm->user_info['username']}',tousername='administrator',title='用户【{$xltm->user_info['username']}】申请提款{$atmmoney}元！请及时处理！',content='<p>申请提款信息如下：</p><p>用户名：{$xltm->user_info['username']}</p><p>代 理 商：{$xltm->user_info['agent']}</p><p>提款金额：{$atmmoney}元</p><p>申请时间：{$xltm->sys_time}</p>',isread='0',add_time='{$xltm->sys_time}'");
			mb("OK!","wapatm.php?type=log",1);
			exit();
		}
		$usermoney = $xltm->user_info['money'];
		$usercash = $xltm->user_info['cash']; //用户当前保证金
		$total_bull_money = 0;
		$usercancash = 0;
		if($xltm->user_info['demo']=='0') //正式帐号才计算
		{
			$usercancash = $xltm->AvailableAtmCash();
		}
		$xltm->tpls->LoadTemplate("./wap/wapatm.html");
		$xltm->tpls->Show();
		break;
}

function event_row($BlockName,&$CurrRec,$RecNum)
{
	if($CurrRec['ispay']=='1')
	{
		$CurrRec['status'] = '<font color=red>OK</font>';
	}
	else if($CurrRec['ispay']=='-1')
	{
		$CurrRec['status'] = '<font color=gray>NO</font>';
	}
	else
	{
		$CurrRec['status'] = 'weichuli';
	}
}
?>
