﻿<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
if($xltm->user_info['username']){
	if($_POST)
	{
		$list = array(
		'open_AM_Start',
		'open_AM_End',
		'open_PM_Start',
		'open_PM_End',
		'sell_AM_Start',
		'sell_AM_End',
		'sell_PM_Start',
		'sell_PM_End',

		'atm_AM_Start',
		'atm_AM_End',
		'atm_PM_Start',
		'atm_PM_End',

		'max_tries',
		'trust_num',
		'system_safeguard',
		'system_safeguard_direc',
		'user_regex',
		'agent_prefix',
		'agent_min',
		'agent_max',
		'user_login2_prefix',
		'user_login2_min',
		'user_login2_max',
		'user_login3_prefix',
		'user_login3_min',
		'user_login3_max',
		'user_member_prefix',
		'user_member_min',
		'user_member_max',
		'user_name_min',
		'user_name_max',
		'max_password',
		'min_password',
		'cookie_length',
		'cost_bull_max',
		'cost_sell_max',
		'cost_save_max',
		'cost_ret_max',
		'cost_save_day',
		'sel_max_time',
		'cost_sell_limit',
		'rest_filter',
		'buy_sd',
		'st_buy_sd',
		'turnover_min',
		'cost_exchange_rate',
		'lower_cash',
		'num_max',
		'num_min',
		'up_float',
		'down_float',
		'money_percent',
		'minmoney',
		'dc5',
		'dc6',
		'dc7',
		'dc8',
		'dc9',
		'dc_wan',
		'dc_wan2',
		'dc_wan3',
		'dc_tou',
		'dc_di',
		'baocang_precent',

		);
		foreach ($list as $value) {
			$newValue=$_POST[$value];
			$xltm->SQL->Execute("UPDATE user_config set value='{$newValue}' where name='{$value}'");
		}
		//print "<script type='text/javascript'>ymPrompt.succeedInfo({title:'系统设置',message:'保存系统设置信息成功！',handler:function(){self.location.href='system_setup.php';}});</script>";
		echo "<script>alert('保存系统设置信息成功！');location.href='system_setup.php';</script>";
		exit();
	}
	$xltm->tpls->LoadTemplate("./tmpfiles/system_Setup.html");
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>
