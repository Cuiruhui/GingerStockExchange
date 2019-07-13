<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?
define('Load_Info', 0);
session_start();
include_once("./Lib/xltm.class.php");
$mem_list=$xltm->SQL->GetRows("select distinct (username) from user_users");
foreach ($mem_list as $k)
{
	print_r($k);
	echo "会员账号:".$k['username']."<br>";
}
?>