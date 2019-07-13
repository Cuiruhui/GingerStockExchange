<?php
// 报错级别设定,一般在开发环境中用E_ALL,这样能够看到所有错误提示
// 系统正常运行后,直接设定为E_ALL || ~E_NOTICE,取消错误显示
error_reporting(E_ALL);
error_reporting(E_ALL || ~E_NOTICE);
date_default_timezone_set ('Asia/Hong_Kong');
header("Content-Type: text/html; charset=utf-8");
$xltm= new xltm();
class xltm{
	function xltm(){
		$this->user_type=1;
		$this->sys_date=date('Y-m-d',time());
		$this->sys_time=date('Y-m-d H:i:s',time());
		require_once('Security.class.php');
		$this->Security = new Security($this);
		//加载 SQL 类
		require_once('tpls_mysql.php');
		require_once('database_info.php');
		$this->SQL = new MYSQL($database_server_name,$database_username,$database_password,$database_name,$database_port,$this);
		require_once('tpls_class_php5.php');
		require_once('tpls_plugin_html.php');
		require_once('tpls_plugin_bypage.php');
		require_once('tpls_plugin_navbar.php');
		require_once('tpls.plugin.aggregate.php');
		$this->tpls = new clsTinyButStrong;
		// 获取配置信息，并指定$config
		$re_info=$this->SQL->GetRows("SELECT * FROM `user_config`");
		foreach ($re_info as $info)
		{
			$this->config[$info['name']] = $info['value'];
		}
		if($this->config['system_safeguard']=='on'){
          echo $this->config['system_safeguard_direc'];
          exit;
		}
		if (Load_Info) {
			//获取用户类
			require_once('User.class.php');
			$this->User = new User($this);
			//删除任何过时登陆数据和游客数据
			$this->Security->remove_old_tries();
			//session类
			require_once('Session.class.php');
			$this->Session = new Session($this);
			$this->Session->clear_old_sessions();
			//exit;
			// 查看是否有效或活动用户
			$this->User->validate_login();
			//echo '<pre>';
			//print_r($this);

			// 如果用户已经登陆则设置用户最后活动时间
			if ($this->user_info['username'] != '') {
				$this->SQL->Execute("update `user_agent` set last_action='".time()."' where id='{$this->user_info['id']}'");
			}
			if(Load_Info==1 && $this->user_info['username']=='')
			$this->gourl('登录超时,请重新登录.','../');
			//$this->outlogin();
			if ($this->user_info['blocked'] == 'yes') {
				$this->gourl('对不起,你的帐号被锁定,请联系你的上级服务商.','../');
				//die('对不起你禁止访问本站');
			}
			require_once("buy.class.php");
			$this->buy = new buy($this);
		}
	}
	//弹出信息窗口提示
	function ShowPrompt($str,$js="",$exit=1)
	{
		$show='<script type="text/javascript">';
		$show .='$().ready ( function() {';
		if($js)
		{
			$show .=$js;
		}
		$show .="$.prompt('$str');";
		$show .='});</script>';
		print $show;
		if($exit==0)
		exit;
	}
	function outlogin()
	{
		$show='<script type="text/javascript">';
		$show .='$().ready ( function() {';
		$show .="function outlogin(){";
		$show.="location.href='login.php';";
		$show.="}";
		$show.="$.prompt('登陆超时或账号在其他地方登陆,点击确定返回登录界面!!',{ callback: outlogin }); ";
		$show .='});</script>';
		print $show;
		exit;
	}
	//数组转换字符串
	function array2str($array)
	{
		$str="";
		foreach ($array as $k=>$v)
		{
			$str .=$k."='".$v."',";
		}
		$str =substr($str,0,-1);
		return $str;
	}
	//用户动作记录
	function user_log($type,$page_str)
	{
		if($this->user_info['username'] != '')
		{
			$userLOG=array(
			'user_type'=>$type,
			'user_name'=>$this->user_info['username'],
			'user_page'=>$page_str,
			'hit_time'=>$this->sys_time
			);
			$in_log=$this->array2str($userLOG);
			$this->SQL->Execute("insert into user_log set $in_log");
		}else {
			$this->outlogin();
			exit;
		}
	}
	
	//自动平仓
	function auto_sell($id,$sell_price=0,$sell_time)
	{
		global $MyUser;
		$saleID=$id;
		if($row=$this->SQL->GetRow("SELECT * FROM `buy_deal` WHERE id='{$saleID}' and sell='0' and agent='{$MyUser}'"))
		{
			if($sell_price==0)
			{
				if($quote = $this->Quote($row['stock_type'].$row['stock_code']))
				{
					//市价卖出多和空委托价格 多=$Net[7] 空=$Net[6]
					$sell_price = ($row['buy_type']=='1') ? $quote[8] : $quote[7];
					if($sell_price == 0)
					{
						return "false|停止交易！";
					}
				}
				else
				{
					return "false|获取行情数据失败！";
				}
			}
			if($sell_time=='') $sell_time = $this->sys_time;
			$sell_date = date('Y-m-d',strtotime($sell_time));
			//卖出金额
			$sell_money=$row['bull_num']*100*$sell_price;
			//插入交易临时表
			$SQL_trust=array(
				'user'=>$row['user'],
				'code'=>$row['stock_code'],
				'type'=>$row['stock_type'],
				'name'=>$row['stock_name'],
				'trust_type'=>2,
				'buy_type'=>$row['buy_type'],
				'price'=>$sell_price,
				'app'=>2, //
				'num'=>$row['bull_num'],
				'money'=>$sell_money,
				'agent'=>$row['agent'],
				'stock_trust_date'=>$sell_date,
				'stock_trust_time'=>$sell_time,
				'stock_deal_time'=>$sell_time
			);
			$insert_trust =$this->array2str($SQL_trust);
			$this->SQL->Execute("insert into `buy_trust` set $insert_trust");
			//委托卖单ID
			$newID=$this->SQL->LastRowId();
			//会员卖出手续费金额
			$sell_cost_money= round($sell_money*$row['sell_cost'],2);
			//代理商获得的水费
			$agent_sell_cost_money = round($sell_cost_money*($row['agent_ret']/100),2);
			//计算盈亏
			if($row['buy_type']==1)
			{
				//(多)盈亏计算 卖出金额-买入金额-买入手续费-卖出手续费
				$sell_gain=$sell_money-$row['bull_money'];
				$gain=$sell_money-$row['bull_money']-$row['bull_cost_money']-$row['dc_money']-$row['save_money'];
			}else {
				//(空)盈亏计算 买入金额-卖出金额-买入手续费-卖出手续费
				$sell_gain=$row['bull_money']-$sell_money;
				$gain=$row['bull_money']-$sell_money-$row['bull_cost_money']-$row['dc_money']-$row['save_money'];
			}
			$SQL_deal=array(
				'sell'=>1, //设置为已经卖出
				'sell_price'=>$sell_price,
				'sell_cost_money'=>$sell_cost_money,
				'sell_money'=>$sell_money,
				'sell_trust_id'=>$newID,
				'sell_trust_date'=>$sell_date,
				'sell_trust_time'=>$sell_time,
				'agent_sell_money'=>$agent_sell_cost_money,
				'can_sell_num'=>0,
				'sell_gain'=>$sell_gain,
				'gain'=>$gain
			);
			$update_deal=$this->array2str($SQL_deal);
			$this->SQL->Execute("UPDATE `buy_deal` set $update_deal where id='{$saleID}'");
			//返还用户信誉额
			$this->SQL->Execute("UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money},cash=cash+{$sell_gain}-{$sell_cost_money} where username='{$row['user']}'");
			//插入帐单信息
			$this->SQL->Execute("insert into `bill_log` set bill_type='手续费',agent='{$row['agent']}',username='{$row['user']}',money='-{$sell_cost_money}',remark='【系统平仓】卖出股票{$row['stock_name']}({$row['stock_code']})的手续费！卖出价:￥{$sell_price}, 数量:{$row['bull_num']}(手)！',add_date='{$sell_date}',add_time='{$sell_time}'");
			//盈亏
			$this->SQL->Execute("insert into `bill_log` set bill_type='浮盈',agent='{$row['agent']}',username='{$row['user']}',money='{$sell_gain}',remark='【系统平仓】卖出股票 {$row['stock_name']}({$row['stock_code']})的浮盈！卖出价:￥{$sell_price}, 数量:{$row['bull_num']}(手)',add_date='{$sell_date}',add_time='{$sell_time}'");
			return "true|平仓成功！";
		}
		else
		{
			return "false|平仓失败！不存在记录！";
		}
	}
	
	//获取指定股票代码的行情
	function Quote($code='sh600001')
	{
		require_once('curl_http.php');
		$curl=&new Curl_HTTP_Client();
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$code,"",10);
		$html_data=iconv("GB2312","UTF-8",$html_data);
		$data='';
		if($html_data)
		{
			preg_match("/str_(.+?)\";/is",$html_data,$data);
			$Net = str_replace('="',",",$data[1]);
			$quote = explode(',',$Net);
		}
		else
		{
			$quote = false;
		}
		return $quote;
	}
	
	//计算用户的可用保证金
	function AvailableCash($username='')
	{
		if(empty($username)) return 0;
		$usercancash = 0;
		$user = $this->SQL->GetRow("select cash,money from `user_users` where username='{$username}'");
		//用户持仓
		$total_bull_money = 0;
		$row = $this->SQL->GetRow("Select sum(bull_money) as tt from `buy_deal` where sell='0' and user='{$username}'");
		if(is_array($row))
		{
			$total_bull_money = round($row['tt'] * 0.1,2);
		}
		//用户委托
		$total_trust_money = 0;
		$row=$this->SQL->GetRow("select sum(money) as tru from `buy_trust` where app='1' and trust_type='1' and user='{$username}'");
		if(is_array($row))
		{
			$total_trust_money = round($row['tru']*0.1,2);
		}
		$usercancash = $user['cash'] - $total_bull_money - $total_trust_money;
		if($usercancash<0) $usercancash = 0;
		return $usercancash;
	}
	
	function save_day($tmA,$tmB)
	{
		$tmA = strtotime($tmA);
		$tmB = strtotime($tmB);
		$Day=($tmA-$tmB)/(24*60*60);
		$t = time();
		$dd = 24*60*60;
		$wnum=0;
		//扫描多少个双休日
		for($i=0;$i<=$Day;$i++)
		{
			$gala=date('Y-m-d',$tmA-$i*$dd);
			//检测本日是否为法定假日
			$row=$this->SQL->GetRow("SELECT id FROM gala_config WHERE gala_date='{$gala}'");
			$Gala=($row)?1:0;
			$week=date('w',$tmA-$i*$dd);
			if($week==0 || $week==6 || $Gala==1)
			$wnum++;
		}
		if($this->config['rest_filter']==1)
		$Day=$Day-$wnum;
		return $Day;
	}
	//检测是否开市时间
	function startTIME()
	{
		$row=$this->SQL->GetRows("SELECT * FROM gala_config WHERE start_date>='{$this->sys_date}' or end_date='{$this->sys_date}'");
		$Gala=($row)?1:0;
		$dqtime=time();
		$Week=date('w');
		$opTimeAm_start=strtotime($this->sys_date.' '.$this->config['open_AM_Start'].':00');
		$opTimeAm_end=strtotime($this->sys_date.' '.$this->config['open_AM_End'].':00');
		$opTimePm_start=strtotime($this->sys_date.' '.$this->config['open_PM_Start'].':00');
		$opTimePm_end=strtotime($this->sys_date.' '.$this->config['open_PM_End'].':00');
		//条件 （非星期六和星期天及指定假日）（当前时间>=上午开市时间 与 当前时间 <=上午收市时间）（当前时间>=下午开市时间 与 当前时间 <=下午收市时间）
		if(($Week<6 && $Week>0 && $Gala==0) && (($dqtime>=$opTimeAm_start && $dqtime<=$opTimeAm_end) || ($dqtime>=$opTimePm_start && $dqtime<=$opTimePm_end)))
		$open=1;
		else
		$open=0;
		return $open;
	}
	function SellTIME()
	{
		$row=$this->SQL->GetRows("SELECT * FROM gala_config WHERE start_date>='{$this->sys_date}' or end_date='{$this->sys_date}'");
		$Gala=($row)?1:0;
		$dqtime=time();
		$Week=date('w');
		$opTimeAm_start=strtotime($this->sys_date.' '.$this->config['open_AM_Start'].':00');
		$opTimeAm_end=strtotime($this->sys_date.' '.$this->config['open_AM_End'].':00');
		$opTimePm_start=strtotime($this->sys_date.' '.$this->config['open_PM_Start'].':00');
		$opTimePm_end=strtotime($this->sys_date.' '.$this->config['open_PM_End'].':00');
		//条件 （非星期六和星期天及指定假日）（当前时间>=上午开市时间 与 当前时间 <=上午收市时间）（当前时间>=下午开市时间 与 当前时间 <=下午收市时间）
		if(($Week<6 && $Week>0 && $Gala==0) && (($dqtime>=$opTimeAm_start && $dqtime<=$opTimeAm_end) || ($dqtime>=$opTimePm_start && $dqtime<=$opTimePm_end)))
		$open=1;
		else
		$open=0;
		return $open;
	}
	function gourl ($str, $url = "")
	{
		//header("Content-Type: text/html; charset=utf-8");
		$src = array("/\n/i", "/'|#/i");
		$des = array("\\n", "\\\\\\0");
		$str = preg_replace($src, $des, $str);
		if ($url == "") {
			$url = "history.go(-1)";
		} elseif ($url == "close") {
			$url = "window.close()";
		} elseif ($url == "back") {
			$url = "history.back()";
		} elseif ($url == "reload") {
			$url = "window.opener.location.reload();window.close()";
		} elseif ($url == "closewin") {
			$url = "parent.iwindow_CLOSE();";
		} elseif ($str == "top" ) {
			$url = "parent.top.location='$url'";
		} else {
			$url = "document.location.href = '$url'";
		}

		if ($str != "" and ($str != "top" and $str != "")) {
			echo "<script language='javascript'>alert('$str');$url;</script>";
		} else {
			echo "<script language='javascript'>$url;</script>";
		}
		exit;
	}
	
	function Alert($title='系统提示',$message,$do)
	{
		$html = "<script type='text/javascript'>";
		$html .= "  parent.ymPrompt.alert({title:'{$title}',message:'{$message}'";
		if($do!='')
		{
			$html .= ",handler:function(){{$do}}";
		}
		$html .= "});</script>";
		print $html;
		exit();
	}
	
	function errorInfo($title='系统提示',$message,$do)
	{
		$html = "<script type='text/javascript'>";
		$html .= "  parent.ymPrompt.errorInfo({title:'{$title}',message:'{$message}'";
		if($do!='')
		{
			$html .= ",handler:function(){{$do}}";
		}
		$html .= "});</script>";
		print $html;
		exit();
	}
	
	function succeedInfo($title='系统提示',$message,$do)
	{
		$html = "<script type='text/javascript'>";
		$html .= "  parent.ymPrompt.succeedInfo({title:'{$title}',message:'{$message}'";
		if($do!='')
		{
			$html .= ",handler:function(){{$do}}";
		}
		$html .= "});</script>";
		print $html;
		exit();
	}
	
	function dateFrom($date)
	{
		$dateTemp=explode('-',$date);
		$YYYY = $dateTemp[0];
		$MM   = ($dateTemp[1]<10)?'0'.abs($dateTemp[1]):$dateTemp[1];
		$DD   = ($dateTemp[2]<10)?'0'.abs($dateTemp[2]):$dateTemp[2];
		return $YYYY.'-'.$MM.'-'.$DD;
	}
	/**
	 * Redirects a user to another page
	 * @param string $url - The new URL to go to
	 * @return void
	 */
	function redirect($type,$url,$msg='') {
		switch ($type) {
			default:
				header('Location: ' . $url);
				exit;
				break;
			case 2:
				echo <<<META
<html><head><meta http-equiv="Refresh" content="0;URL={$url}" /></head><body></body></html>
META;
				break;
case 3:
	echo <<<SCRIPT
<html><body><script>location="{$url}";</script></body></html>
SCRIPT;
	break;
}
}


}
?>