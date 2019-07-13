<?php
define('Load_Info', 1);
session_start();
include_once ('./Lib/xltm.class.php');
$MyUser = $xltm->user_info['username'];
$user_info=$xltm->user_info;
$config=$xltm->config;
$inf=0;
//最大限制
$Max_cost_bull = $config['cost_bull_max'];
$Max_cost_sell = $config['cost_sell_max'];
$Max_cost_save = $config['cost_save_max'];
?>