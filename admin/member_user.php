<?php
//加密方式：php源码混淆类加密。免费版地址:http://www.zhaoyuanma.com/phpjm.html 免费版不能解密,可以使用VIP版本。

//发现了time,请自行验证这套程序是否有时间限制.
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (免费版）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
if($xltm->user_info['username']){
	$type=isset($_REQUEST['type'])?$_REQUEST['type']:'';
	switch ($type)
	{
		case 'userinfo':
			$username = isset($_GET['username']) ? $_GET['username'] : '';
			if($username)
			{
				if($user=$xltm->SQL->GetRow("select * from `user_users` where username='{$username}'"))
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
			$agent=isset($_REQUEST['agent']) ? $_REQUEST['agent'] : 'zongdai';
			$agent_list=$xltm->SQL->GetRows("SELECT username,alias,member_max,mem_num FROM `user_agent` where active='yes'");
			$openUser=0;
			if($agent)
			{
				$row=$xltm->SQL->GetRow("SELECT * FROM `user_agent` where username='$agent'");
			    $user_num=$xltm->SQL->GetRow("select count(*) as num from `user_users` where agent='{$select}'");
			    $openUser=($user_num['num']>=$row['member_max'])?1:0;
			}
			$MAX_num_min=$row['num_min']*1;       //最小单手数
			$MAX_NUM_Max=$row['num_max']*1;     //最大单手数
			$cost_bull=($row['cost_bull'])?$row['cost_bull']:'0';
			$cost_sell=($row['cost_sell'])?$row['cost_sell']:'0';
			$cost_save=($row['cost_save'])?$row['cost_save']:'0';

			$xltm->tpls->LoadTemplate("./tmpfiles/member_user_add.html");
			$xltm->tpls->MergeBlock('l1','array',$agent_list);
			$xltm->tpls->Show();
			break;
		case 'edit':
			$Edit=$xltm->SQL->GetRow("SELECT * FROM `user_users` WHERE id='{$_GET['id']}'");
			$agent=$xltm->SQL->GetRow("SELECT * FROM `user_agent` where username='{$Edit['agent']}'");
			$paylist = $xltm->SQL->GetRows("select id,name from `pay_config` where agent='{$Edit['agent']}' order by isdefault desc");
			$agent_list=$xltm->SQL->GetRows("SELECT username,alias,member_max,mem_num FROM `user_agent` where active='yes'");
			
			$cancash = $xltm->AvailableCash($Edit['username'])*$xltm->config['cost_exchange_rate'];
			//最大限制
			$Max_cost_bull=$xltm->config['cost_bull_max'];
			$Max_cost_sell=$xltm->config['cost_sell_max'];
			$Max_cost_save=$xltm->config['cost_save_max'];
			//最小限制
			$Min_cost_bull=$agent['cost_bull'];
			$Min_cost_sell=$agent['cost_sell'];
			$Min_cost_save=$agent['cost_save'];
			$MAX_num_min=$agent['num_min'];       //最小单手数
			$MAX_NUM_Max=$agent['num_max'];     //最大单手数

			$xltm->tpls->LoadTemplate("./tmpfiles/member_user_edit.html");
			$xltm->tpls->MergeBlock('py','array',$paylist);
			$xltm->tpls->MergeBlock('l1','array',$agent_list);
			$xltm->tpls->Show();
			break;
		case 'check_username':
			$username=$_GET['username'];
			$validate_username=$xltm->User->validate_username($username);
			if($validate_username && !$User=$xltm->SQL->GetRow("SELECT * FROM user_users WHERE username='{$username}'"))
			echo 'true';
			else
			echo 'false';
			break;
		case 'active':
			$User=$xltm->SQL->GetRow("SELECT active FROM user_users WHERE id='{$_GET['id']}'");
			if($User['active']=='yes')
			{
				$up='no';
				$out='<font color="#FF9900">启用</font>';
			}else {
				$up='yes';
				$out='停用';
			}
			$xltm->SQL->Execute("UPDATE user_users set active='$up' where id='{$_GET['id']}'");
			echo $out;
			break;
		case 'Ublocked':
			$User=$xltm->SQL->GetRow("SELECT blocked FROM user_users WHERE id='{$_GET['id']}'");
			if($User['blocked']=='yes')
			{
				$up='no';
				$out='<font color="#FF0000">锁定</font>';
			}else {
				$up='yes';
				$out='<font color="#FF9900">解锁</font>';
			}
			$xltm->SQL->Execute("UPDATE user_users set blocked='$up',tries='0' where id='{$_GET['id']}'");
			echo $out;
			break;
		default:
			if($type=='regsubmit')
			{
				$new_activation_code=$xltm->User->generate_activation_code($_POST['username'],$_POST['password'],$activation_code);
				$new_password=$xltm->User->generate_password_hash($_POST['password'], $new_activation_code);
				$agent = $_POST['agent'];
				$username=$_POST['username'];
				$Int=array(
				'username'=>$username,
				'password'=>$new_password,
				'code'=>$new_activation_code,
				'active'=>'yes',
				'alias'=>$_POST['alias'],
				'cost_bull'=>$_POST['cost_bull'],
				'cost_sell'=>$_POST['cost_sell'],
				'cost_save'=>$_POST['cost_save'],
				'num_min'=>$_POST['num_min'],
				'num_max'=>$_POST['num_max'],
				'money_max'=>$_POST['money_max'],
				'money'=>$_POST['money'],
				'cash'=>$_POST['cash'],
				'agent'=>$agent,
				'regdate'=>$xltm->sys_time,
				'demo'=>$_POST['demo'],
				'referrals'=>$_POST['referrals'],
				'realname'=>$_POST['realname'],
				'mobile'=>$_POST['mobile'],
				'bankcode'=>$_POST['bankcode'],
				'bankno'=>$_POST['bankno'],
				'bankname'=>$_POST['bankname'],
				'atmpwd'=>$_POST['atmpwd'],
				'email'=>$_POST['email'],
				'qq'=>$_POST['qq'],
				'referr_name'=>$_POST['referr_name']
				);
				$insert =$xltm->array2str($Int);
				$xltm->SQL->Execute("insert into `user_users` set $insert");
				//重新统计代理商下属会员帐号数
				$xltm->SQL->Execute("update `user_agent` set mem_num=(select count(*) from `user_users` where agent='{$agent}') where username='{$agent}'");
				$xltm->showMsg('新增会员',"新增会员成功!",true,'member_user.php');
			}
			if($type=='del')
			{
				$DelUser=$xltm->SQL->GetRow("SELECT * FROM user_users WHERE id='{$_GET['id']}'");
				$xltm->SQL->Execute("UPDATE 'user_agent` set mem_num=mem_num-'1' where username='{$DelUser['agent']}'");
				//清除委托记录
				$xltm->SQL->Execute("Delete from buy_trust where app='1' user='{$DelUser['username']}'");
				//清除bill
				$xltm->SQL->Execute("Delete from bill where username='{$DelUser['username']}'");
				//清除bill_log
				$xltm->SQL->Execute("Delete from bill_log where username='{$DelUser['username']}'");
				//清除充值记录
				$xltm->SQL->Execute("Delete from payment where username='{$DelUser['username']}'");
				//清除提款记录
				$xltm->SQL->Execute("Delete from atmlog where username='{$DelUser['username']}'");
				//删除用户
				$xltm->SQL->Execute("DELETE FROM user_users where id='{$_GET['id']}'");
				$xltm->showMsg('删除会员',"删除会员成功!",true,'member_user.php');
			}
			if($type=='editsubmit')
			{
				$Edit=$xltm->SQL->GetRow("SELECT * FROM `user_users` WHERE id='{$_POST['id']}'");
				if($_POST['password'])
				{
					$new_activation_code=$xltm->User->generate_activation_code($Edit['username'],$_POST['password'],$activation_code);
					$new_password=$xltm->User->generate_password_hash($_POST['password'], $new_activation_code);
				}
				$newPASS=($_POST['password'])?$new_password:$Edit['password'];
				$code=($new_activation_code)?$new_activation_code:$Edit['code'];
				$agent = $_POST['agent'];
				$agent1 = $_POST['agent1'];
				$upagent=array(
				'code'=>$code,
				'alias'=>$_POST['alias'],
				'password'=>$newPASS,
				'cost_bull'=>$_POST['cost_bull'],
				'cost_sell'=>$_POST['cost_sell'],
				'cost_save'=>$_POST['cost_save'],
				'num_min'=>$_POST['num_min'],
				'num_max'=>$_POST['num_max'],
				'money_max'=>$_POST['money_max'],
				'money'=>$_POST['money'],
				'cash'=>$_POST['cash'],
				'agent'=>$agent,
				'demo'=>$_POST['demo'],
				'referrals'=>$_POST['referrals'],
				'realname'=>$_POST['realname'],
				'bankcode'=>$_POST['bankcode'],
				'bankno'=>$_POST['bankno'],
				'bankname'=>$_POST['bankname'],
				'mobile'=>$_POST['mobile'],
				'atmpwd'=>$_POST['atmpwd'],
				'email'=>$_POST['email'],
				'qq'=>$_POST['qq'],
				'referr_name'=>$_POST['referr_name'],
				'payid'=>$_POST['payid']
				);
				$upagent =$xltm->array2str($upagent);
				$xltm->SQL->Execute("UPDATE user_users set $upagent where id='{$_POST['id']}'");
				
				//判断是否修改了代理商，如果修改则分别重新统计下属会员号
				if($agent != $agent1)
				{
					$xltm->SQL->Execute("update `user_agent` set mem_num=(select count(*) from `user_users` where agent='{$agent}') where username='{$agent}'");
					$xltm->SQL->Execute("update `user_agent` set mem_num=(select count(*) from `user_users` where agent='{$agent1}') where username='{$agent1}'");
				}
				
				//修改会员已持仓单子的卖出/留仓手续费
				$xltm->SQL->Execute("Update `buy_deal` set sell_cost='{$_POST['cost_sell']}',save_cost='{$_POST['cost_save']}' where user='{$Edit['username']}'");
			}
			$inquiry="";
			$search=isset($_REQUEST['search'])?$_REQUEST['search']:'';
			$agent = isset($_REQUEST['agent'])?$_REQUEST['agent']:'';
			$demo = isset($_REQUEST['demo'])?$_REQUEST['demo']:'0';
			$referrals = isset($_REQUEST['referrals'])?$_REQUEST['referrals']:'-1';
			$Order=isset($_REQUEST['Order'])?$_REQUEST['Order']:'regdate';
			$By=isset($_REQUEST['By'])?$_REQUEST['By']:'desc';
			if($search) $inquiry .=" and username='$search'";
			if($agent) $inquiry .=" and agent='$agent'";
			if($demo != '-1') $inquiry .=" and demo='$demo'";
			if($referrals != '-1') $inquiry .=" and referrals='$referrals'";
			//分页设置
			if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
			$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
			$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):-1;
			$PageSize = 20;
			//End page
			$user_list=$xltm->SQL->GetRows("SELECT * FROM user_users where id>0 $inquiry order by $Order $By");
			
			$agent_list=$xltm->SQL->GetRows("SELECT username FROM `user_agent` where active='yes' order by id");
			$xltm->tpls->LoadTemplate("./tmpfiles/member_user.html");
			$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);

			$RecCnt =$xltm->tpls->MergeBlock('au','array',$user_list);
			//print_r($user_list);
			// 计算保证金
			$totalmoney=0;
			$totalcash = 0;
			foreach($user_list as $key=>$value){
                		$totalcash+=$value["cash"];
        		}
        		$totalmoney = $totalmoney + $totalcash;
        		$totalone =array(array("totalcash"=>$totalcash));
        		$xltm->tpls->MergeBlock('totalcash','array',$totalone);
			
			// 计算可用资金
			$totalmoneys = 0;
			foreach($user_list as $key=>$value){
                		$totalmoneys+=$value["money"];
        		}
        		$totalmoney = $totalmoney + $totalmoneys;
        		$totaltwo=array(array("totalmoneys"=>$totalmoneys));
        		$xltm->tpls->MergeBlock('totalmoneys','array',$totaltwo);
			
			
			//foreach($user_list as $key=>$value){
      //          		$totalmoney = $totalone + totaltwo;
     //   		}
        		$totalAmount=array(array("totalmoney"=>$totalmoney));
        		$xltm->tpls->MergeBlock('totalmoney','array',$totalAmount);
			$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
			$showPage=($RecCnt>0)?1:'';
			$xltm->tpls->MergeBlock('a1','array',$agent_list);
			$xltm->tpls->Show();
			break;
	}
}else {
	$xltm->gourl('账号超时,请重新登陆','login.php');
}
function list_event($BlockName,&$CurrRec,$RecNum){
	global $xltm;
	$CurrRec['onlineTime']=($CurrRec['last_action']>(time()-180))?'<font color="#FF0000">在线</font>':'离线';
	$CurrRec['user_app']=($CurrRec['active']=='yes')?'停用':'<font color="#FF9900">启用</font>';
	$CurrRec['Ublocked']=($CurrRec['blocked']=='yes')?'<font color="#FF9900">解锁</font>':'<font color="#FF0000">锁定</font>';
	$CurrRec['money']= $xltm->AvailableCash($CurrRec['username'])* $xltm->config['cost_exchange_rate'];
}
?>
