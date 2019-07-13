<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
require_once("./Lib/xltm.class.php");
if($xltm->user_info['username']=='')
{
	exit('请先登录!');
}
else
{
	$xltm->tpls->LoadTemplate("./tmpfiles/left.html");
	$xltm->tpls->Show();
}
?>