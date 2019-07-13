<?
include_once ('global.php');
$xltm->user_log(1,"浏览交易详细 {$_GET['id']}");
$v=$xltm->SQL->GetRow("SELECT * FROM buy_deal WHERE id='{$_GET['id']}'");
$cost=$xltm->User->user_cost($v['user']);
$price=$v['sell_price'];
if($v['buy_type']==1)
{
	$differ=$price-$v['bull_price']; //差价
}else {
	$differ=$v['bull_price']-$price;
}
$extent=($differ/$v['bull_price']); //涨跌幅
$save_day=$xltm->save_day($xltm->sys_date,$v['bull_trust_date']);
$xltm->tpls->LoadTemplate("./tmpfiles/account.html");
$xltm->tpls->Show();
?>