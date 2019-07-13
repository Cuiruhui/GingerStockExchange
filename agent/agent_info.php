<?php
include_once('global.php');
if($_REQUEST['type']=='regform')
{
	$new_activation_code=$xltm->User->generate_activation_code($xltm->user_info['username'],$_POST['password'],$activation_code);
	$new_password=$xltm->User->generate_password_hash($_POST['password'], $new_activation_code);
	$newPASS=($_POST['password'])?$new_password:$user_info['password'];
	$code=($new_activation_code)?$new_activation_code:$user_info['code'];
	$UPuser=array('code'=>$code,'password'=>$newPASS);
	$UPuser =$xltm->array2str($UPuser);
	$xltm->SQL->Execute("UPDATE `user_agent` set $UPuser where username='{$user_info['username']}'");
	echo "<script>alert('密码修改成功!');</script>";
}
$L2info=$xltm->SQL->GetRow("select sum(credit) as credit,sum(member_max) as member from user_login2 where agent='{$user_info['username']}'");
$can_credit=$user_info['credit']-$L2info['credit'];
$cost_bull=$user_info['cost_bull']*1000;
$cost_sell=$user_info['cost_sell']*1000;
$cost_save=$user_info['cost_save']*1000;
$showCredit1=($user_info['credit']>=10000)?($user_info['credit']/10000).' 万':$user_info['credit'];
$showCredit2=($L2info['credit']>=10000)?($L2info['credit']/10000).' 万':$L2info['credit']*1;
$showCredit3=($can_credit>=10000)?($can_credit/10000).' 万':$can_credit;

$xltm->tpls->LoadTemplate("./tmpfiles/agent_info.html");
$xltm->tpls->show();
?>
