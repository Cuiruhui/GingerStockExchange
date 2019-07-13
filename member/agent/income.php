<?php
include_once('global.php');
$xltm->user_log(1,"浏览代理收益");

$demo = 0;
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date("Y-m-d",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : $xltm->sys_date;
if($start_date>$end_date)
{
	$xltm->errorInfo('代理收益','开始日期必须小于等于结束日期！','history.go(-1);');
}
$where = '';
if($start_date && $end_date)
{
	$where .=" and income_date>='{$start_date}' and income_date<='{$end_date}'";
}
$rows=$xltm->SQL->GetRows("select * from `agent_income` where agent='{$xltm->user_info['username']}'{$where} order by income_date asc");
$xltm->tpls->LoadTemplate("./tmpfiles/income.html",false);
$xltm->tpls->MergeBlock('tr','array',$rows);
$xltm->tpls->Show();

?>