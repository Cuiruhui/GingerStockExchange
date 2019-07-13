<?php
define('Load_Info', 1);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username'])
{
	$xiaxian_menu = '';
	if($xltm->user_info['referrals']==1)
	{
		$xiaxian_menu = '<h5>下线管理</h5><ul><li><a href="referrals_member.php">帐号列表</a></li><li><a href="referrals_member_register.php">注册帐号</a></li><li><a href="referrals_sell.php">下线平仓</a></li><li><a href="referrals_deal.php">下线持仓</a></li></ul>';
	}
	$xltm->tpls->LoadTemplate("./tmpfiles/left.html",false);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>
