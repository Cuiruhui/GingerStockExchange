<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
include_once("./safe.php");
$type=isset($_REQUEST['type'])?$_REQUEST['type']:'';
switch ($type)
{
	case 'add':
		$dgd = $xltm->SQL->GetRows("select username from `user_agent` order by id asc");
		$xltm->tpls->LoadTemplate("./tmpfiles/pay_interface_add.html");
		$xltm->tpls->MergeBlock('tp','array',$dgd);
		$xltm->tpls->show();
		break;
	case 'edit':
		$edit=$xltm->SQL->GetRow("select * from `pay_config` where id='{$_GET['id']}'");
		$dgd = $xltm->SQL->GetRows("select username from `user_agent` order by id asc");
		$xltm->tpls->LoadTemplate("./tmpfiles/pay_interface_edit.html");
		$xltm->tpls->MergeBlock('tp','array',$dgd);
		$xltm->tpls->show();
		break;
	default:
		if($type=='AddFrom')
		{
			if(md5($_POST["safe"]) == $safe_mak){
			$agent = $_POST['agent'];
			$isdefault = is_numeric($_POST['isdefault']) ? intval($_POST['isdefault']) : 0;
			if($isdefault==1)
			{
				$xltm->SQL->Execute("Update `pay_config` set isdefault='0' where agent='{$agent}'");
			}
			$xltm->SQL->Execute("insert into `pay_config` set add_time='{$xltm->sys_time}',type='{$_POST['types']}',accounttype='{$_POST['accounttype']}',payinterfacename='{$_POST['payinterfacename']}',agent='{$_POST['agent']}',name='{$_POST['name']}',payee='{$_POST['payee']}',account='{$_POST['account']}',bankname='{$_POST['bankname']}',secretkey='{$_POST['secretkey']}',active='{$_POST['active']}',connecturl='{$_POST['connecturl']}',callbackurl='{$_POST['callbackurl']}',remark='{$_POST['remark']}',merid='{$_POST['merid']}',isdefault='{$_POST['default']}'");
			$xltm->showMsg('支付接口管理',"添加成功!",true,'pay_interface.php');
			}else{
			
		$xltm->showMsg('支付接口管理',"你无权管理接口!",false,'pay_interface.php');
			}


		}
		else if($type=='EditFrom')
		{
		if(md5($_POST["safe"]) == $safe_mak){
			$agent = $_POST['agent'];
			$isdefault = is_numeric($_POST['isdefault']) ? intval($_POST['isdefault']) : 0;
			if($isdefault==1)
			{
				$xltm->SQL->Execute("Update `pay_config` set isdefault='0' where agent='{$agent}'");
			}
			$xltm->SQL->Execute("UPDATE `pay_config` set type='{$_POST['types']}',accounttype='{$_POST['accounttype']}',payinterfacename='{$_POST['payinterfacename']}',agent='{$_POST['agent']}',name='{$_POST['name']}',payee='{$_POST['payee']}',account='{$_POST['account']}',bankname='{$_POST['bankname']}',secretkey='{$_POST['secretkey']}',active='{$_POST['active']}',connecturl='{$_POST['connecturl']}',callbackurl='{$_POST['callbackurl']}',remark='{$_POST['remark']}',merid='{$_POST['merid']}',isdefault='{$_POST['isdefault']}' where id='{$_POST['id']}'");
	
			$xltm->showMsg('支付接口管理',"编辑成功!",true,'pay_interface.php');

			}else{
			
			$xltm->showMsg('支付接口管理',"你无权管理接口!",false,'pay_interface.php');
				

			}


		}
		else if($type=='del')
		{
			$xltm->SQL->Execute("DELETE FROM `pay_config` where id='{$_GET['id']}'");
		}
		else if($type=='clone')
		{
			if($row = $xltm->SQL->GetRow("select type,accounttype,payinterfacename,name,account,agent,bankname,secretkey,active,connecturl,callbackurl,remark,merid,payee,add_time,isdefault from `pay_config` where id='{$_GET['id']}'"))
			{
				$row['name'] .= ' 副本';
				$row['add_time']=$xltm->sys_time;
				$query = $xltm->array2str($row);
				$xltm->SQL->Execute("Insert Into `pay_config` set {$query}");
			}
		}
		$pays=$xltm->SQL->GetRows("SELECT a.*,(select sum(money) from `payment` where payid=a.id and result=1) as totalmoney FROM `pay_config` a order by a.add_time desc");
		$xltm->tpls->LoadTemplate("./tmpfiles/pay_interface.html");
		$xltm->tpls->MergeBlock('tr','array',$pays);
		$xltm->tpls->show();
		break;
}

function list_event($BlockName,&$CurrRec,$RecNum){
	$CurrRec['totalmoney']=!is_numeric($CurrRec['totalmoney']) ? 0 :$CurrRec['totalmoney'];
}
?>
