<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
ini_set('display_errors','On');
//分页设置
if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
$PageNum =isset($_GET['PageNum'])?$_GET['PageNum']:1;
$RecCnt =(isset($_REQUEST['RecCnt']))?intval($_REQUEST['RecCnt']):'-1';
$PageSize = isset($_REQUEST['PageSize']) && is_numeric($_REQUEST['PageSize']) ? intval($_REQUEST['PageSize']) : 20;
$inquiry="";
$search = isset($_REQUEST['search'])?$_REQUEST['search']:'';
$id = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$result = isset($_REQUEST['result']) ? $_REQUEST['result'] : '-1';
$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',time()-30*24*3600);
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-d');
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

if($type=='remove')
{
	remove();
}

if($start_date>$end_date)
{
	$xltm->showMsg('充值管理查询','开始日期不能大于结束日期！');
}

if($search) $inquiry .=" and a.username like '%{$search}%'";
if($id) $inquiry .=" and a.payid='{$id}'";
if($result!='-1') $inquiry .=" and a.result='{$result}'";
if($agent) $inquiry .= " and a.agent='{$agent}'";

$paylog=$xltm->SQL->GetRows("SELECT a.*,b.name as payname FROM `payment` a left join `pay_config` b on a.payid=b.id where a.add_time>='{$start_date} 0:0:0' and a.add_time<='{$end_date} 23:59:59' $inquiry order by a.add_time desc");
$agent_list=$xltm->SQL->GetRows("SELECT username FROM `user_agent` where active='yes' order by id");
$xltm->tpls->LoadTemplate("./tmpfiles/pay_log.html",false);
$xltm->tpls->MergeBlock('ag','array',$agent_list);
$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
$RecCnt =$xltm->tpls->MergeBlock('tr','array',$paylog);
//print_r($paylog);
$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
$showPage=($RecCnt>0)?1:'';
$xltm->tpls->show();

//彻底删除
function remove()
{	
	global $xltm;
	$ids = $_REQUEST['ids'];
	//exit("delete from `payment` where id in ({$ids})");
	$xltm->SQL->Execute("delete from `payment` where id in ({$ids})");
	
	$xltm->showMsg("充值管理","删除成功！",true,"pay_log.php");
}

?>
