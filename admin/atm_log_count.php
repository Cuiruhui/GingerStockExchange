<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
//分页设置
if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
$inquiry="";
$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
$pay_type = isset($_REQUEST['pay_type']) ? $_REQUEST['pay_type'] : '1';
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',time()-30*24*3600);
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-d');

if($start_date>$end_date)
{
	$xltm->showMsg('充值管理查询','开始日期不能大于结束日期！');
}

if($pay_type!='999') $inquiry .=" and ispay='{$pay_type}'";
$statusary = array('999'=>'全部','1'=>'<font color=red>已打款</font>','0'=>'<font color=gray>未打款</font>','-1'=>'<font color=gray>已回退</font>');
$agent_list=$xltm->SQL->GetRows("SELECT username FROM `user_agent` where active='yes' order by username asc");

$atmcount = array();
if($agent) //指定代理
{
	$sql = "SELECT sum(money) as totalmoney,agent FROM `atmlog` where agent='{$agent}' and add_time>='{$start_date} 0:0:0' and add_time<='{$end_date} 23:59:59' $inquiry";
	$atm = $xltm->SQL->GetRow($sql);
	if(!is_array($atm) || $atm['totalmoney']==0)
	{
		$atm['agent'] = $agent;
		$atm['totalmoney'] = 0; 
	}
	$atm['start_date'] = $start_date;
	$atm['end_date'] = $end_date;
	$atm['status'] = $statusary[$pay_type];
	$atmcount[] = $atm;
}
else
{
	foreach($agent_list as $item)
	{
		$sql = "SELECT agent,sum(money) as totalmoney FROM `atmlog` where agent='{$item['username']}' and add_time>='{$start_date} 0:0:0' and add_time<='{$end_date} 23:59:59' {$inquiry}";
		$atm = $xltm->SQL->GetRow($sql);
		if(!is_array($atm) || $atm['totalmoney']==0)
		{
			$atm['agent'] = $item['username'];
			$atm['totalmoney'] = 0; 
		}
		$atm['start_date'] = $start_date;
		$atm['end_date'] = $end_date;
		$atm['status'] = $statusary[$pay_type];
		$atmcount[] = $atm;
	}
}
$xltm->tpls->LoadTemplate("./tmpfiles/atm_log_count.html",false);
$xltm->tpls->MergeBlock('ag','array',$agent_list);
$xltm->tpls->MergeBlock('tr','array',$atmcount);
$xltm->tpls->show();
?>