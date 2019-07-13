<?php
define('Load_Info', 1);
session_start();

include_once("./Lib/xltm.class.php");
include_once("./safe.php");
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
			if(md5($_POST["safe"]) == $safe_mak){
			$xltm->SQL->Execute("insert into common set add_time='{$xltm->sys_time}',type='{$_POST['types']}',title='{$_POST['title']}',content='{$_POST['content']}'");
			$xltm->showMsg('新增公告','新增公告成功！！',true,'note.php');
			}else{
			
			$xltm->gourl('对不起,无权进行此操作!!','');
			
			}

		}
		if($type=='EditFrom')
		{	
	
			if(md5($_POST["safe"]) == $safe_mak){
			$xltm->SQL->Execute("UPDATE common set type='{$_POST['types']}',title='{$_POST['title']}',content='{$_POST['content']}' where id='{$_POST['id']}'");
			}else{
			$xltm->gourl('对不起,无权进行此操作!!','');
			
			}


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