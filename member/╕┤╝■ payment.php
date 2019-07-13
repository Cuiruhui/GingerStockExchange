<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

//充值提交用户名

if($_GET['orderno'] && $_GET['username']){

 $xltm->user_info['username'] = $_GET['username'];

}

$currentusername = $xltm->user_info['username'];
$mobile			 = $xltm->user_info['mobile'];

$isdemo = $xltm->user_info['demo'];
if($currentusername=='')
{
	$xltm->gourl('','login.php');
	exit();
}

$r1="test";
$r6=$xltm->config['minmoney'];

$types = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'pay';
switch($types)
{
	case 'log':
		//分页设置
		if (!isset($_REQUEST)) $_REQUEST=&$HTTP_GET_VARS ;
		$PageNum =(isset($_REQUEST['PageNum']))?$_REQUEST['PageNum']:1;
		$RecCnt =(isset($_REQUEST['RecCnt']))?intval($_REQUEST['RecCnt']):'-1';
		$PageSize = 100000;
		//End page
		$rows=$xltm->SQL->GetRows("select * from `payment` where username='{$currentusername}' order by add_time desc");
		$xltm->tpls->LoadTemplate("./tmpfiles/payment_log.html");
		$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
		$RecCnt = $xltm->tpls->MergeBlock('tr','array',$rows);
		$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
		$showPage=($RecCnt>0)?1:'';
		$xltm->tpls->Show();
		break;
	case 'fail':
		$xltm->tpls->LoadTemplate("./tmpfiles/payment_fail.html");
		$xltm->tpls->Show();
		break;
	case 'add':
	
		$time = date("Y-m-d H:i:s",time());
		
		//充值入库
		$orderno = trim($_GET['orderno']);
		$agent = trim($_GET['agent']);
		$merid = trim($_GET['merid']);
		$money = trim($_GET['money']);
		$add_time = $time;
		$payid = trim($_GET['payid']);
	
		$sql = "INSERT INTO `payment` VALUES ('', '{$orderno}', '{$xltm->user_info['username']}', 'Buy', '{$agent}', '{$merid}', '0', null, '{$money}', 'CNY', null, null, '{$add_time}', null, null, '{$payid}')";
		$xltm->SQL->Execute($sql);
		exit();
		break;
	case 'cz':
		$time = date("Y-m-d H:i:s",time());
	
		//充值
		$orderno = trim($_GET['orderno']);
		$payno = trim($_GET['payno']);
		$add_time = $time;
	
		//读取订单信息
		$row = $xltm->SQL->GetRow("Select * from `payment` where orderno='{$orderno}'");
		if(is_array($row))
		{
			if($row['result']=='0')
			{	
				$username = $row['username']; 
				$money = $row['money']; 
				$urow = $xltm->SQL->GetRow("Select agent from `user_users` where username='{$username}'");
				$agent = $urow['agent'];
				//更新支付订单信息
				$xltm->SQL->Execute("update `payment` set result='1',trxid='{$payno}',uid='',return_time='{$xltm->sys_time}',params='' where orderno='{$orderno}'");
				//更新用户的可用余额和保证金
				$money2 = ceil($xltm->config['cost_exchange_rate'] * $money);
				$xltm->SQL->Execute("update `user_users` set money=money + {$money2},cash=cash+{$money} where username='{$username}'");
				//添加用户帐单信息
				$xltm->SQL->Execute("insert into `bill_log` set bill_type='充值',agent='{$agent}',username='{$username}',money='{$money}',remark='在线支付入款',add_time='{$xltm->sys_time}',add_date='{$xltm->sys_date}'");
				exit();
			}
			else
			{
				exit("false:002");
			}
		}
		exit();
		break;		
		
	default:
		;
}
	if($isdemo)
	{
		echo "<script type='text/javascript'>
				alert('您目前级别为试玩帐号，不能进行支付操作！');
				history.go(-1);
				</script>";

		exit();		
	}
	$orderno = date('YmdHis',time()).rand(100,200);
	$cmd = 'Buy';
	$money = $_POST['money'];
	$cur = 'CNY'; //交易币种
	$add_time = $xltm->sys_time;
	$agent = $xltm->user_info['agent']; //所属代理商
	
	//读取用户自定义指定的支付接口
	if($pay_config = $xltm->SQL->GetRow("select a.payid,b.merid,b.secretkey,connecturl,callbackurl,b.name from `user_users` a inner join `pay_config` b on a.payid=b.id where a.username='{$xltm->user_info['username']}' and a.payid>0"))
	{
	
		//存在自定义支付接口并且支付接口配置信息存在
		$mp = $pay_config['payid'];
		$merid = $pay_config['merid']; //商户代码
		$webid = $merid;
		$webpwd = $pay_config['secretkey']; //商户代码
		$payid = $pay_config['payid']; //支付接口id
		$payname = $pay_config['name']; //支付接口名称

		$connecturl = $pay_config['connecturl'];
		$callbackurl = $pay_config['callbackurl'];	
	}
	else
	{
		if($pay_config = $xltm->SQL->GetRow("Select id,merid,connecturl,callbackurl,secretkey,name from `pay_config` where type='IN' and accounttype='1' and (payinterfacename='易宝' or payinterfacename='购宝') and active='1' and agent='{$agent}'"))
		{
			$mp = $pay_config['id'];
			$merid = $pay_config['merid']; //商户代码
			$webid = $merid;
			$webpwd = $pay_config['secretkey']; //商户代码
			$payid = $pay_config['id']; //支付接口id
			$payname = $pay_config['name']; //支付接口名称

			$connecturl = $pay_config['connecturl'];
			$callbackurl = $pay_config['callbackurl'];	

		}
		else
		{
			echo '<script>alert("支付系统错误，请联系管理员！"); history.back(-1);</script>';
			exit();
		}
	}
	$xltm->tpls->LoadTemplate("./tmpfiles/payment_huanxun.html");
	$xltm->tpls->Show();

?>
