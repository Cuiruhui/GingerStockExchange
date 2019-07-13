<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();

include_once("./Lib/xltm.class.php");
if($_GET['type']=='change')
{
	$new_activation_code=$xltm->User->generate_activation_code($_POST['username'],$_POST['password'],$activation_code);
	$new_password=$xltm->User->generate_password_hash($_POST['password'], $new_activation_code);
	$xltm->SQL->Execute("UPDATE `user_admin` set code='$new_activation_code',password='{$new_password}' where username='{$xltm->user_info['username']}'");
	$xltm->showMsg('修改密码','修改密码成功！',true,'change_password.php');
}
$xltm->tpls->LoadTemplate("./tmpfiles/change_password.html");
$xltm->tpls->Show();
?>