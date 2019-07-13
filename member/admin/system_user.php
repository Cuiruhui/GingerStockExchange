<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
include_once("./safe.php");
if($xltm->user_info['username']){
	$type=isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
	switch ($type)
	{
		case 'add':
			$xltm->tpls->LoadTemplate("./tmpfiles/system_user_add.html");
			$xltm->tpls->Show();
			break;
		case 'edit':
			$Edit=$xltm->SQL->GetRow("SELECT * FROM user_admin WHERE id='{$_GET['id']}'");
			$xltm->tpls->LoadTemplate("./tmpfiles/system_user_edit.html");
			$xltm->tpls->Show();
			break;
		case 'check_username':
			$validate_username=$xltm->User->validate_username($_GET['username']);
			if($validate_username && !$User=$xltm->SQL->GetRow("SELECT * FROM user_admin WHERE username='{$_GET['username']}'"))
			echo 'true';
			else
			echo 'false';
			break;
		case 'active':
			$User=$xltm->SQL->GetRow("SELECT active FROM user_admin WHERE id='{$_GET['id']}'");
			if($User['active']=='yes')
			{
				$up='no';
				$out='<font color="#FF9900">启用</font>';
			}else {
				$up='yes';
				$out='停用';
			}
			$xltm->SQL->Execute("UPDATE user_admin set active='$up' where id='{$_GET['id']}'");
			echo $out;
			break;
		case 'Ublocked':
			$User=$xltm->SQL->GetRow("SELECT blocked FROM user_admin WHERE id='{$_GET['id']}'");
			if($User['blocked']=='yes')
			{
				$up='no';
				$out='<font color="#FF0000">锁定</font>';
			}else {
				$up='yes';
				$out='<font color="#FF9900">解锁</font>';
			}
			$xltm->SQL->Execute("UPDATE user_admin set blocked='$up',tries='0' where id='{$_GET['id']}'");
			echo $out;
			break;
		default:
			if($type=='regsubmit')
			{
				if(md5($_POST["safe"]) == $safe_mak){
				$new_activation_code=$xltm->User->generate_activation_code($_POST['username'],$_POST['password'],$activation_code);
				$new_password=$xltm->User->generate_password_hash($_POST['password'], $new_activation_code);
				$Int=array(
				'username'=>$_POST['username'],
				'password'=>$new_password,
				'code'=>$new_activation_code,
				'active'=>'yes',
				'alias'=>$_POST['alias'],
				'regdate'=>$xltm->sys_time
				);
				$insert =$xltm->array2str($Int);
				$xltm->SQL->Execute("insert into user_admin set $insert");
				$xltm->showMsg('管理员帐号管理',"新增管理员帐号成功!",true,'system_user.php');
				}else{
				
					$xltm->showMsg('管理员帐号管理',"你无权添加管理员!",true,'system_user.php');
				}
			}
			if($type=='del')
			{
				$xltm->SQL->Execute("DELETE FROM user_admin where id='{$_GET['id']}'");
				$xltm->showMsg('管理员帐号管理',"删除管理员帐号成功!",true,'system_user.php');
			}
			if($type=='editsubmit')
			{
				$Edit=$xltm->SQL->GetRow("SELECT * FROM user_admin WHERE id='{$_POST['id']}'");
				if($_POST['password'])
				{
					$new_activation_code=$xltm->User->generate_activation_code($Edit['username'],$_POST['password'],$activation_code);
					$new_password=$xltm->User->generate_password_hash($_POST['password'], $new_activation_code);
				}
				$newPASS=($_POST['password'])?$new_password:$Edit['password'];
				$code=($new_activation_code)?$new_activation_code:$Edit['code'];
				$xltm->SQL->Execute("UPDATE user_admin set code='$code',alias='{$_POST['alias']}',password='{$newPASS}' where id='{$_POST['id']}'");
			}
			$inquiry="";
			$search=isset($_REQUEST['search'])?$_REQUEST['search']:'';
			if($search)
			$inquiry .=" and username='$search'";
			$Order=isset($_REQUEST['Order'])?$_REQUEST['Order']:'username';
			$By=isset($_REQUEST['By'])?$_REQUEST['By']:'asc';
			// Default value
			if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
			$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
			$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
			$PageSize = 20;
			$AdminUser=$xltm->SQL->GetRows("SELECT * FROM user_admin where id>0 $inquiry order by $Order $By");
			$xltm->tpls->LoadTemplate("./tmpfiles/system_user.html");
			$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
			$RecCnt =$xltm->tpls->MergeBlock('au','array',$AdminUser);
			$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
			$xltm->tpls->Show();
			break;
	}
}else {
	$xltm->gourl('','login.php');
}

function list_event($BlockName,&$CurrRec,$RecNum){
	global $xltm;
	if($CurrRec['username']==$xltm->user_info['username'])
	{
		$CurrRec['canlock']=" style='display:none;'";
		$CurrRec['canstop']=" style='display:none;'";
		$CurrRec['candel']=" style='display:none;'";
	}
	else
	{
		$CurrRec['canlock']="";
		$CurrRec['canstop']="";
		$CurrRec['candel']="";
	}
	$CurrRec['onlineTime']=($CurrRec['last_action']>(time()-180))?'<font color="#FF0000">在线</font>':'离线';
	$CurrRec['user_app']=($CurrRec['active']=='yes')?'停用':'<font color="#FF9900">启用</font>';
	$CurrRec['Ublocked']=($CurrRec['blocked']=='yes')?'<font color="#FF9900">解锁</font>':'<font color="#FF0000">锁定</font>';
}
?>