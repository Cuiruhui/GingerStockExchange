<?php
define('Load_Info', 1);
session_start();

include_once("./Lib/xltm.class.php");
$type=$_REQUEST['type'];
switch ($type)
{
	case 'add':
		$xltm->tpls->LoadTemplate("./tmpfiles/xinwen_add.html");
		$xltm->tpls->show();
		break;
	case 'edit':
		$edit=$xltm->SQL->GetRow("select * from newcaijin where id='{$_GET['id']}'");
		$xltm->tpls->LoadTemplate("./tmpfiles/xinwen_edit.html");
		$xltm->tpls->show();
		break;
	default:
		if($type=='AddFrom')
		{
			$xltm->SQL->Execute("insert into newcaijin set add_time='{$xltm->sys_time}',type='{$_POST['types']}',title='{$_POST['title']}',content='{$_POST['MyTextarea']}'");
			$xltm->gourl("新增新闻成功.","xinwen.php");
		}
		if($type=='EditFrom')
		{
			$xltm->SQL->Execute("UPDATE newcaijin set type='{$_POST['types']}',title='{$_POST['title']}',content='{$_POST['MyTextarea']}' where id='{$_POST['id']}'");
		}
		if($type=='del')
		{
			$xltm->SQL->Execute("DELETE FROM newcaijin where id='{$_GET['id']}'");
		}
		$gala=$xltm->SQL->GetRows("SELECT * FROM newcaijin order by add_time desc");
		$xltm->tpls->LoadTemplate("./tmpfiles/xinwen.html");
		$xltm->tpls->MergeBlock('tr','array',$gala);
		$xltm->tpls->show();
		break;
}
?>