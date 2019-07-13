<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
if($xltm->user_info['username']){
	if($_POST)
	{
		$newValue=htmlspecialchars($_POST['system_rule']);
		$xltm->SQL->Execute("UPDATE system_text set value='{$newValue}' where name='system_rule'");
		print "<script type='text/javascript'>parent.ymPrompt.succeedInfo({title:'系统设置',message:'保存系统设置信息成功！',handler:function(){self.location.href='system_rule.php';}});</script>";
		exit();
	}
	
	
	$row = $xltm->SQL->GetRow("Select * from system_text where name='system_rule'");
	$system_rule = htmlspecialchars_decode($row['value']);
	
	$xltm->tpls->LoadTemplate("./tmpfiles/system_rule.html");
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}
?>