<?php
include_once ('global.php');
$type = $_REQUEST['type'];
switch($type) {
	case 'userinfo':
		$username = isset($_GET['username']) ? $_GET['username'] : '';
		if($username)
		{
			if($user=$xltm->SQL->GetRow("select * from `user_users` where username='{$username}' and agent='{$xltm->user_info['username']}'"))
			{
				$user['cancash']=$xltm->AvailableCash($user['username']);
				//本月交易金额
				$trade_money_month = 0;
				$trade_money_total = 0;
				$cost_total = 0;
				$save_money_total = 0;
				$start_date=date('Y-m-1',time());
				if($row=$xltm->SQL->GetRow("select sum(money) as trade_money_month from `buy_trust` where app='2' and user='{$user['username']}' and stock_trust_date>='{$start_date}' and stock_trust_date<='{$xltm->sys_date}'"))
				{
					if(is_numeric($row['trade_money_month']) && $row['trade_money_month']>0)
					{
						$trade_money_month=$row['trade_money_month'];
					}
				}
				if($row=$xltm->SQL->GetRow("select sum(money) as trade_money_total from `buy_trust` where app='2' and user='{$user['username']}'"))
				{
					if(is_numeric($row['trade_money_total']) && $row['trade_money_total']>0)
					{
						$trade_money_total=$row['trade_money_total'];
					}
				}
				if($row=$xltm->SQL->GetRow("select sum(bull_cost_money+sell_cost_money) as cost_total,sum(save_money) as save_money_total from `buy_deal` where user='{$user['username']}'"))
				{
					if(is_numeric($row['cost_total']) && $row['cost_total']>0)
					{
						$cost_total=$row['cost_total'];
					}
					if(is_numeric($row['save_money_total']) && $row['save_money_total']>0)
					{
						$save_money_total=$row['save_money_total'];
					}
				}
				$user['trade_money_total']=$trade_money_total;
				$user['trade_money_month']=$trade_money_month;
				$user['cost_total']=$cost_total;
				$user['save_money_total']=$save_money_total;
				$xltm->tpls->LoadTemplate("./tmpfiles/member_info.html",false);
				$xltm->tpls->Show();
			}
			else
			{
				echo "<script>alert('找不到会员信息或者该会员不是你的下属会员！');window.parent.ymPrompt.doHandler('error',true);</script>";
			}
		}
		else
		{
			echo "<script>alert('未指定用户名！');window.parent.ymPrompt.doHandler('error',true);</script>";
			exit();
		}
		break;
	case 'add':
		//最小限制
		$Min_cost_bull =$user_info['cost_bull'];
		$Min_cost_sell =$user_info['cost_sell'];
		$Min_cost_save =$user_info['cost_save'];
		//可用会员人数
		$canMember=0;
		$centArr=array();
		$Agentinfo=$xltm->SQL->GetRow("SELECT * FROM `user_agent` where username='{$MyUser}'");
		$Member=$xltm->SQL->GetRow("select count(id) as count from `user_users` where agent='{$MyUser}'");
		$user_prefix=substr($Agentinfo['username'],0,$config['user_member_prefix']);
		$Min_cost_bull =$Agentinfo['cost_bull'];
		$Min_cost_sell =$Agentinfo['cost_sell'];
		$Min_cost_save =$Agentinfo['cost_save'];
		//可用会员人数
		$canMember=$Agentinfo['member_max']-$Member['count'];
		if($canMember<1)
			$xltm->Alert('新增会员',"代理商{$MyUser}会员人数已满.",'history.go(-1);');

		$Num_Min=$Agentinfo['num_min']*1;       //最小单手数
		$Num_Max=$Agentinfo['num_max']*1;     //最大单手数
		$xltm->tpls->LoadTemplate("./tmpfiles/member_add.html");
		$xltm->tpls->MergeBlock('tr','array',$L3list);
		$xltm->tpls->MergeBlock('ti','array',$centArr);
		$xltm->tpls->Show();
		break;
	case 'edit':
		$Edit = $xltm->SQL->GetRow("SELECT * FROM `user_users` WHERE id='{$_GET['id']}'");
		$Agentinfo = $xltm->SQL->GetRow("select * from `user_agent` where username='{$MyUser}'");
		//最小限制
		$Min_cost_bull = $Agentinfo['cost_bull'];
		$Min_cost_sell = $Agentinfo['cost_sell'];
		$Min_cost_save = $Agentinfo['cost_save'];
		$agent_num_min=$Agentinfo['num_min'];       //最小单手数
		$agent_num_max=$Agentinfo['num_max'];     //最大单手数
		$xltm->tpls->LoadTemplate("./tmpfiles/member_edit.html");
		$xltm->tpls->Show();
		break;
	case 'check_username':
		$user_prefix=$_GET['user_prefix'];
		$username = $user_prefix.$_GET['username'];
		$validate_username = $xltm->User->validate_username($username);
		if($xltm->User->check_username(5,$username)) {
			echo 'true';
		} else {
			echo 'false';
		}
		break;
	case 'active':
		$User = $xltm->SQL->GetRow("SELECT active FROM user_users where agent='{$MyUser}' and id='{$_GET['id']}'");
		if($User['active'] == 'yes') {
			$up = 'no';
			$out = '<font color="#FF9900">启用</font>';
		} else {
			$up = 'yes';
			$out = '停用';
		}
		$xltm->SQL->Execute("UPDATE user_users set active='$up' where agent='{$MyUser}' and id='{$_GET['id']}'");
		echo $out;
		break;
	case 'Ublocked':
		$User = $xltm->SQL->GetRow("SELECT blocked FROM user_users where agent='{$MyUser}' and id='{$_GET['id']}'");
		if($User['blocked'] == 'yes') {
			$up = 'no';
			$out = '<font color="#FF0000">锁定</font>';
		} else {
			$up = 'yes';
			$out = '<font color="#FF9900">解锁</font>';
		}
		$xltm->SQL->Execute("UPDATE user_users set blocked='$up',tries='0' where agent='{$MyUser}' and id='{$_GET['id']}'");
		echo $out;
		break;
	default:
		if($type == 'regsubmit') {
			$new_activation_code = $xltm->User->generate_activation_code($_POST['username'],$_POST['password'],$activation_code);
			$new_password = $xltm->User->generate_password_hash($_POST['password'],$new_activation_code);
			$u_p = substr($MyUser,0,$xltm->config['user_member_prefix']);
			$username = $u_p.$_POST['username'];
			$Agentinfo = $xltm->SQL->GetRow("select * from `user_agent` where username='{$MyUser}'");
			$Mmeber = $xltm->SQL->GetRow("select count(id) as count from `user_users` where agent='{$MyUser}'");
			$Min_cost_bull = $Agentinfo['cost_bull'];
			$Min_cost_sell = $Agentinfo['cost_sell'];
			$Min_cost_save = $Agentinfo['cost_save'];
			$canMember = $Agentinfo['member_max'] - $Mmeber['count']; //代理商可用会员人数
			
			echo $username;
			echo '1';
			
			if($xltm->User->check_username(2,$username) == false
			|| $_POST['cost_bull'] < $Min_cost_bull
			|| $_POST['cost_sell'] < $Min_cost_sell
			|| $_POST['cost_save'] < $Min_cost_save
			|| $_POST['cost_bull'] > $Max_cost_bull
			|| $_POST['cost_sell'] > $Max_cost_sell
			|| $_POST['cost_save'] > $Max_cost_save
			|| $canMember<1) {
				$xltm->ShowPrompt("新增失败,下属会员数已经超过您的最大会员数！.","",1);
			} else {
				$xltm->user_log(1,"新增会员账号:$username");
				$Int = array(
				'username' => $username,
				'password' => $new_password,
				'code' => $new_activation_code,
				'active' => 'yes',
				'alias' => $_POST['alias'],
				'cost_bull' =>$_POST['cost_bull'],
				'cost_sell' => $_POST['cost_sell'],
				'cost_save' => $_POST['cost_save'],
				'money' => $_POST['money'],
				'cash' => $_POST['cash'],
				'num_min'=>$_POST['num_min'],
				'num_max'=>$_POST['num_max'],
				'agent' =>$MyUser,
				'regdate' => $xltm->sys_time,
				'demo'=>$_POST['demo'],
				'referrals'=>$_POST['referrals'],
				'realname'=>$_POST['realname'],
				'mobile'=>$_POST['mobile'],
				'atmpwd'=>$_POST['atmpwd'],
				'email'=>$_POST['email'],
				'qq'=>$_POST['qq'],
				'referr_name'=>$_POST['referr_name']
				);
				$insert = $xltm->array2str($Int);
				$xltm->SQL->Execute("insert into user_users set $insert");
				$xltm->SQL->Execute("UPDATE `user_agent` set mem_num=mem_num+1 where username='{$MyUser}'");
				$xltm->Alert('新增会员','新增成功!','self.location.href="member.php";');
			}
		}
		if($type == 'editsubmit') {
			if($_POST['password']) {
				$new_activation_code = $xltm->User->generate_activation_code($Edit['username'],$_POST['password'],$activation_code);
				$new_password = $xltm->User->generate_password_hash($_POST['password'],$new_activation_code);
			}
			$Edit = $xltm->SQL->GetRow("SELECT * FROM `user_users` WHERE id='{$_POST['id']}'");
			$Agentinfo = $xltm->SQL->GetRow("select * from `user_agent` where username='{$MyUser}'");
			$newPASS = ($_POST['password']) ? $new_password:$Edit['password'];
			$code = ($new_activation_code) ? $new_activation_code:$Edit['code'];
			//最小限制
			$Min_cost_bull = $Agentinfo['cost_bull'];
			$Min_cost_sell = $Agentinfo['cost_sell'];
			$Min_cost_save = $Agentinfo['cost_save'];
			
	
			if($_POST['cost_bull'] < $Min_cost_bull //0.002 98
			|| $_POST['cost_sell'] < $Min_cost_sell	//0.002 98
			|| $_POST['cost_save'] < $Min_cost_save //0.0004 100
			|| $_POST['cost_bull'] > $Max_cost_bull //0.005  101
			|| $_POST['cost_sell'] > $Max_cost_sell //0.005  101
			|| $_POST['cost_save'] > $Max_cost_save //0.0004 100
			) {
				$xltm->ShowPrompt("修改失败[3].","",1);
			} else {
				$xltm->user_log(1,"编辑会员{$Edit['username']}");
				$upmember = array(
				'code' => $code,
				'alias' => $_POST['alias'],
				'password' => $newPASS,
				'cost_bull' => $_POST['cost_bull'],
				'cost_sell' => $_POST['cost_sell'],
				'cost_save' => $_POST['cost_save'],
				'num_min'=>$_POST['num_min'],
				'num_max'=>$_POST['num_max'],
				'cash' => $_POST['cash'],
				'money' => $_POST['money'],
				'demo'=>$_POST['demo'],
				'referrals'=>$_POST['referrals'],
				'realname'=>$_POST['realname'],
				'mobile'=>$_POST['mobile'],
				'atmpwd'=>$_POST['atmpwd'],
				'email'=>$_POST['email'],
				'referr_name'=>$_POST['referr_name']
				);
				$upmember = $xltm->array2str($upmember);
				$xltm->SQL->Execute("UPDATE `user_users` set $upmember where agent='{$MyUser}' and id='{$_POST['id']}'");
				//修改会员已持仓单子的卖出/留仓手续费
				$xltm->SQL->Execute("Update `buy_deal` set sell_cost='{$_POST['cost_sell']}',save_cost='{$_POST['cost_save']}' where user='{$Edit['username']}'");
				$xltm->Alert('编辑会员','保存会员信息成功!','self.location.href="member.php";');
			}
		}
		$xltm->user_log(1,"浏览会员列表");
		$inquiry = "";
		$search = ($_REQUEST['search']) ? $_REQUEST['search']:'';
		$demo = isset($_REQUEST['demo']) ? $_REQUEST['demo'] : 0;
		if($search)
		$inquiry .= " and username like '%$search%'";
		
		if($demo=='1')
		{
			$inquiry .=" and demo='1'";
		}
		else if($demo=='999')
		{
			$inquiry .=" and referrals='1'";
		}
		else
		{
			$inquiry .=" and demo='0'";
		}
		$Order = ($_GET['Order']) ? $_GET['Order']:'regdate';
		$By = ($_GET['By']) ? $_GET['By']:'desc';
		//分页设置
		if(!isset($_GET))$_GET = &$HTTP_GET_VARS;
		$PageNum = (isset($_GET['PageNum'])) ? $_GET['PageNum']:1;
		$RecCnt = (isset($_GET['RecCnt'])) ? intval($_GET['RecCnt']):-1;
		$PageSize = 30;
		//End page
		$member = $xltm->SQL->GetRows("SELECT * FROM `user_users` where agent='{$MyUser}' $inquiry order by $Order $By");
		$totalrecord = count($member);
		$xltm->tpls->LoadTemplate("./tmpfiles/member.html",false);
		$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
		$RecCnt = $xltm->tpls->MergeBlock('tr','array',$member);
		$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
		$showPage = ($RecCnt > 0) ? 1:'';
		$xltm->tpls->Show();
		break;
}
function list_event($BlockName,&$CurrRec,$RecNum) {
	global $xltm;
	$CurrRec['cancash'] = $xltm->AvailableCash($CurrRec['username']);
	$CurrRec['money'] = $CurrRec['cancash']* $xltm->config['cost_exchange_rate'];
	$CurrRec['onlineTime'] = ($CurrRec['last_action'] > (time() - 180)) ? '<font color="#FF0000">在线</font>':'离线';
	$CurrRec['user_app'] = ($CurrRec['active'] == 'yes') ? '停用':'<font color="#FF9900">启用</font>';
	$CurrRec['Ublocked'] = ($CurrRec['blocked'] == 'yes') ? '<font color="#FF9900">解锁</font>':'<font color="#FF0000">锁定</font>';
}
?>
