<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	if($xltm->user_info['referrals']==0)
	{
		echo "<script>parent.ymPrompt.errorInfo({title:'下线会员',message:'对不起，你没有开通下线会员权限！',handler:function(){self.location.href='stock_lists.php';}});</script>";
		exit();
	}
	$today = date('Y-m-d',time());
	$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',mktime(0,0,0,1,1,2010));
	$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : $today;
	$PageSize = 20;
	$PageNum =isset($_REQUEST['PageNum'])?$_REQUEST['PageNum']:1;
	$RecCnt =isset($_REQUEST['RecCnt'])?intval($_REQUEST['RecCnt']):'-1';
	$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
	if($start_date>$end_date)
	{
		echo "<script>parent.ymPrompt.errorInfo({title:'下线会员',message:'对不起，开始日期不能大于结束日期！',handler:function(){history.go(-1);}});</script>";
		exit();
	}
	$query = "select * from `user_users` where regdate>='{$start_date} 0:0:0' and regdate<='{$end_date} 23:59:59' and referr_name='{$xltm->user_info['username']}'";
	if($username)
	{
		$query .=" and username like '%{$username}%'";
	}
	$query .= " order by regdate desc";
	$rows = $xltm->SQL->GetRows($query);
	$xltm->tpls->LoadTemplate("./tmpfiles/referrals_member.html",false);
	$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
	$RecCnt = $xltm->tpls->MergeBlock('tr','array',$rows);
	$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}

function list_event($BlockName,&$CurrRec,$RecNum){
	global $xltm;
	$CurrRec['onlineTime']=($CurrRec['last_action']>(time()-180))?'<font color="#FF0000">在线</font>':'离线';
	$CurrRec['user_app']=($CurrRec['active']=='yes')?'停用':'<font color="#FF9900">启用</font>';
	$CurrRec['Ublocked']=($CurrRec['blocked']=='yes')?'<font color="#FF9900">解锁</font>':'<font color="#FF0000">锁定</font>';
	$CurrRec['money']= $xltm->AvailableCash($CurrRec['username'])*10;
}
?>