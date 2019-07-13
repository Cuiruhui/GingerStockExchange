<?php
header('P3P: CP="ALL ADM DEV PSAi COM OUR OTRo STP IND ONL"');
define('Load_Info', 2);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$type = isset($_GET['type']) ? $_GET['type'] : '';
if($type=='chkvalidate')
{
	if(!isset($_SESSION[$session_prefix.'validate']))
	{
		exit("false");
	}
	$validate=isset($_GET['validate'])?$_GET['validate']:'';
	if(empty($validate))
	{
		exit("false");
	}
	elseif(strtolower($_SESSION[$session_prefix.'validate'])!=strtolower($validate))
	{
		exit("false");
	}
	else
	{
		exit("true");
	}
}
if($type=='login')
{
	//是否加载验证码
	if(isset($_SESSION[$session_prefix.'validate']) && isset($_POST['validate']))
	{
		if(strtolower($_SESSION[$session_prefix.'validate'])!=strtolower($_POST['validate']))
		{
			$xltm->gourl('验证码不正确.','');
			exit();
		}
	}
	$client = isset($_REQUEST['client']) ? $_REQUEST['client'] : 'false';
	if($_POST['username'] && $_POST['password']){
		$xltm->User->login_user();
		$ref_domain = $_POST['ref_domain'];
		if(empty($ref_domain))
		{
			$ref_domain=getref_domain();
		}
		
		exit("<script>top.location.href='http://{$ref_domain}/clause.php?client={$client}';</script>");
	}else {
		$xltm->gourl("请输入账号和密码.","");
	}
}
if($type=='logout') //注销
{
	$ref_domain = getref_domain();
	$xltm->User->logout_user();
	$client = isset($_REQUEST['client']) ? $_REQUEST['client'] : 'false';
	if($client=='true')
	{
		header("location:index.php");
	}
	else
	{
		header("location:http://{$ref_domain}/");
	}
	
	exit();
}
if($type=='disagree') //不同意
{
	$ref_domain = getref_domain();
	$xltm->User->logout_user();
	header("location:http://{$ref_domain}/");
	exit();
}
if($type=='agree') //同意
{
	$ref_domain = getref_domain();
	header("location:http://{$ref_domain}/trade.php");
	exit();
}

function getref_domain()
{
	global $xltm;
	$ref_domain = '';
	if($xltm->user_info['ref_domain'])
	{
		$ref_domain=$xltm->user_info['ref_domain'];
	}
	else
	{
		$agent = $xltm->user_info['agent'];
		if($row=$xltm->SQL->GetRow("select * from `user_agent` where username='{$agent}'"))
		{
			$ref_domain1 = explode(',',$row['domain']);
			$ref_domain = $ref_domain1[0];
		}
		else
		{
			$ref_domain = $_SERVER['HTTP_HOST'];
		}
	}
	$ref_domain = $_SERVER['HTTP_HOST'];
	return $ref_domain;
}
?>