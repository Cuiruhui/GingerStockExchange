<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
if($xltm->user_info['username']){
	$type=isset($_REQUEST['type'])?$_REQUEST['type']:'';
	switch ($type)
	{
		case 'add':
			//最大限制
			$Max_Member=99999;
			$Min_Member = 5000;
			$MIN_NUM_Max=$xltm->config['num_min']; //最小单手数
			$MAX_NUM_Max=$xltm->config['num_max']; //最大单手数
			$xltm->tpls->LoadTemplate("./tmpfiles/member_agent_add.html");
			$xltm->tpls->Show();
			break;
		case 'edit':
			$Edit=$xltm->SQL->GetRow("SELECT * FROM `user_agent` WHERE id='{$_GET['id']}'");
			//最大限制
			$Max_Member=99999;
			$Min_Member = 5000;
			$MIN_NUM_Max=$xltm->config['num_min']; //最小单手数
			$MAX_NUM_Max=$xltm->config['num_max']; //最大单手数
			$xltm->tpls->LoadTemplate("./tmpfiles/member_agent_edit.html");
			$xltm->tpls->Show();
			break;
		case 'check_username':
			$validate_username=$xltm->User->validate_username($_REQUEST['username']);
			if($validate_username && !$User=$xltm->SQL->GetRow("SELECT * FROM `user_agent` WHERE username='{$_REQUEST['username']}'"))
			echo 'true';
			else
			echo 'false';
			break;
		case 'active':
			$User=$xltm->SQL->GetRow("SELECT active FROM `user_agent` WHERE id='{$_GET['id']}'");
			if($User['active']=='yes')
			{
				$up='no';
				$out='<font color="#FF9900">启用</font>';
			}else {
				$up='yes';
				$out='停用';
			}
			$xltm->SQL->Execute("UPDATE `user_agent` set active='$up' where id='{$_GET['id']}'");
			echo $out;
			break;
		case 'Ublocked':
			$User=$xltm->SQL->GetRow("SELECT blocked FROM `user_agent` WHERE id='{$_GET['id']}'");
			if($User['blocked']=='yes')
			{
				$up='no';
				$out='<font color="#FF0000">锁定</font>';
			}else {
				$up='yes';
				$out='<font color="#FF9900">解锁</font>';
			}
			$xltm->SQL->Execute("UPDATE `user_agent` set blocked='$up',tries='0' where id='{$_GET['id']}'");
			echo $out;
			break;
		default:
			if($type=='regsubmit')
			{
				//验证用户名
				$query = "select * from user_agent where username='{$_POST['username']}'";
				$row = $xltm->SQL->GetRow($query);
				if(is_array($row))
				{
					$xltm->showMsg('代理商管理',"存在相同的用户名!",false,'back');
				}
				$new_activation_code=$xltm->User->generate_activation_code($_POST['username'],$_POST['password'],$activation_code);
				$new_password=$xltm->User->generate_password_hash($_POST['password'], $new_activation_code);
				$Int=array(
				'username'=>$_POST['username'],
				'password'=>$new_password,
				'code'=>$new_activation_code,
				'active'=>'yes',
				'alias'=>$_POST['alias'],
				'cost_bull'=>$_POST['cost_bull'],
				'cost_sell'=>$_POST['cost_sell'],
				'cost_save'=>$_POST['cost_save'],
				'cost_ret'=>$_POST['cost_ret'],
				'num_min'=>$_POST['num_min'],
				'num_max'=>$_POST['num_max'],
				'member_max'=>$_POST['member_max'],
				'regdate'=>$xltm->sys_time,
				'domain'=>$_POST['domain']
				);
				$insert =$xltm->array2str($Int);
				$xltm->SQL->Execute("insert into `user_agent` set $insert");
				$xltm->showMsg('代理商管理',"新增代理商帐号成功!",true,'member_agent.php');
			}
			if($type=='del')
			{
				$DelUser=$xltm->SQL->GetRow("SELECT username FROM `user_agent` WHERE id='{$_GET['id']}'");
				$xltm->SQL->Execute("DELETE FROM user_users where `user_agent`='{$DelUser['username']}'");
				$xltm->SQL->Execute("DELETE FROM `user_agent` where id='{$_GET['id']}'");
				$xltm->showMsg('代理商管理',"删除代理商帐号成功!",true,'member_agent.php');
			}
			//确认修改用户
			if($type=='editsubmit')
			{
				$Edit=$xltm->SQL->GetRow("SELECT * FROM `user_agent` WHERE id='{$_POST['id']}'");
				if($_POST['password'])
				{
					$new_activation_code=$xltm->User->generate_activation_code($Edit['username'],$_POST['password'],$activation_code);
					$new_password=$xltm->User->generate_password_hash($_POST['password'], $new_activation_code);
				}
				$newPASS=($_POST['password'])?$new_password:$Edit['password'];
				$code=($new_activation_code)?$new_activation_code:$Edit['code'];
				$upagent=array(
				'code'=>$code,
				'alias'=>$_POST['alias'],
				'password'=>$newPASS,
				'cost_bull'=>$_POST['cost_bull'],
				'cost_sell'=>$_POST['cost_sell'],
				'cost_save'=>$_POST['cost_save'],
				'cost_ret'=>$_POST['cost_ret'],
				'credit'=>$_POST['credit'],
				'member_max'=>$_POST['member_max'],
				'num_min'=>$_POST['num_min'],
				'num_max'=>$_POST['num_max'],
				'domain'=>$_POST['domain']
				);
				$upagent =$xltm->array2str($upagent);
				$xltm->SQL->Execute("UPDATE `user_agent` set $upagent where id='{$_POST['id']}'");
				//修改下属会员的买入/卖出/留仓手续费/最小手数/最大手数
				$xltm->SQL->Execute("UPDATE `user_users` set cost_bull='{$_POST['cost_bull']}',cost_sell='{$_POST['cost_sell']}',cost_save='{$_POST['cost_save']}',num_min='{$_POST['num_min']}',num_max='{$_POST['num_max']}' where agent='{$Edit['username']}'");
				
				//修改下属会员已持仓单子的卖出/留仓手续费/代理分成
				$xltm->SQL->Execute("Update `buy_deal` set sell_cost='{$_POST['cost_sell']}',save_cost='{$_POST['cost_save']}',agent_ret='{$_POST['cost_ret']}' where sell='0' and agent='{$Edit['username']}'");
			}
			$inquiry="";
			$search=($_REQUEST['search'])?$_REQUEST['search']:'';
			if($search)
			$inquiry .=" and username='$search'";
			$Order=($_REQUEST['Order'])?$_REQUEST['Order']:'username';
			$By=($_REQUEST['By'])?$_REQUEST['By']:'asc';
			//分页设置
			if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
			$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
			$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
			$PageSize = 15;
			//End page
			$agent=$xltm->SQL->GetRows("SELECT * FROM `user_agent` where id>0 $inquiry order by $Order $By");
			$xltm->tpls->LoadTemplate("./tmpfiles/member_agent.html");
			$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
			$RecCnt =$xltm->tpls->MergeBlock('au','array',$agent);
			$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
			$showPage=($RecCnt>0)?1:'';
			$xltm->tpls->Show();
			break;
	}
}else {
	$xltm->gourl('账号超时,请重新登陆','login.php');
}
function list_event($BlockName,&$CurrRec,$RecNum){
	$CurrRec['onlineTime']=($CurrRec['last_action']>(time()-180))?'<font color="#FF0000">在线</font>':'离线';
	$CurrRec['user_app']=($CurrRec['active']=='yes')?'停用':'<font color="#FF9900">启用</font>';
	$CurrRec['Ublocked']=($CurrRec['blocked']=='yes')?'<font color="#FF9900">解锁</font>':'<font color="#FF0000">锁定</font>';
}
?>