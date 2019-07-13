<?php
include_once ('global.php');
$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
if($type=='del')
{
	del();
}
else if($type=='pay')
{
	pay();
}
$xltm->user_log(1,"浏览提款记录列表");
$inquiry = "";
$ispay=isset($_REQUEST['ispay']) ? $_REQUEST['ispay']:'999';
$search =isset($_REQUEST['search']) ? $_REQUEST['search']:'';
if($search) $inquiry .= " and username like '%{$search}%'";
if($ispay!='999') $inquiry .=" and ispay='$ispay'";
//分页设置
if(!isset($_GET))$_GET = &$HTTP_GET_VARS;
$PageNum = (isset($_GET['PageNum'])) ? $_GET['PageNum']:1;
$RecCnt = (isset($_GET['RecCnt'])) ? intval($_GET['RecCnt']):-1;
$PageSize = 20;
//End page
$atmlog = $xltm->SQL->GetRows("SELECT * FROM `atmlog` where agent='{$MyUser}' $inquiry order by add_time desc");
$totalrecord=count($atmlog);
$xltm->tpls->LoadTemplate("./tmpfiles/user_atm.html",false);
$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
$RecCnt = $xltm->tpls->MergeBlock('tr','array',$atmlog);
$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
$showPage = ($RecCnt > 0) ? 1:'';
$xltm->tpls->Show();
break;

//回退提款记录
function del()
{	
	global $xltm,$MyUser;
	$id = $_REQUEST['id'];
	if(!isset($id) || !is_numeric($id))
	{
		$xltm->errorInfo("回退提款申请","请指定正确的ID值！","history.go(-1);");
	}
	else
	{
		//判断股东是否有权限删除
		$atmlog = $xltm->SQL->GetRow("Select * from `atmlog` where id='{$id}'");
		if(!is_array($atmlog))
		{
			$xltm->errorInfo("回退提款申请","找不到要回退的提款记录！","history.go(-1);");
		}
		else if($atmlog['agent']!=$MyUser)
		{
			$xltm->errorInfo("回退提款申请","你无权限回退该条提款记录！","history.go(-1);");
		}
		else if($atmlog['ispay']=='1') //已设置打款
		{
			$xltm->errorInfo("回退提款申请","该笔提款申请已经打款，无法回退！","history.go(-1);");
		}
		else if($atmlog['ispay']=='-1') //已回退
		{
			$xltm->errorInfo("提款管理","该笔提款申请已经回退，请匆重复操作！","history.go(-1);");
		}
		else
		{
			$xltm->user_log(1,"回退ID=".$id."的用户提款记录");
			//修改用户的可用余额以及冻结资金
			$xltm->SQL->Execute("Update `user_users` set cash=cash+{$atmlog['money']},frozencash=frozencash-{$atmlog['money']} where username='".$atmlog['username']."'");
			//更新状态
			$xltm->SQL->Execute("update `atmlog` set ispay='-1',pay_time='{$xltm->sys_time}' where id='{$id}'");
			$xltm->succeedInfo("回退提款申请","回退提款成功！","self.location.href='user_atm.php';");
		}
	}
}

//已打款
function pay()
{	
	global $xltm,$MyUser;
	$id = $_REQUEST['id'];
	if(!isset($id) || !is_numeric($id))
	{
		$xltm->errorInfo("打款","请指定正确的ID值！","history.go(-1);");
	}
	else
	{
		//判断股东是否有权限删除
		$atmlog = $xltm->SQL->GetRow("Select * from `atmlog` where id='{$id}'");
		if(!is_array($atmlog))
		{
			$xltm->errorInfo("打款","找不到要回退的提款记录！","history.go(-1);");
		}
		else if($atmlog['agent']!=$MyUser)
		{
			$xltm->errorInfo("打款","你无权修改该条提款记录状态！","history.go(-1);");
		}
		else if($atmlog['ispay']=='1')
		{
			$xltm->errorInfo("打款","该条提款记录已设置成已打款！","history.go(-1);");
		}
		else
		{
			$xltm->user_log(1,"设置ID=".$id."的用户提款状态为已打款");
			$xltm->SQL->Execute("update `atmlog` set ispay='1',pay_time='{$xltm->sys_time}' where id='{$id}'");
			$xltm->SQL->Execute("Update `user_users` set frozencash=frozencash-{$atmlog['money']} where username='".$atmlog['username']."'");
			$xltm->succeedInfo("打款","设置打款状态成功！","self.location.href='user_atm.php';");
		}
	}
}

function event_row($BlockName,&$CurrRec,$RecNum)
{
	if($CurrRec['ispay']=='1')
	{
		$CurrRec['status'] = '<font color=red>已打款</font>';
		$CurrRec['disabled'] = ' disabled';
	}
	else if($CurrRec['ispay']=='-1')
	{
		$CurrRec['status'] = '<font color=gray>回退</font>';
		$CurrRec['disabled'] = ' disabled';
	}
	else
	{
		$CurrRec['status'] = '未处理';
		$CurrRec['disabled'] = '';
	}
}
?>
