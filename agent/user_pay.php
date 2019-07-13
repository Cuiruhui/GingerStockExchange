<?php
include_once ('global.php');
$xltm->user_log(1,"浏览支付记录列表");
$inquiry = "";
$result=isset($_REQUEST['result']) ? $_REQUEST['result']:'-1';
$search = isset($_REQUEST['search']) ? $_REQUEST['search']:'';
if($search) $inquiry .= " and username like '%$search%'";
if($result>-1) $inquiry .=" and result='$result'";
//分页设置
if(!isset($_GET))$_GET = &$HTTP_GET_VARS;
$PageNum = (isset($_GET['PageNum'])) ? $_GET['PageNum']:1;
$RecCnt = (isset($_GET['RecCnt'])) ? intval($_GET['RecCnt']):-1;
$PageSize = 20;
//End page
$pays = $xltm->SQL->GetRows("SELECT * FROM `payment` where agent='{$MyUser}' $inquiry order by add_time desc");
$totalrecord=count($pays);
$xltm->tpls->LoadTemplate("./tmpfiles/user_pay.html");
$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
$RecCnt = $xltm->tpls->MergeBlock('tr','array',$pays);
$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
$showPage = ($RecCnt > 0) ? 1:'';
$xltm->tpls->Show();
?>