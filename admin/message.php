<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
$type=isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
if($type=='del')
{
	$xltm->SQL->Execute("DELETE FROM `message` where id='{$_GET['id']}'");
	$xltm->showMsg('删除短信息','删除短信息成功！',true,'message.php');
}
elseif($type=='remove')
{
	$ids = $_REQUEST['ids'];
	$xltm->SQL->Execute("delete from `message` where id in ({$ids})");
	
	$xltm->showMsg("短信管理","删除成功！",true,"message.php");
}
else if($type=='read')
{
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	//if($row=$xltm->SQL->GetRow("select * from `message` where tousername='administrator' and id='{$id}'"))
	if($row=$xltm->SQL->GetRow("select * from `message` where id='{$id}'"))
	{
		//更新阅读状态
		$xltm->SQL->Execute("update `message` set isread='1' where id='{$id}'");
		$xltm->tpls->LoadTemplate("./tmpfiles/message_read.html",false);
		$xltm->tpls->show();
	}
	else
	{
		echo "<script>alert('不存在您要阅读的短信息！');window.parent.ymPrompt.doHandler('error',true);</script>";
		exit();
	}
}else if($type=='add')
{
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	if($row=$xltm->SQL->GetRow("select * from `user_users` where id='{$id}'"))
	{
		$xltm->tpls->LoadTemplate("./tmpfiles/message_add.html",false);
		$xltm->tpls->show();
	}
	else
	{
		echo "<script>alert('该接收人不存在！');history.go(-1);</script>";
		exit();
	}
}else if($type=='addok')
{
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
	$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
	$content = isset($_REQUEST['content']) ? $_REQUEST['content'] : '';

	if($row=$xltm->SQL->GetRow("select * from `user_users` where id='{$id}'"))
	{
		$xltm->SQL->Execute("insert into `message` set username='管理员发送',tousername='{$row["username"]}',title='{$title}',content='{$content}',isread='0',add_time='{$xltm->sys_time}'");
		
		echo "<script>alert('新增成功！');location.href='message.php';</script>";
		exit();
	}
	else
	{
		echo "<script>alert('该接收人不存在！');history.go(-1);</script>";
		exit();
	}
}
else
{
	$PageNum =isset($_REQUEST['PageNum'])?$_REQUEST['PageNum']:1;
	$RecCnt =isset($_REQUEST['RecCnt'])?intval($_REQUEST['RecCnt']):'-1';
	$PageSize = 20;
	//$message=$xltm->SQL->GetRows("SELECT * FROM `message` where tousername='administrator' order by isread asc,add_time desc");
	$message=$xltm->SQL->GetRows("SELECT * FROM `message` order by isread asc,add_time desc");
	$xltm->tpls->LoadTemplate("./tmpfiles/message.html");
	$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
	$RecCnt = $xltm->tpls->MergeBlock('tr','array',$message);
	$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
	$showPage=($RecCnt>0)?1:'';
	$xltm->tpls->show();
}
?>