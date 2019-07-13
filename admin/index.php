<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
include_once("./Lib/xltm.class.php");
if($_GET['login']=='logout')
{
	$xltm->User->logout_user();
	exit;
}
if($xltm->user_info['username']){
	$user_name=$xltm->user_info['username'];
	$xltm->tpls->LoadTemplate("./tmpfiles/index.html");
	$xltm->tpls->Show();
}else {
	$xltm->gourl('请登录','login.php');
	//header("location:login.php");
}
?>