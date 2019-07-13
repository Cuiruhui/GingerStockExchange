<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();

include_once("./Lib/xltm.class.php");
$type=$_REQUEST['type'];
switch ($type)
{
	case 'add':
		$xltm->tpls->LoadTemplate("./tmpfiles/note_add.html");
		$xltm->tpls->show();
		break;
	case 'edit':
		$edit=$xltm->SQL->GetRow("select * from common where id='{$_GET['id']}'");
		$xltm->tpls->LoadTemplate("./tmpfiles/note_edit.html");
		$xltm->tpls->show();
		break;
	default:
		if($type=='AddFrom')
		{
			$xltm->SQL->Execute("insert into common set add_time='{$xltm->sys_time}',type='{$_POST['types']}',title='{$_POST['title']}',content='{$_POST['content']}'");
			$xltm->showMsg('新增公告','新增公告成功！！',true,'note.php');
		}
		if($type=='EditFrom')
		{
			$xltm->SQL->Execute("UPDATE common set type='{$_POST['types']}',title='{$_POST['title']}',content='{$_POST['content']}' where id='{$_POST['id']}'");
		}
		if($type=='del')
		{
			$xltm->SQL->Execute("DELETE FROM common where id='{$_GET['id']}'");
		}
		$gala=$xltm->SQL->GetRows("SELECT * FROM common order by add_time desc");
		$xltm->tpls->LoadTemplate("./tmpfiles/note.html");
		$xltm->tpls->MergeBlock('tr','array',$gala);
		$xltm->tpls->show();
		break;
}
?>