<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();

include_once("./Lib/xltm.class.php");
$type=$_REQUEST['type'];
$bill_types = array(array('bill_id'=>'1','bill_type'=>'充值'),array('bill_id'=>'2','bill_type'=>'手续费'),array('bill_id'=>'3','bill_type'=>'留仓费'),array('bill_id'=>'4','bill_type'=>'浮盈'),array('bill_id'=>'5','bill_type'=>'其他'),array('bill_id'=>'6','bill_type'=>'点差费'),array('bill_id'=>'7','bill_type'=>'滑价修正'));

$bill_type = array('1'=>'充值','2'=>'手续费','3'=>'留仓费','4'=>'浮盈','5'=>'其他');
$agents = $xltm->SQL->GetRows("select username from `user_agent` order by username asc");
switch ($type)
{
	case 'add':
		$do = isset($_REQUEST['do']) ? $_REQUEST['do'] : 'add';
		$xltm->tpls->LoadTemplate("./tmpfiles/bill_add.html");
		$xltm->tpls->MergeBlock('bt','array',$bill_types);
		$xltm->tpls->MergeBlock('ag','array',$agents);
		$xltm->tpls->show();
		break;
	case 'edit':
		$edit=$xltm->SQL->GetRow("select * from `bill_log` where id='{$_GET['id']}'");
		$xltm->tpls->LoadTemplate("./tmpfiles/bill_edit.html");
		$xltm->tpls->MergeBlock('bt','array',$bill_types);
		$xltm->tpls->MergeBlock('ag','array',$agents);
		$xltm->tpls->show();
		break;
	default:
		if($type=='AddFrom')
		{
			$do = isset($_POST['do']) ? $_POST['do'] : 'add';
			$doname = $do=='add' ? '入款' : '扣款';
			$money = abs($_POST['money']);
			$money = $do=='cut' ? (0-$money) : $money;
			$insert = array(
				'username'=>$_POST['username'],
				'money'=>$money,
				'remark'=>$_POST['remark'],
				'bill_type'=>$_POST['bill_type'],
				'add_date'=>$xltm->sys_date,
				'add_time'=>$xltm->sys_time
			);
			$query = $xltm->array2str($insert);
			$xltm->SQL->Execute("insert into `bill_log` set {$query}");

			$bill_type = trim($_POST['bill_type']);

			if ($bill_type == '滑价修正'){		//滑价修改，只处理持仓股，也就是未卖出的。而且，入费扣费都一样的处理。

				$user_info = $xltm->SQL->GetRow("select * from `user_users` where username='{$_POST['username']}'");

				$buy_arr = $xltm->SQL->GetRow("select * from `buy_deal` where id=" . $_POST['bill_no']);
				
				$money			= abs( $money );						//单价
				$bull_money	= $buy_arr["bull_num"]*100*$money;		//股票总金额
				
				$bull_cost_money = round($bull_money*($user_info['cost_bull']),2); //交易手续费
				$dc_num = $buy_arr['dc_num'];				//点差
				$dc_money = round($bull_money*($dc_num/1000),2); //点差费，买入收取
				$totalmoney = $bull_money + $bull_cost_money + $dc_money; //总金额(买入总价+手续费+点差费)
				$agent_bull_money = round(($bull_cost_money+$dc_money)*($buy_arr['agent_ret']/100),2) ;		//代理金额

//print_r($dc_money);exit;

				$diff_money = $buy_arr["bull_cost_money"] + $buy_arr["dc_money"] - $bull_cost_money - $dc_money;

				//更新 购买金额，点差金额，手续费，代理金额。
				$xltm->SQL->Execute( "Update `buy_deal` set bull_price={$money},bull_money={$bull_money},dc_money={$dc_money},bull_cost_money={$bull_cost_money},agent_bull_money={$agent_bull_money} where id=" . $_POST['bill_no'] );


				$money = $diff_money; 

			}


			$xltm->SQL->Execute("update `user_users` set money=money+{$money},cash=cash+{$money} where username='{$_POST['username']}'");

			if ($bill_type == '点差费'){
				if($do=='add'){
					$money = abs( $money );
					$xltm->SQL->Execute("update `buy_deal` set dc_money=dc_money-{$money} where id=" . $_POST['bill_no']);
				}else{
					$money = abs( $money );
					$xltm->SQL->Execute("update `buy_deal` set dc_money={$money} where id=" . $_POST['bill_no']);
				}
			}

			$xltm->showMsg("手动{$doname}","手动{$doname}成功！",true,'bill.php');
		}
		if($type=='EditFrom')
		{
			$id = $_REQUEST['id'];
			if($row = $xltm->SQL->GetRow("select * from `bill_log` where id='{$id}'"))
			{
				if ($row["bill_type"] == '点差费' || $row["bill_type"] == '滑价修正'){
					$xltm->showMsg("手动入/扣款修改","点差费 及 滑价修正 不支持直接修改，请重新执行入扣款操作！",false,"back");
					return;
				}
			}
			$money = $_POST['money'];
			$money1 = $_POST['money1'];
			$cha = $money-$money1;
			if($cha!=0) $xltm->SQL->Execute("update `user_users` set money=money+{$cha},cash=cash+{$cha} where username='{$_POST['username']}' and agent='{$_POST['agent']}'");
			$update = array(
				'username'=>$_POST['username'],
				'money'=>$money,
				'remark'=>$_POST['remark'],
				'bill_type'=>$_POST['bill_type'],
				'agent'=>$_POST['agent']
			);
			$query = $xltm->array2str($update);
			$xltm->SQL->Execute("update `bill_log` set {$query} where id='{$_POST['id']}'");
			$xltm->showMsg("手动入/扣款修改","手动入/扣款修改成功！",true,'bill.php');
		}
		if($type=='del')
		{
			$id = $_REQUEST['id'];
			if($row = $xltm->SQL->GetRow("select * from `bill_log` where id='{$id}'"))
			{
				if ($row["bill_type"] == '点差费' || $row["bill_type"] == '滑价修正'){
					$xltm->showMsg("手动入/扣款修改","点差费 及 滑价修正 不支持删除操作！",false,"back");
					return;
				}

				$xltm->SQL->Execute("Update `user_users` set money=money-{$row['money']},cash=cash-{$row['money']} where username='{$row['username']}'");
				$xltm->SQL->Execute("delete from `bill_log` where id='{$id}'");
			}
		}
		//分页设置
		if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
		$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
		$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
		$PageSize = 25;
		$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
		$bill_id = isset($_REQUEST['bill_id']) ? intval($_REQUEST['bill_id']) : 0;
		$demo = isset($_REQUEST['demo']) ? $_REQUEST['demo'] : '0';
		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
		$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-d',time()-30*24*3600);
		$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : $xltm->sys_date;
		if($start_date>$end_date)
		{
			$xltm->showMsg("资金明细","查询开始日期不能大于结束日期！",false,"back");
		}
		$where = " a.add_date>='{$start_date}' and a.add_date<='{$end_date}'";
		if($agent) $where .= " and a.agent='{$agent}'";
		if($bill_id>0) $where .= " and a.bill_type='{$bill_type[$bill_id]}'";
		if($demo!='-1') $where .= " and b.demo='{$demo}'";
		if($username) $where .= " and a.username like '%{$username}%'";
		
		$bills=$xltm->SQL->GetRows("SELECT a.* FROM `bill_log` a left join `user_users` b on a.username=b.username where {$where} order by a.add_time desc");
		$xltm->tpls->LoadTemplate("./tmpfiles/bill.html");
		$xltm->tpls->MergeBlock('ag','array',$agents);
		$xltm->tpls->MergeBlock('bt','array',$bill_types);
		$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
		$RecCnt = $xltm->tpls->MergeBlock('tr','array',$bills);
		$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
		$showPage=($RecCnt>0)?1:'';
		$xltm->tpls->show();
		break;
}
?>