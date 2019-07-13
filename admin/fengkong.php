<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
if($xltm->user_info['username']){
	if($_POST)
	{
		$list = array(
		'more9',
		
		

		);
		foreach ($list as $value) {
			$newValue=$_POST[$value];
			$xltm->SQL->Execute("UPDATE user_config set value='{$newValue}' where name='{$value}'");
		}
		$xltm->gourl('保存成功','fengkong.php');
		exit();
	}
	$xltm->tpls->LoadTemplate("./tmpfiles/fengkong.html");
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>