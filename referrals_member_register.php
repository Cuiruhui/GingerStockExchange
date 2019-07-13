<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	if($xltm->user_info['referrals']==0)
	{
		echo "<script>parent.ymPrompt.errorInfo({title:'下线会员注册',message:'对不起，你没有开通下线会员权限！',handler:function(){self.location.href='stock_lists.php';}});</script>";
		exit();
	}
	$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'unknow';
	if($type == 'regsubmit')
	{
		//读出代理商的初始配置值
		$agent = $xltm->user_info['agent'];
		$row = $xltm->SQL->GetRow("select * from `user_agent` where username='{$agent}'");
		if(!is_array($row))
		{
			echo "<script>parent.ymPrompt.errorInfo({title:'下线会员注册',message:'系统注册失败，请联系管理员！error:0002',handler:function(){history.go(-1);}});</script>";
			exit();
		}
		$username = strtolower($_POST['username']);
		$password = $_POST['password'];
		$repassword = $_POST['password1'];
		$currency = 'RMB';
		$realname =$_POST['realname'];
		$mobile = $_POST['mobile'];
		$atmpwd = $_POST['atmpwd'];
		$email = $_POST['email'];
		$cash = 0;
		$money = 0;
		$referrals = 0;
		$referr_name = $xltm->user_info['username'];
		
		$cost_bull=($row['cost_bull'])?$row['cost_bull']:'0';
		$cost_sell=($row['cost_sell'])?$row['cost_sell']:'0';
		$cost_save=($row['cost_save'])?$row['cost_save']:'0';
		$cost_ret=($row['cost_ret'])?$row['cost_ret']:'0';
		$num_min = $row['num_min'];
		$num_max = $row['num_max'];
		$regdate = $xltm->sys_time;
		$frozenmoney = 0;
		$demo = 0;
		if(substr($username,0,1)!='u')
		{
			echo "<script>parent.ymPrompt.errorInfo({title:'下线会员注册',message:'正式帐号必须以字母u开头！',handler:function(){history.go(-1);}});</script>";
			exit();
		}

		if(empty($password) || strlen($password)<6 || strlen($password)>12)
		{
			echo "<script>parent.ymPrompt.errorInfo({title:'下线会员注册',message:'密码长度应在6-12个字符之间！',handler:function(){history.go(-1);}});</script>";
			exit();
		}
		
		if($password!=$repassword)
		{
			echo "<script>parent.ymPrompt.errorInfo({title:'下线会员注册',message:'两次输入密码不一致！',handler:function(){history.go(-1);}});</script>";
			exit();
		}
		
		if(empty($realname))
		{
			echo "<script>parent.ymPrompt.errorInfo({title:'下线会员注册',message:'请输入真实姓名！',handler:function(){history.go(-1);}});</script>";
			exit();
		}
		
		if(empty($mobile))
		{
			echo "<script>parent.ymPrompt.errorInfo({title:'下线会员注册',message:'请输入手机号码！',handler:function(){history.go(-1);}});</script>";
			exit();
		}
		
		if((empty($atmpwd) || $atmpwd=='0000') && $demo==0)
		{
			echo "<script>parent.ymPrompt.errorInfo({title:'下线会员注册',message:'为了您的资金安全，请选择取款密码或者密码不能为初始的0000！',handler:function(){history.go(-1);}});</script>";
			exit();
		}
		
		if(empty($email))
		{
			echo "<script>parent.ymPrompt.errorInfo({title:'下线会员注册',message:'请输入emaill!',handler:function(){history.go(-1);}});</script>";
			exit();
		}
		
		//检测用户名是否可用
		$validate_username=$xltm->User->validate_username($username);
		if(!$validate_username || $User=$xltm->SQL->GetRow("SELECT * FROM `user_users` WHERE username='{$username}'"))
		{
			echo "<script>parent.ymPrompt.errorInfo({title:'下线会员注册',message:'用户名已存在或者不符合规则！请重新输入！',handler:function(){history.go(-1);}});</script>";
			exit();
		}
		$new_activation_code=$xltm->User->generate_activation_code($username,$password,$activation_code);
		$new_password=$xltm->User->generate_password_hash($password, $new_activation_code);
		
		//保存
		$query = "Insert Into `user_users` set username='{$username}',password='{$new_password}',alias='{$realname}',code='{$new_activation_code}',active='yes',last_login='0',last_action='0',blocked='no',tries='0',last_try='1319722996',activation_time='0',";
		$query .= "money='{$money}',cash='{$cash}',cost_bull='{$cost_bull}',cost_sell='{$cost_sell}',cost_save='{$cost_save}',num_min='{$num_min}',num_max='{$num_max}',agent='{$agent}',";
		$query .= "regdate='{$regdate}',demo='{$demo}',currency='{$currency}',realname='{$realname}',mobile='{$mobile}',atmpwd='{$atmpwd}',email='{$email}',frozencash='0.00',cancash='{$cash}',referrals='{$referrals}',referr_name='{$referr_name}'";
		$xltm->SQL->Execute($query);
		
		//更新代理商会员数
		$xltm->SQL->Execute("update `user_agent` set mem_num=(select count(*) from `user_users` where agent='{$AgentID}') where username='{$AgentID}'");
		
		echo "<script>parent.ymPrompt.succeedInfo({title:'下线会员注册',message:'恭喜您注册帐号成功！用户名:{$username}，请登录系统后尽快进行在线充款，开始您的财富之旅！',handler:function(){location.href='referrals_member.php';}});</script>";
		exit();
	}
	elseif($type== 'chkuser')
	{
		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
		if(empty($username) || $username=="")
		{
			exit("false");
		}
		if($row = $xltm->SQL->GetRow("select * from `user_users` where username='{$username}'"))
		{
			exit('false');
		}
		else
		{
			exit('true');
		}
	}
	else
	{
		$xltm->tpls->LoadTemplate("./tmpfiles/referrals_member_register.html",false);
		$xltm->tpls->Show();
	}
}else {
	$xltm->gourl('','login.php');
}
?>