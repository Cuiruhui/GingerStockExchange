<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	if($type=='save')
	{
		$oldpwd = $_POST['oldpwd'];
		$password = $_POST['password'];
		$password_confirm = $_POST['password_confirm'];
		$atmpwd = $_POST['atmpwd'];
		//验证旧密码是否正确
		if($xltm->User->compare_passwords($oldpwd, $xltm->user_info['password'], $xltm->user_info['code']))
		{
			$new_activation_code=$xltm->User->generate_activation_code($xltm->user_info['username'],$password,$activation_code);
			$new_password=$xltm->User->generate_password_hash($password, $new_activation_code);
			$newPASS=($password)?$new_password:$xltm->user_info['password'];
			$code=($new_activation_code)?$new_activation_code:$xltm->user_info['code'];
			$qary = array(
				'password'=>$newPASS,
				'code'=>$code,
				'atmpwd'=>$atmpwd
			);
			$query = $xltm->array2str($qary);
			$xltm->SQL->Execute("update `user_users` set {$query} where username='{$xltm->user_info['username']}'");
			mb("修改密码信息成功！","w2pwd.php",1);
		}
		else
		{
			mb("原始密码不正确，请重新输入！","",0);
			exit();
		}

	}
	$xltm->tpls->LoadTemplate("./tmpfiles/w2pwd.html");
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>