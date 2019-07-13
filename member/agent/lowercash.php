<?php
include_once('global.php');
$xltm->user_log(1,"浏览低保证金用户列表");
if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
//遍列用户
$users = $xltm->SQL->GetRows("select username from `user_users` where agent='{$MyUser}' and active='yes' and blocked='no'");
foreach($users as $u)
{
	$cancash = $xltm->AvailableCash($u['username']);
	$islower = $cancash>$xltm->config['lower_cash'] ? 0 : 1; //小于100的时候启动预警
	$xltm->SQL->Execute("update `user_users` set islowercash='{$islower}',cancash='{$cancash}' where username='{$u['username']}'");
}
$cash=$xltm->SQL->GetRows("select * from `user_users` where demo='0' and agent='{$MyUser}' and islowercash='1' and active='yes' and blocked='no' order by last_action desc");
$xltm->tpls->LoadTemplate("./tmpfiles/lowercash.html");
$xltm->tpls->MergeBlock('tr','array',$cash);
$xltm->tpls->Show();

function list_event($BlockName,&$CurrRec,$RecNum) {
	$CurrRec['onlineTime'] = ($CurrRec['last_action'] > (time() - 180)) ? '<font color="#FF0000">在线</font>':'离线';
}
?>
