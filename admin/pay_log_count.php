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
$inquiry="";
$result = isset($_REQUEST['result']) ? $_REQUEST['result'] : '1';
$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',time()-30*24*3600);
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-d');

if($start_date>$end_date)
{
	$xltm->showMsg('充值管理查询','开始日期不能大于结束日期！');
}
$resultary = array('-1'=>'全部','1'=>'<font color=red>成功</font>','0'=>'<font color=gray>等待打款</font>');
if($result!='-1') $inquiry .=" and result='{$result}'";
$agent_list=$xltm->SQL->GetRows("SELECT username FROM `user_agent` where active='yes' order by username asc");
$paycount = array();
if($agent) //指定代理
{
	$sql = "SELECT sum(money) as totalmoney,agent FROM `payment` where agent='{$agent}' and add_time>='{$start_date} 0:0:0' and add_time<='{$end_date} 23:59:59' $inquiry";
	$pay = $xltm->SQL->GetRow($sql);
	if(!is_array($pay) || $pay['totalmoney']==0)
	{
		$pay['agent'] = $item['username'];
		$pay['totalmoney'] = 0; 
	}
	$pay['start_date'] = $start_date;
	$pay['end_date'] = $end_date;
	$pay['result'] = $resultary[$result];
	$paycount[] = $pay;
}
else
{
	foreach($agent_list as $item)
	{
		$sql = "SELECT agent,sum(money) as totalmoney FROM `payment` where agent='{$item['username']}' and add_time>='{$start_date} 0:0:0' and add_time<='{$end_date} 23:59:59' {$inquiry}";
		$pay = $xltm->SQL->GetRow($sql);
		if(!is_array($pay) || $pay['totalmoney']==0)
		{
			$pay['agent'] = $item['username'];
			$pay['totalmoney'] = 0; 
		}
		$pay['start_date'] = $start_date;
		$pay['end_date'] = $end_date;
		$pay['result'] = $resultary[$result];
		$paycount[] = $pay;
	}
}
$xltm->tpls->LoadTemplate("./tmpfiles/pay_log_count.html",false);
$xltm->tpls->MergeBlock('tr','array',$paycount);
$xltm->tpls->MergeBlock('ag','array',$agent_list);
$xltm->tpls->show();
?>