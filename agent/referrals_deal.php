<?php
include_once('global.php');
$xltm->user_log(1,"浏览下线用户持仓列表(当日)");

$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d');
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-d');
$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
$where = "b.referr_name<>'' and a.bull_trust_date>='{$start_date}' and a.bull_trust_date<='{$end_date}'";
if($username) $where .=" and b.referr_name like '%{$username}%'";
$codelist='';
$rand = rand(0,10000);
//exit("select a.* from `buy_deal` a left join `user_users` b on a.user=b.username where {$where} and a.sell='0' and a.agent='{$xltm->user_info['username']}'");
$rows = $xltm->SQL->GetRows("select a.* from `buy_deal` a left join `user_users` b on a.user=b.username where {$where} and a.sell='0' and a.agent='{$xltm->user_info['username']}'");
if(is_array($rows))
{
	foreach($rows as $r)
	{
		$codelist .= (empty($codelist)?"":",").$r['stock_type'].$r['stock_code'];
	}
}
$xltm->tpls->LoadTemplate("./tmpfiles/referrals_deal.html",false);
$xltm->tpls->MergeBlock('tr','array',$rows);
$xltm->tpls->Show();
?>