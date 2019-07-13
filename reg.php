<?php
define('Load_Info', 2);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
//检测验证码
$act = isset($_GET['act'])?$_GET['act']:'';
$cur = 3;
if($act=='chkcode')
{
	if(!isset($_SESSION[$session_prefix.'validate']))
	{
		exit('false');
	}
	$validate = $_GET['validate'];
	if($validate && strtolower($validate)==strtolower($_SESSION[$session_prefix.'validate']))
	{
		exit('true');
	}
	else
	{
		exit('false');
	}
}
else if($act=='chkuser')
{
	$username = isset($_GET['username']) ? $_GET['username'] : '';
	if(empty($username))
	{
		exit('false');
	}
	$row = $xltm->SQL->GetRow("select * from `user_users` where username='{$username}'");
	if(is_array($row))
	{
		exit('false');
	}
	else
	{
		exit('true');
	}
}
else if($act=='wapsave') //保存注册信息
{
	$AgentID = isset($_POST['agent'])?$_POST['agent']:'daili';

	//读出代理商的初始配置值
	$row = $xltm->SQL->GetRow("select * from `user_agent` where username='{$AgentID}'");
	if(!is_array($row))
	{
		mb("系统注册失败，请联系管理员！error:0002","",0);
	}
	else
	{
		//判断代理商是否已达到最大用户数
		if($row['mem_num']>=$row['member_max'])
		{
			mb("对不起，系统会员已达到最大值不允许再注册，请联系管理员！error:0003","",0);
		}
		$demo = $_POST['demo'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];
		$currency = isset($_POST['currency'])?$_POST['currency']:'RMB';
		$validate = isset($_POST['validate'])?$_POST['validate']:'';
		$realname =$_POST['realname'];
		$identification =$_POST['identification'];
		$mobile = $_POST['mobile'];
		$atmpwd = $_POST['atmpwd'];
		$email = isset($_POST['email'])?$_POST['email']:'';
		$qq = $_POST['qq'];
		$cash = 0;
		$money = 0;
		$cost_bull=($row['cost_bull'])?$row['cost_bull']:'0';
		$cost_sell=($row['cost_sell'])?$row['cost_sell']:'0';
		$cost_save=($row['cost_save'])?$row['cost_save']:'0';
		$cost_ret=($row['cost_ret'])?$row['cost_ret']:'0';
		$num_min = $row['num_min'];
		$num_max = $row['num_max'];
		$agent = $AgentID;
		$regdate = $xltm->sys_time;
		$frozenmoney = 0;
		
		
		
		
		
		if(empty($password) || strlen($password)<6 || strlen($password)>12)
		{
			mb("密码长度应在6-12个字符之间！","",0);
		}
		
		if($password!=$repassword)
		{
			mb("两次输入密码不一致！","",0);
		}
		
		if(empty($realname))
		{
			mb("请输入真实姓名！","",0);
		}
		
		if(empty($identification))
                {
                        mb("请输入身份证号码！","",0);
                }
	
		if(empty($mobile))
		{
			mb("请输入手机号码！","",0);
		}
		
		
		
		/*if(empty($email))
		{
			mb("请输入emaill!","",0);
		}*/
		

		//检测用户名是否可用
		$validate_username=$xltm->User->validate_username($username);
		if(!$validate_username || $User=$xltm->SQL->GetRow("SELECT * FROM `user_users` WHERE username='{$username}'"))
		{
			mb("用户名已存在或者不符合规则！请重新输入！","",0);
		}
		$new_activation_code=$xltm->User->generate_activation_code($username,$password,$activation_code);
		$new_password=$xltm->User->generate_password_hash($password, $new_activation_code);
		
		//保存
		$query = "Insert Into `user_users` set username='{$username}',password='{$new_password}',alias='{$realname}',code='{$new_activation_code}',active='yes',last_login='0',last_action='0',blocked='no',tries='0',last_try='1319722996',activation_time='0',";
		$query .= "money='{$money}',cash='{$cash}',cost_bull='{$cost_bull}',cost_sell='{$cost_sell}',cost_save='{$cost_save}',num_min='{$num_min}',num_max='{$num_max}',agent='{$agent}',";
		$query .= "regdate='{$regdate}',demo='{$demo}',currency='{$currency}',realname='{$realname}',mobile='{$mobile}',atmpwd='{$atmpwd}',email='{$email}',qq='{$qq}',frozencash='0.00',cancash='{$cash}',";
		$query .="identification='{$identification}'";
		$xltm->SQL->Execute($query);
		
		//更新代理商会员数
		$xltm->SQL->Execute("update `user_agent` set mem_num=(select count(*) from `user_users` where agent='{$AgentID}') where username='{$AgentID}'");
		
		
		exit("<script>self.location.href='http://gpto.pw/waplogin.php';</script>");
		
	 }

}

else if($act=='save') //保存注册信息
{
    $zhuce = $xltm->config['open_xitong'];
	if($zhuce == 0)
	{
		mb("系统暂时禁止注册会员，请联系客服！","",0);
	}
    
	if($_POST['agent']!='') $AgentID = $_POST['agent']; 

	//读出代理商的初始配置值
	$row = $xltm->SQL->GetRow("select * from `user_agent` where username='{$AgentID}'");
	if(!is_array($row))
	{
		mb("系统注册失败，请联系管理员！error:0002","",0);
	}
	else
	{
		//判断代理商是否已达到最大用户数
		if($row['mem_num']>=$row['member_max'])
		{
			mb("对不起，系统会员已达到最大值不允许再注册，请联系管理员！error:0003","",0);
		}
		$demo = $_POST['demo'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];
		$currency = $_POST['currency'];
		$validate = $_POST['validate'];
		$realname =$_POST['realname'];
		$identification =$_POST['identification'];
		$mobile = $_POST['mobile'];
		
		$bankcode = $_POST['bankcode'];
		$bankno = $_POST['bankno'];
		$bankname = $_POST['bankname'];
		
		$atmpwd = $_POST['atmpwd'];
		$email = $_POST['email'];
		$qq = $_POST['qq'];
		$cash = 0;
		$money = 0;
		$cost_bull=($row['cost_bull'])?$row['cost_bull']:'0';
		$cost_sell=($row['cost_sell'])?$row['cost_sell']:'0';
		$cost_save=($row['cost_save'])?$row['cost_save']:'0';
		$cost_ret=($row['cost_ret'])?$row['cost_ret']:'0';
		$num_min = $row['num_min'];
		$num_max = $row['num_max'];
		$agent = $AgentID;
		$regdate = $xltm->sys_time;
		$frozenmoney = 0;
		
		if(!isset($_SESSION[$session_prefix.'validate']) || empty($validate) || strtolower($_SESSION[$session_prefix.'validate'])!=strtolower($validate))
		{
			mb("验证码输入有误！","",0);
		}
		
		if($demo != '0') //试玩帐号
		{
			$demo = 1;
			$cash = 10000; //默认1万的可用现金
			$money = 100000;
			if(substr($username,0,1)!='x')
			{
				mb("试玩帐号必须以字母x开头！","",0);
			}
		}
		else
		{
			$demo = 0;
			if(substr($username,0,1)!='u')
			{
				mb("正式帐号必须以字母u开头！","",0);
			}
		}
		
		if(empty($password) || strlen($password)<6 || strlen($password)>12)
		{
			mb("密码长度应在6-12个字符之间！","",0);
		}
		
		if($password!=$repassword)
		{
			mb("两次输入密码不一致！","",0);
		}
		
		if(empty($realname))
		{
			mb("请输入真实姓名！","",0);
		}
		
		if(empty($identification))
                {
                        mb("请输入身份证号码！","",0);
                }
	
		if(empty($mobile))
		{
			mb("请输入手机号码！","",0);
		}
		
		if(empty($bankno))
		{
			mb("请输入银行卡号！","",0);
		}
		if(empty($bankname))
		{
			mb("请输入银行支行名字！","",0);
		}
	
		
		if((empty($atmpwd) || $atmpwd=='0000') && $demo==0)
		{
			mb("为了您的资金安全，请选择取款密码或者密码不能为初始的0000！","",0);
		}
		
		/*if(empty($email))
		{
			mb("请输入emaill!","",0);
		}
		if(empty($qq))
		{
			mb("请输入qq号码！","",0);
		}*/

		//检测用户名是否可用
		$validate_username=$xltm->User->validate_username($username);
		if(!$validate_username || $User=$xltm->SQL->GetRow("SELECT * FROM `user_users` WHERE username='{$username}'"))
		{
			mb("用户名已存在或者不符合规则！请重新输入！","",0);
		}
		$new_activation_code=$xltm->User->generate_activation_code($username,$password,$activation_code);
		$new_password=$xltm->User->generate_password_hash($password, $new_activation_code);
		
		//保存
		$query = "Insert Into `user_users` set username='{$username}',password='{$new_password}',alias='{$realname}',code='{$new_activation_code}',active='yes',last_login='0',last_action='0',blocked='no',tries='0',last_try='1319722996',activation_time='0',";
		$query .= "money='{$money}',cash='{$cash}',cost_bull='{$cost_bull}',cost_sell='{$cost_sell}',cost_save='{$cost_save}',num_min='{$num_min}',num_max='{$num_max}',agent='{$agent}',";
		$query .= "regdate='{$regdate}',demo='{$demo}',currency='{$currency}',realname='{$realname}',mobile='{$mobile}',bankcode='{$bankcode}',bankno='{$bankno}',bankname='{$bankname}',atmpwd='{$atmpwd}',email='{$email}',qq='{$qq}',frozencash='0.00',cancash='{$cash}',";
		$query .="identification='{$identification}'";
		$xltm->SQL->Execute($query);
		
		//更新代理商会员数
		$xltm->SQL->Execute("update `user_agent` set mem_num=(select count(*) from `user_users` where agent='{$AgentID}') where username='{$AgentID}'");
		
		if($demo=='0')
		{
			mb("恭喜您，注册会员成功！请登录系统后尽快进行在线充款，开始您的财富之旅！提示：交易有风险，入市请谨慎！","index.php",1);
		}
		else
		{
			mb("注册用户成功，交易有风险，入市请谨慎，请登录！","index.php",1);
		}
	 }
}
$stype = $_REQUEST['utype']?$_REQUEST['utype']:'1';
$bfchar = 'u';
$email = '';
$qq = '';
$realname = '';
$mobile = '';
if(!isset($stype) || empty($stype) || $stype=='0')
{
	$stype = '0'; //默认为试玩帐号
	$bfchar = 'x';
	$realname = '';
	$mobile = '';
	$email = 'shiwan@abc.com';
	$qq = '';
}

if($_GET['agent']=='') $agent = $AgentID;
else $agent = $_GET['agent'];

$xltm->tpls->LoadTemplate("./tmpfiles/reg.html");
$xltm->tpls->Show();
?>
