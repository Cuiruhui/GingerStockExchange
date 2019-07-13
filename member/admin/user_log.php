<?php
define('Load_Info', 1);
session_start();

include_once("./Lib/xltm.class.php");
//分页设置
if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';

$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

if($type=='remove')
{
	remove();
}


$PageSize = 15;
$inquiry="";
$search=($_REQUEST['search'])?$_REQUEST['search']:'';
if($search)
$inquiry .=" and user_name='$search'";
$user_type=($_REQUEST['user_type'])?$_REQUEST['user_type']:'';
if($user_type)
$inquiry .=" and user_type='$user_type'";
$log=$xltm->SQL->GetRows("SELECT * FROM user_log where id>0 $inquiry order by hit_time desc");
$xltm->tpls->LoadTemplate("./tmpfiles/user_log.html");
$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
$RecCnt =$xltm->tpls->MergeBlock('tr','array',$log);
$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
$showPage=($RecCnt>0)?1:'';
$xltm->tpls->show();
function log_event($BlockName,&$CurrRec,$RecNum){
	$utype=array('9'=>'会员','5'=>'管理员','1'=>'代理商');
	$CurrRec['user_types']=$utype[$CurrRec['user_type']];
}

//彻底删除
function remove()
{	
	global $xltm;
	$ids = $_REQUEST['ids'];
	//exit("delete from user_log where id in ({$ids})");
	$xltm->SQL->Execute("delete from user_log where id in ({$ids})");
	
	$xltm->showMsg("日志管理","删除成功！",true,"user_log.php");
}
?>