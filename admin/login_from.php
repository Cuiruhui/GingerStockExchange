<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 2);
session_start();
include_once("./Lib/xltm.class.php");
$type=$_REQUEST['type'];
if($type=='logout')
{
	$xltm->User->logout_user();
}
if($_POST['username'] ){
	$xltm->User->login_user();
	$xltm->gourl('','index.php');
}
?>