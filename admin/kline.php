<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
$stockcode = isset($_GET['stockcode']) ? $_GET['stockcode'] : '600000';
$stocktype = isset($_GET['stocktype']) ? $_GET['stocktype'] : 'sh';
$xltm->tpls->LoadTemplate("./tmpfiles/kline.html");
$xltm->tpls->Show();
?>