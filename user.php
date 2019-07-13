<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$type = isset($_GET['type']) ? $_GET['type'] : '';
	if($type=='save')
	{
		$qary = array(
			'mobile'=>$_POST['mobile'],
			'atmpwd'=>$_POST['atmpwd'],
			'qq'=>$_POST['qq']
		);
		$query = $xltm->array2str($qary);
		$xltm->SQL->Execute("update `user_users` set {$query} where username='{$xltm->user_info['username']}'");
		mb("更新用户信息成功！","user.php",1);
	}
	$cancash = round($xltm->AvailableCash(),2);
	$money = round($cancash * $xltm->config['cost_exchange_rate'],2);
	$cost_bull = $xltm->user_info['cost_bull']*1000;
	$cost_sell = $xltm->user_info['cost_sell']*1000;
	$cost_save = $xltm->user_info['cost_save']*1000;
	$xltm->tpls->LoadTemplate("./tmpfiles/user.html");
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>