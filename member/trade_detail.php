<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

if($xltm->user_info['username']){
	$today = date('Y-m-d',time());
	$id = "";
	$qdate = isset($_REQUEST['date']) ? $_REQUEST['date'] : $today;
	//最近5日查询
	$date_list = '<table border="0" width="98%" cellspacing="1" cellpadding="3" align="center" bgcolor="#CCCCCC"><tr>';
	for($i=0;$i<5;$i++)
	{
		$date = date('Y-m-d',time()-$i*3600*24);
		$date_list .='<td align="center" width="20%" height="20" bgcolor="#FAFAFA"><a href="?type=date&date='.$date.'">';
		if($qdate==$date)
		{
			$date_list .= '<b>'.$date.'</b>';
		}
		else
		{
			$date_list .= $date;
		}
		$date_list .='</a></td>';
	}
	$date_list .='</tr></table>';
	$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
	if($type=='id') //按订单号
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
		$rows = $xltm->SQL->GetRows("select * from `buy_trust` where user='{$xltm->user_info['username']}' and id like '%{$id}%' order by stock_trust_time desc");
	}
	else
	{
		$rows = $xltm->SQL->GetRows("select * from `buy_trust` where user='{$xltm->user_info['username']}' and stock_trust_date='{$qdate}' order by stock_trust_time desc");
	}
	$xltm->tpls->LoadTemplate("./tmpfiles/trade_detail.html",false);
	$xltm->tpls->MergeBlock('tr','array',$rows);
	$xltm->tpls->Show();
}else {
	$xltm->gourl('','login.php');
}

function event_status($BlockName,&$CurrRec,$RecNum)
{
	if($CurrRec['app']=='1')
	{
		$CurrRec['status'] = '委托中';
	}
	else if($CurrRec['app']=='2')
	{
		$CurrRec['status'] = '<font color=red>已成交</font>';
	}
	else if($CurrRec['app']=='3')
	{
		$CurrRec['status'] = '<font color=gray><em>已撤单(用户)</em></font>';
	}
	else
	{
		$CurrRec['status'] = '<font color=gray><em>已撤单(系统)</em></font>';
	}
}

?>