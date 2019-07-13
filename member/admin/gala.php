<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
$type=$_REQUEST['type'];
switch ($type)
{
	case 'add':
		$xltm->tpls->LoadTemplate("./tmpfiles/gala_add.html");
		$xltm->tpls->show();	
		break;
	case 'edit':
		$edit=$xltm->SQL->GetRow("select * from `gala_config` where id='{$_GET['id']}'");
		$xltm->tpls->LoadTemplate("./tmpfiles/gala_edit.html");
		$xltm->tpls->show();	
		break;
	default:
		if($type=='AddFrom')
		{
			$xltm->SQL->Execute("insert into `gala_config` set gala_date='{$_POST['gala_date']}',Description='{$_POST['Description']}'");
			$xltm->ShowPrompt("新增成功.","",1);
		}
		if($type=='EditFrom')
		{
			$xltm->SQL->Execute("UPDATE `gala_config` set gala_date='{$_POST['gala_date']}',Description='{$_POST['Description']}' where id='{$_POST['id']}'");
		}
		if($type=='del')
		{
			$xltm->SQL->Execute("DELETE FROM gala_config where id='{$_GET['id']}'");
		}
		$gala=$xltm->SQL->GetRows("SELECT * FROM gala_config order by gala_date desc");
		$xltm->tpls->LoadTemplate("./tmpfiles/gala.html");
		$xltm->tpls->MergeBlock('tr','array',$gala);
		$xltm->tpls->show();	
		break;
}
?>