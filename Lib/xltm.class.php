<?php
date_default_timezone_set ('Asia/Hong_Kong');
header("Content-Type: text/html; charset=utf-8");
$xltm= new xltm();
$judge=0;
if(strpos($_SERVER["HTTP_USER_AGENT"],"Mobile")){
	$judge=1;
}
//echo $judge;exit;
class xltm{
	function xltm(){
		global $database_prefix,$database_type,$database_server_name,$database_username,$database_password,$database_name,$database_port,$activation_code,$database_persistent;
		$this->sys_date=date('Y-m-d',time());
		$this->sys_time=date('Y-m-d H:i:s',time());
		require_once('Security.class.php');
		$this->Security = new Security($this);
		//加载 SQL 类
		require_once('tpls_mysql.php');
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
			//if($judge!=1){
			$this->user_info = array();
			//删除任何过时登陆数据和游客数据
			$this->Security->remove_old_tries();
			//获取用户类
			require_once('User.class.php');
			$this->User = new User($this);
			//session类
			require_once('Session.class.php');
			$this->Session = new Session($this);
			$this->Session->clear_old_sessions();
			
			// 查看是否有效或活动用户
			$this->User->validate_login();

			// 如果用户已经登陆则设置用户最后活动时间
			//print_r($this->user_info);
			//exit();
			if ($this->user_info['username'] != '') {
				$this->SQL->Execute("update user_users set last_action='".time()."' where id='{$this->user_info['id']}'");
			}

			if(Load_Info == 1 && $this->user_info['username'] == '')  {
				$this->outlogin();
			}

			if ($this->user_info['blocked'] == 'yes') {
				die('对不起你禁止访问本站');
			}
			require_once("buy.class.php");
			$this->buy = new buy($this);
			//}
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
		if($exit==1) {exit();}
	}
	function outlogin()
	{
		$show='<script type="text/javascript">';
		$show .='alert("登录超时，请点后“确定”后返回登录口！");document.location.href="login.php";</script>';
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
	
	//帐单明细
	function bill_log($username,$agent='zongdai',$bill_type='手续费',$money='0',$remark)
	{
		$qary = array(
			'username'=>$username,
			'bill_type'=>$bill_type,
			'agent'=>$agent,
			'money'=>$money,
			'remark'=>$remark,
			'add_date'=>$this->sys_date,
			'add_time'=>$this->sys_time
		);
		$query = $this->array2str($qary);
		$this->SQL->Execute("insert into `bill_log` set {$query}");
	}
	
	//计算留仓日数
	function save_day($tmA,$tmB)
	{
		$tmA = strtotime($tmA);
		$tmB = strtotime($tmB);
		$dd = 24*60*60;
		$Day=($tmA-$tmB)/$dd;
		$t = time();
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
		if($this->config['rest_filter']==0)
		{
			$Day=$Day-$wnum;
		}
		return $Day;
	}
	//检测是否开市时间
	function startTIME()
	{	
		$row=$this->SQL->GetRows("SELECT * FROM gala_config WHERE gala_date='{$this->sys_date}'");
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
		$dqtime=time();
		$Week=date('w');
		$SellAm_start=strtotime($this->sys_date.' '.$this->config['sell_AM_Start'].':00');
		$SellAm_end=strtotime($this->sys_date.' '.$this->config['sell_AM_End'].':00');
		$SellPm_start=strtotime($this->sys_date.' '.$this->config['sell_PM_Start'].':00');
		$SellPm_end=strtotime($this->sys_date.' '.$this->config['sell_PM_End'].':00');
		//条件 （非星期六和星期天及指定假日）（当前时间>=上午开市时间 与 当前时间 <=上午收市时间）（当前时间>=下午开市时间 与 当前时间 <=下午收市时间）
		if(($Week<6 && $Week>0) && (($dqtime>=$SellAm_start && $dqtime<=$SellAm_end) || ($dqtime>=$SellPm_start && $dqtime<=$SellPm_end)))
		$open=1;
		else
		$open=1;
		return $open;
	}
	
	//检测是否可提款时间
	function AtmTime()
	{
/*
		$time = time();
		echo  $this->sys_date. ' ' . $this->config['atm_AM_Start'] .'-'. $this->sys_date.' ' . $this->config['atm_AM_End'];
		echo '<br>';
		echo  $this->sys_date. ' ' . $this->config['atm_PM_Start'] .'-'. $this->sys_date.' ' . $this->config['atm_PM_End'];
	echo var_dump($time>=strtotime($this->sys_date. ' ' . $this->config['atm_AM_Start'].':00' ) and $time<=strtotime($this->sys_date.' ' . $this->config['atm_AM_End'].':00'));

	echo var_dump($time>=strtotime($this->sys_date.' ' . $this->config['atm_PM_Start'].':00') and $time<=strtotime($this->sys_date.' ' . $this->config['atm_PM_End'].':00'));

	echo date("Y-m-d H:i:s");
			echo '<br>';
			echo $time;
			echo '<br>';
			echo strtotime($this->sys_date.' ' . $this->config['atm_PM_Start'].':00');
			echo '<br>';
			echo strtotime($this->sys_date.' ' . $this->config['atm_PM_End'].':00');

exit;


		//是否开市时间
		if($this->startTIME()) return false;
*/
		//判断是否周六或周日
		$Week=date('w');
		if($Week==6 && $Week==0) return false;

		//判断是否休市
		$row=$this->SQL->GetRows("SELECT * FROM gala_config WHERE gala_date='{$this->sys_date}'");
		$Gala=($row)?1:0;
		if($Gala == 1) return false;

		//判断时间
		$time = time();
		if($time>=strtotime($this->sys_date. ' ' . $this->config['atm_AM_Start'].':00' ) and $time<=strtotime($this->sys_date.' ' . $this->config['atm_AM_End'].':00'))
		{
			return true;
		}
		elseif($time>=strtotime($this->sys_date.' ' . $this->config['atm_PM_Start'].':00') and $time<=strtotime($this->sys_date.' ' . $this->config['atm_PM_End'].':00'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	//计算用户的可用保证金
	function AvailableCash($username='')
	{
		if(empty($username)) $username = $this->user_info['username'];
		if(empty($username)) return 0;
		$usercancash = 0;
		$user = $this->SQL->GetRow("select cash,money from `user_users` where username='{$username}'");
		//用户持仓
		$total_bull_money = 0;
		$row = $this->SQL->GetRow("Select sum(bull_money) as tt from `buy_deal` where sell='0' and user='{$username}'");
		if(is_array($row))
		{
			//exit( round($row['tt'] * ( 1/$this->config['cost_exchange_rate'] ),2) );
			//$total_bull_money = round($row['tt'] * ( 1/$this->config['cost_exchange_rate'] ),2);
			$total_bull_money = bcmul($row['tt'], 1/$this->config['cost_exchange_rate'], 2);
		}
		//用户委托
		$total_trust_money = 0;
		$row=$this->SQL->GetRow("select sum(money) as tru from `buy_trust` where app='1' and trust_type='1' and user='{$username}'");
		if(is_array($row))
		{
			//$total_trust_money = round($row['tru']* ( 1/$this->config['cost_exchange_rate'] ) ,2);
			$total_trust_money = bcmul($row['tru'], 1/$this->config['cost_exchange_rate'], 2);
		}
		$usercancash = bcsub(bcsub($user['cash'], $total_bull_money, 2), $total_trust_money, 2);
		//if($usercancash<0) $usercancash = 0;
		return $usercancash;
	}
	
	//计算用户的可提款金额
	function AvailableAtmCash($username='')
	{
		if(empty($username)) $username = $this->user_info['username'];
		if(empty($username)) return 0;
		$atmcash = 0;
		$user = $this->SQL->GetRow("select cash,money from `user_users` where username='{$username}'");
		//用户持仓
		$total_bull_money = 0;
		$row = $this->SQL->GetRow("Select sum(bull_money) as tt from `buy_deal` where sell='0' and user='{$username}'");
		if(is_array($row))
		{
			$total_bull_money = round($row['tt'] * ( 1/$this->config['cost_exchange_rate'] + 0.03 ),2);
		}
		//用户委托
		$total_trust_money = 0;
		$row=$this->SQL->GetRow("select sum(money) as tru from `buy_trust` where app='1' and trust_type='1' and user='{$username}'");
		if(is_array($row))
		{
			$total_trust_money = round($row['tru']*( 1/$this->config['cost_exchange_rate'] + 0.03 ) ,2);
		}
		$atmcash = $user['cash'] - $total_bull_money - $total_trust_money;
		if($atmcash<0) $atmcash = 0;
		return $atmcash;
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


	function baocang()
	{
		$bc_num = 0;
		//if($u=$this->SQL->GetRow("select * from `user_users` where demo='0' and cash>'0' and last_login>'0' and active='yes' and blocked='no' and username='".$this->user_info['username']."'"))
		if($u=$this->SQL->GetRow("select * from `user_users` where demo='0' and last_login>'0' and active='yes' and blocked='no' and username='".$this->user_info['username']."'"))
		{
			//当前保证金
			//$cash = round($u['cash']* $this->config['baocang_precent'] /100 ,2); //亏损90%爆仓
			$cash = $u['cash'] ;
			if($cash<=0) $cash = 0;
			//读取持仓数据
			if($deal=$this->SQL->GetRows("select * from `buy_deal` where sell='0' and user='{$u['username']}'"))
			{
				$codelist = "";
				foreach($deal as $c)
				{
					$codelist .= (empty($codelist) ? '' : ',').$c['stock_type'].$c['stock_code'];
				}
				//读取持仓股票当前价格
				require_once('curl_http.php');
				$curl=&new Curl_HTTP_Client();
				$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$codelist,"",10);
				$html_data=iconv("GB2312","UTF-8",$html_data);
				$data='';
				$quote = '';
				if($html_data)
				{
					preg_match_all("/str_(.+?)\";/is",$html_data,$data);
					foreach($data[1] as $k)
					{
						$k=str_replace('="',",",$k);
						$q=explode(',',$k);
						$quote .= (empty($quote)?'':',') . $q[4];
					}
				}
				
				if($quote)
				{
					$q = explode(',',$quote);
					$i=0;
					$gain_total = 0;
					$sell_cost_money_total = 0;
					$dc_money_total = 0;

					foreach($deal as $d)
					{
						$gain = 0;
						if($q[$i]<=0) continue; //如果获取到价格为0，不计算盈亏
						$sell_money = round($q[$i] * $d['bull_num'] * 100 ,2);		//当前卖出的金额。
						$sell_cost_money = round($sell_money*$d['sell_cost'],2);	//手续费
						$dc_money		 = $d['dc_money'];							//点差
						$dc_money		 = 0;							//点差

						if($d['buy_type']=='1') //
						{
							$gain = $sell_money - $d['bull_money'] - $sell_cost_money - $dc_money - $d['bull_cost_money'];
						}
						else
						{
							$gain = $d['bull_money'] - $sell_money  - $sell_cost_money - $dc_money - $d['bull_cost_money'];
						}
						$gain_total += $gain;
						$sell_cost_money_total += $d['bull_cost_money'];
						$dc_money_total += $dc_money;
						$i++;
					}
					//当前保证金+持仓手续费+持仓点差费去乘以爆仓比例
					//$cash = round(($cash + $sell_cost_money_total + $dc_money_total) * $this->config['baocang_precent'] /100 ,2);
					$cash = round(($cash + $sell_cost_money_total + $dc_money_total) * 70 /100 ,2);
					//爆仓
					if($gain_total<0 && ($cash + $gain_total<=0))
					{
						$bc_num ++;
						//平仓股票
						$i = 0;
						foreach($deal as $d)
						{
							$current_sell_price = $q[$i]>0 ? $q[$i] : $d['bull_price'];
							$this->stock_sell($d['id'],$current_sell_price,$this->sys_time);
							$i++;
						}

						//给用户发送爆仓通知短信
						$this->SQL->Execute("insert into `message` set username='系统管理员',tousername='{$u['username']}',title='系统通知：您的股票在{$this->sys_time}爆仓！',content='<p>尊敬的用户：<br />很抱歉的通知您，截至 {$this->sys_time} 您的持仓股票总亏损达到：{$gain_total}，高于您当前保证金：{$cash}<br />系统自动爆仓您所持有的股票信息！请您继续充值，开始下一轮的财富冲刺！祝您好运！！！',isread='0',add_time='{$this->sys_time}'");
						//通知管理员
						$this->SQL->Execute("insert into `message` set username='系统自动发送',tousername='administrator',title='用户【{$u['username']}】在{$this->sys_time}股票被爆仓！！',content='<p>用 户 名：{$u['username']}<br />代 理 商：{$u['agent']}<br />总 亏 损：{$gain_total}<br /> 保 证 金：{$cash}<br />爆仓时间：{$this->sys_time}',isread='0',add_time='{$this->sys_time}'");
					}
				}
			}

		}
		return $bc_num;
	}

	/*后台管理员强制平仓*/
	function stock_sell($saleID,$sell_price=0,$sell_time)
	{
		if(!$sell_time) $sell_time = date('Y-m-d H:i:s');
		if($row=$this->SQL->GetRow("SELECT * FROM `buy_deal` WHERE id='{$saleID}' and sell='0'"))
		{
			//插入委托表
			$SQL_trust=array(
			'demo'=>$row['demo'],
			'user'=>$row['user'],
			'code'=>$row['stock_code'],
			'type'=>$row['stock_type'],
			'name'=>$row['stock_name'],
			'trust_type'=>2,
			'buy_type'=>$row['buy_type'],
			'price'=>$sell_price,
			'app'=>2,
			'num'=>$row['bull_num'],
			'money'=>round($row['bull_num']*100*$sell_price,2),
			'agent'=>$row['agent'],
			'stock_trust_date'=>$this->sys_date,
			'stock_trust_time'=>$sell_time,
			'stock_deal_time'=>$sell_time
			);
			$insert_trust =$this->array2str($SQL_trust);
			$this->SQL->Execute("insert into `buy_trust` set $insert_trust");
			//委托卖单ID
			$newID=$this->SQL->LastRowId();
			//卖出金额
			$sell_money=round($row['bull_num']*100*$sell_price,2);
			//会员卖出手续费金额
			$sell_cost_money=round($sell_money*$row['sell_cost'],2);
			//exit( $sell_cost_money );
			//代理商获得的水费
			$agent_sell_cost_money = round($sell_cost_money*$row['agent_ret']/100,2);
			if($row['buy_type']==1)
			{
				//(多)盈亏计算 卖出金额-买入金额-买入手续费-卖出手续费
				$sell_gain=$sell_money-$row['bull_money'];
				$gain=$sell_money-$row['bull_money']-$row['bull_cost_money']-$sell_cost_money-$row['save_money']-$row['dc_money'];
			}else {
				//(空)盈亏计算 买入金额-卖出金额-买入手续费-卖出手续费
				$sell_gain=$row['bull_money']-$sell_money;
				$gain=$row['bull_money']-$sell_money-$row['bull_cost_money']-$sell_cost_money-$row['save_money']-$row['dc_money'];
			}
			$SQL_deal=array(
			'sell'=>1, //设置为已经卖出
			'sell_price'=>$sell_price,
			'sell_cost_money'=>$sell_cost_money,
			'sell_money'=>$sell_money,
			'sell_trust_id'=>$newID,
			'sell_gain'=>$sell_gain,
			'gain'=>$gain,
			'agent_sell_money'=>$agent_sell_cost_money,
			'sell_trust_date'=>$this->sys_date,
			'sell_trust_time'=>$sell_time,
			'can_sell_num'=>0
			);
			$update_deal=$this->array2str($SQL_deal);
			@$fp = fopen('./log/bcang.txt','a');
			@fwrite($fp,$update_deal."\r\n");
			@fclose($fp);
			$this->SQL->Execute("UPDATE `buy_deal` set $update_deal where id='{$saleID}'");
			//返还用户可用值(又及保证金)
			$this->SQL->Execute("UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money},cash=cash+{$sell_gain}-{$sell_cost_money} where username='{$row['user']}'");
			//插入帐单信息
			$this->bill_log($row['user'],$row['agent'],'手续费',(0-$sell_cost_money),"[{$saleID}]卖出股票{$row['stock_name']}({$row['stock_code']})手续费!卖出总金额:￥{$sell_money}, 卖出价:￥{$sell_price}, 数量:{$row['bull_num']}手！",$saleID);
			$this->bill_log($row['user'],$row['agent'],'浮盈',$sell_gain,$_SERVER['REMOTE_ADDR']."1[{$saleID}]卖出股票{$row['stock_name']}({$row['stock_code']})浮盈!卖出总金额:￥{$sell_money}, 卖出价:￥{$sell_price}, 数量:{$row['bull_num']}手！",$saleID);
			return '平仓成功!!';
		}else {
			return '该交易已经平仓或不存在';
		}
	}

function ussale_part($sell_id,$sell_num=0,$price_type=1,$sell_price=0)
{	
		
		
		
		
		$SellTIME=$this->SellTIME();
		$sell_cost = 0;
		if($SellTIME==0)
		{
			exit("false|休市中");
		}
		if($sell_num==0||$sell_num<0)
		{
			exit("false|卖出数量必须大于0");
		}
		if($sell_id=='')
		{
			exit("false|订单号不能为空");
		}
		if($row=$this->SQL->GetRow("SELECT * FROM `buy_deal` WHERE user='{$this->user_info['username']}' and id='{$sell_id}' and app='0'"))
		{
			$stock_code=$this->SQL->GetRow("select * from `usstock_code` where code='{$row['stock_code']}'");
			if($stock_code['can_sell']==0 && $stock_code['can_sell']!='')
				exit("false|该股已经满仓不能卖出！");
			
				
			//获取当前价
			$code =strtoupper($row['stock_code']);
			$stockarea =$stock_code['type'];
			$url = "http://gu.qq.com/us".$code.".".$stockarea."/gg";
	
			$reg = array("xianjia"=>array(".item-1 span:eq(0)","text"),"jinkai"=>array(".col-2.fr span:eq(1)","text"),"zuoshou"=>array(".col-2.fr span:eq(9)","text"),"zuigao"=>array(".col-2.fr span:eq(3)","text"),"zuidi"=>array(".col-2.fr span:eq(11)","text"),"chengjiao"=>array(".col-2.fr span:eq(7)","text"),"shizhi"=>array(".col-2.fr span:eq(15)","text"),"zd"=>array(".item-2 span:eq(0)","text"),"zdf"=>array(".item-2 span:eq(1)","text"),"codename"=>array(".title_bg h1:eq(0)","text"));
			$qy = new QueryList($url,$reg);
			$arr = $qy->jsonArr;
			//$arrstr=$arr[0][codename].','.$arr[0][xianjia].','.$arr[0][shizhi].','.$arr[0][jinkai].','.$arr[0][zuigao].','.$arr[0][zuidi].','.$arr[0][zuoshou].','.$arr[0][zd].','.$arr[0][zdf].','.$arr[0][chengjiao];
			if(floatval($arr[0][xianjia])==0 ||$arr[0][xianjia]=='')
				exit("false|该股已经停牌！");
			//市价卖出多和空委托价格 多=$Net[7] 空=$Net[6]
			//$cur_price = ($row['buy_type']=='1')?$Net[7]:$Net[6];
			$cur_price = floatval($arr[0][xianjia]);



			if($price_type==1) $sell_price = $cur_price; //如果是市价卖出，设置卖出价等于当前价
			$can_sell_num = $row['bull_num']-$sell_num;
			
			//委托价卖出或当前的价格小于委托价做委托记录
			if($price_type==2 && (($row['buy_type']==1 && $sell_price>$cur_price) || ($row['buy_type']==2 && $sell_price<$cur_price)))
			{
				
			}
			else //市价卖出或当前价大于等于委托价，卖出股票
			{

				//2012-4-12 李兴，这里加入滑价金额。 单价加滑价后，再四舍五入。==================================
				if ( $price_type==1){		//市价卖出，才加滑价。
					$price_float	= 0 ;
					$buy_type = $row['buy_type'] ;

					if ( $buy_type == 1 ){		//买涨
						$price_float = $cur_price * $this->config['up_float'] ;
						$new_price = $cur_price - $price_float;
						$new_price = round($new_price, 2);

					}elseif ( $buy_type == 2 ){	//买跌
						$price_float = $cur_price * $this->config['down_float'] ;
						$new_price = $cur_price + $price_float;
						$new_price = round($new_price, 2);

					}else{
						$new_price = $cur_price;

					}
					$sell_price = $cur_price = $new_price;

					$this->config['ttt_sell_price'] = $sell_price; 
				}

//exit($new_price);
				//2012-4-12 李兴，这里加入滑价金额。 单价加滑价后，再四舍五入。==================================



				//卖出股票操作
				$this->user_log(9,"卖出股票:{$row['stock_code']}({$row['bull_num']}手)，卖出价:{$cur_price}。");
				//插入卖出股票委托记录
				$SQL_trust=array(
					'user'=>$row['user'],
					'code'=>$row['stock_code'],
					'type'=>$row['stock_type'],
					'name'=>$row['stock_name'],
					'trust_type'=>2, //卖出
					'buy_type'=>$row['buy_type'],
					'price'=>$cur_price,
					'app'=>2,
					'num'=>$row['bull_num'],
					'money'=>round($row['bull_num']*100*$cur_price,2),
					'agent'=>$row['agent'],
					'stock_trust_date'=>$this->sys_date,
					'stock_trust_time'=>$this->sys_time,
					'stock_deal_time'=>$this->sys_time
				);
				$insert_trust =$this->array2str($SQL_trust);
				$this->SQL->Execute("insert into buy_trust set $insert_trust");
				//委托卖单ID
				$newID=$this->SQL->LastRowId();
				//卖出总金额
				$sell_money = round($cur_price*$row['bull_num']*100,2);
				//会员卖出手续费
				$sell_cost_money = round($sell_money * $row['sell_cost'],2);
				//留仓费用
				$save_money=$row['save_money'];
				//点差费
				$dc_money = $row['dc_money'];

$dc_sellmoney = 0;


if ( $dc_money == 0	){

						
			$dc_num		= $stock_code["dc"];
			$dc_num1	= 0;
			$money		= $sell_money ;
			$new_price  = $sell_price;
			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  开始 ====================
			/*
			include_once("./Lib/curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$st['type'].$st['code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);

			preg_match("/\"(.+?)\";/is",$html_data,$data);
			$Net=split(',',$data[1]);
			*/

			$chajia1 = $arr[0][zdf];
			$chajia = abs(floatval(str_replace('%', '', $chajia1)));
			
			$nowchengjiaoe = floatval($arr[0][chengjiao])*10000;
			// $chajia  这个就是涨跌百分比。
			for( $d = 5; $d <=9; $d++){
				if ($chajia >= $d ){		//涨跌率大于 设置的百分比，就读取相应的点差率值。
					$dc_num1 = $this->config['dc'.$d];
				}
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			
			//成交额大于五千万 的点差率。
			if ( $nowchengjiaoe > 50000000){		
				$dc_num1 = $this->config['dc_wan'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于8千万 的点差率。
			if ( $nowchengjiaoe > 80000000){		
				$dc_num1 = $this->config['dc_wan2'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于1.2亿 的点差率。
			if ( $nowchengjiaoe > 120000000){		
				$dc_num1 = $this->config['dc_wan3'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//股票股价低于5元的股票点差率
			if ( $new_price < 5 ){
				$dc_num1 = $this->config['dc_di'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  结束 ====================

			$dc_money = round(($dc_num/1000)*$money,2);
			
			$dc_sellmoney = $dc_money;
}

				//允许卖出时间内卖出加收手续费
				$canSellTime=date('Y-m-d H:i:s',time()-($this->config['sel_max_time']*60));
				$cost_sell_limit = $row['bull_trust_time']>$canSellTime ? $this->config['cost_sell_limit'] : 0;
				if($cost_sell_limit>0)
				{
					$cost_sell_limit_money = round($sell_money*$cost_sell_limit,2);
					$dc_money = $dc_money + $cost_sell_limit_money;
					//代理商水费
					$agent_sell_money = round(($sell_cost_money+cost_sell_limit_money)*$row['agent_ret']/100,2);
				}
				else
				{
					$cost_sell_limit_money = 0;
					//代理商水费
					$agent_sell_money = round($sell_cost_money*$row['agent_reg']/100,2);
				}
				
				if($row['buy_type']==1) //买升
				{
					//(多)盈亏计算 卖出金额-买入金额-卖出手续费
					$sell_gain = $sell_money - $row['bull_money'];
					$gain = $sell_money - $row['bull_money'] - $row['bull_cost_money'] - $sell_cost_money - $save_money - $dc_money;
				}else { //买跌
					//(空)盈亏计算 买入金额-卖出金额-买入手续费-卖出手续费
					$sell_gain = $row['bull_money'] - $sell_money;
					$gain = $row['bull_money'] - $sell_money - $row['bull_cost_money'] - $sell_cost_money - $save_money - $dc_money;
				}
				//更新持仓单状态
				$SQL_deal=array(
				'sell'=>1, //设置为已经卖出
				'can_sell_num'=>0,
				'sell_price'=>$sell_price,
				'sell_cost_money'=>$sell_cost_money,
				'dc_money'=>$dc_money,
				'sell_money'=>$sell_money,
				'sell_trust_id'=>$newID,
				'sell_gain'=>$sell_gain,
				'gain'=>$gain,
				'agent_sell_money'=>$agent_sell_money,
				'sell_trust_date'=>$this->sys_date,
				'sell_trust_time'=>$this->sys_time
				);
				$update_deal=$this->array2str($SQL_deal);
				
				
			$this->SQL->_Message($_SERVER['REMOTE_ADDR']." Update `buy_deal` set {$update_deal} where id='{$sell_id}'");
			$this->SQL->_Message($_SERVER['REMOTE_ADDR']." UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney},cash=cash+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney} where username='{$row['user']}'");
				
				$this->SQL->Execute("Update `buy_deal` set {$update_deal} where id='{$sell_id}'");
				
								$ip = $_SERVER['REMOTE_ADDR'];

				//返还用户可用值(以及保证金)
				$this->SQL->Execute("UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney},cash=cash+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney} where username='{$row['user']}'");
				//添加用户帐单信息
				$this->bill_log($row['user'],$row['agent'],'手续费',(0-$sell_cost_money),$ip."[{$sell_id}]卖出股票{$row['stock_name']}({$row['stock_code']})的手续费！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！");
				if($cost_sell_limit_money>0)
				{
					$this->bill_log($row['user'],$row['agent'],'点差费',(0-$cost_sell_limit_money),"[{$sell_id}]卖出股票{$row['stock_name']}({$row['stock_code']})的点差费({$this->config['sel_max_time']}分钟内卖出加收)！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！, 加收手续率:".($this->config['cost_sell_limit']*100)."%！");
				}
				
				if($dc_sellmoney>0)
				{
					$this->bill_log($row['user'],$row['agent'],'点差费',(0-$dc_sellmoney),"[{$sell_id}]卖出股票{$row['stock_name']}({$row['stock_code']})的点差费(卖出)！");
				}
				
				$this->bill_log($row['user'],$row['agent'],'浮盈',$sell_gain,$ip."[{$sell_id}]卖出股票{$row['stock_name']}({$row['stock_code']})的浮盈！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！");
				exit("true|交易成功");
			}
		}else {
			exit("false|该交易已经平仓");
		}

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
