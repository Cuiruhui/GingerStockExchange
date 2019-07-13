<?php
define('Load_Info', true);
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once("./Lib/xltm.class.php");
//$currentusername = $xltm->user_info['username'];
//$rows=$xltm->SQL->GetRows("select * from `payment`");
//print_r(count($rows));
//$xltm->SQL->Execute("insert into `payment` set orderno='{$_REQUEST['orderno']}',agent='{$_REQUEST['agent']}',username='{$currentusername}',money='{$_REQUEST['money']}',add_time='{$_REQUEST['add_time']}'");
//update payment set payment.cur = (select user_users.alias from user_users where user_users.username = payment.username) where exists(select 1 from user_users where user_users.username = payment.username);
