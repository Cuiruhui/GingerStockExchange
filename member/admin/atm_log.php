<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
//分页设置
if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
$PageSize = 20;
if($type=='pay')
{
	pay();
}
elseif($type=='del')
{
	del();
}
elseif($type=='remove')
{
	remove();
}
else
{
	$inquiry="";
	$search = isset($_REQUEST['search'])?$_REQUEST['search']:'';
	$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
	$pay_type = isset($_REQUEST['pay_type']) ? $_REQUEST['pay_type'] : '999';
	if($search)
	{
		$inquiry .=" and username like '%{$search}%'";
	}
	if($agent)
	{
		$inquiry .=" and agent='{$agent}'";
	}
	if($pay_type!='999')
	{
		$inquiry .=" and ispay='{$pay_type}'";
	}
	$atmlog=$xltm->SQL->GetRows("SELECT * FROM `atmlog` where id>0 $inquiry order by add_time desc");
	$agent_list=$xltm->SQL->GetRows("SELECT username FROM `user_agent` where active='yes' order by id");
	$xltm->tpls->LoadTemplate("./tmpfiles/atm_log.html",false);
	$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
	$xltm->tpls->MergeBlock('ag','array',$agent_list);
	$RecCnt =$xltm->tpls->MergeBlock('tr','array',$atmlog);
	$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
	$showPage=($RecCnt>0)?1:'';
	$xltm->tpls->show();
}

//彻底删除
function remove()
{	
	global $xltm;
	$ids = $_REQUEST['ids'];
	//exit("delete from `atmlog` where id in ({$ids})");
	$xltm->SQL->Execute("delete from `atmlog` where id in ({$ids})");
	
	$xltm->showMsg("提款管理","删除成功！",true,"atm_log.php");
}

//回退提款记录
function del()
{	
	global $xltm;
	$id = $_REQUEST['id'];
	if(!isset($id) || !is_numeric($id))
	{
		$xltm->showMsg("提款管理","请指定正确的ID值！",false,"back");
	}
	else
	{
		//判断股东是否有权限删除
		$atmlog = $xltm->SQL->GetRow("Select * from `atmlog` where id='{$id}'");
		if(!is_array($atmlog))
		{
			$xltm->showMsg("提款管理","找不到要回退的提款记录！",false,"back");
		}
		else if($atmlog['ispay']=='1') //已设置打款
		{
			$xltm->showMsg("提款管理","该笔提款申请已经打款，无法回退！",false,"back");
		}
		else if($atmlog['ispay']=='-1') //已回退
		{
			$xltm->showMsg("提款管理","该笔提款申请已经回退，请匆重复操作！",false,"back");
		}
		else
		{
			//修改用户的可用余额以及冻结资金
			$xltm->SQL->Execute("Update `user_users` set cash=cash+{$atmlog['money']},frozencash=frozencash-{$atmlog['money']} where username='".$atmlog['username']."'");
			//更新状态
			$xltm->SQL->Execute("update `atmlog` set ispay='-1',pay_time='{$xltm->sys_time}' where id='{$id}'");
			//删除提款纪录
			$xltm->SQL->Execute("delete from `atm` where  update_time='".date('Y-m-d')."' and username='".$atmlog['username']."'");

			$xltm->showMsg("提款管理","回退提款成功！",true,"atm_log.php");
		}
	}
}

//已打款
function pay()
{	
	global $xltm;
	$id = $_REQUEST['id'];
	if(!isset($id) || !is_numeric($id))
	{
		$xltm->showMsg("提款管理","请指定正确的ID值！",false,"back");
	}
	else
	{
		$atmlog = $xltm->SQL->GetRow("Select * from `atmlog` where id='{$id}'");
		if(!is_array($atmlog))
		{
			$xltm->showMsg("提款管理","找不到要打款的提款记录！",false,"back");
		}
		else if($atmlog['ispay']=='1')
		{
			$xltm->showMsg("提款管理","该笔提款申请已经打款！",false,"back");
		}
		else
		{
			$xltm->SQL->Execute("update `atmlog` set ispay='1',pay_time='{$xltm->sys_time}' where id='{$id}'");
			$xltm->SQL->Execute("Update `user_users` set frozencash=frozencash-{$atmlog['money']} where username='".$atmlog['username']."'");
			$xltm->showMsg("提款管理","设置提款状态成功！",true,"atm_log.php");
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
