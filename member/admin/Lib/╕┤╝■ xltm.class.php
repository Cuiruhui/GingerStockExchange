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
		$this->sys_date=date('Y-m-d',time());
		$this->sys_time=date('Y-m-d H:i:s',time());
		require_once('Security.class.php');
		$this->Security = new Security($this);
		//加载 SQL 类
		require_once('tpls.mysql.php');
		require_once('database_info.php');
		$this->SQL = new MYSQL($database_server_name,$database_username,$database_password,$database_name,$database_port,$this);
		require_once('tpls.class.php5.php');
		require_once('tpls.plugin.html.php');
		require_once('tpls.plugin.bypage.php');
		require_once('tpls.plugin.navbar.php');
		require_once('tpls.plugin.aggregate.php');
		require_once('User.class.php');
		$this->User = new User($this);
		$this->tpls = new clsTinyButStrong;
		// 获取配置信息，并指定$config
		$re_info=$this->SQL->GetRows("SELECT * FROM `user_config`");
		foreach ($re_info as $info)
		{
			$this->config[$info['name']] = $info['value'];
		}
		if (Load_Info) {
			//解锁12小时前因密码输入错误而锁定的用户
			$this->Security->remove_old_tries();
			//获取用户类
			//session类
			require_once('Session.class.php');
			$this->Session = new Session($this);
			//清除指定时间过期的Session
			$this->Session->clear_old_sessions();
			// 查看是否有效或活动用户
			$this->User->validate_login();

			// 如果用户已经登陆则设置用户最后活动时间
			if ($this->user_info['username'] != '') {
				$this->SQL->Execute("update user_admin set last_action='".time()."' where id='{$this->user_info['id']}'");
			}
			if(Load_Info==1 && $this->user_info['username']=='')
			$this->outlogin();
			if ($this->user_info['blocked'] == 'yes') {
				$this->gourl('不允许访问本站！','login.php');
			}
		}
	}
	//弹出信息窗口提示
	function ShowPrompt($str,$js="",$exit=0)
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
	
	//帐单明细
	function bill_log($username,$agent='zongdai',$bill_type='手续费',$money='0',$remark='',$dealid=0)
	{
		$qary = array(
			'username'=>$username,
			'bill_type'=>$bill_type,
			'agent'=>$agent,
			'money'=>$money,
			'remark'=>$remark,
			'add_date'=>$this->sys_date,
			'add_time'=>$this->sys_time,
			'dealid'=>$dealid
		);
		$query = $this->array2str($qary);
		$this->SQL->Execute("insert into `bill_log` set {$query}");
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
	
	function calc_day()
	{
		$maxday = $this->config['cost_save_day'];
		$saveDate = date('Y-m-d',$maxday*24*3600);
		$t = time();
		$dd = 24*60*60;
		//扫描多少个双休日
		$k=0;
		for($i=0;$i<=30;$i++)
		{
			$gala=date('Y-m-d',$t-$i*$dd);
			//检测本日是否为法定假日
			$row=$this->SQL->GetRow("SELECT id FROM `gala_config` WHERE gala_date='{$gala}'");
			$Gala=($row)?1:0;
			$week=date('w',$t-$i*$dd);
			if($week!=0 && $week!=6 && $Gala!=1)
			{
				$k++;
				if($k>=$maxday) //达到预定的天数
				{
					$saveDate = date('Y-m-d',($t-$i*$dd));
					break;
				}
			}
		}
		return $saveDate;
	}
	
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
			$total_bull_money = round($row['tt'] * ( 1 / $this->config['cost_exchange_rate'] ) ,2);
		}
		//用户委托
		$total_trust_money = 0;
		$row=$this->SQL->GetRow("select sum(money) as tru from `buy_trust` where app='1' and trust_type='1' and user='{$username}'");
		if(is_array($row))
		{
			$total_trust_money = round($row['tru']*( 1 / $this->config['cost_exchange_rate'] ),2);
		}
		$usercancash = $user['cash'] - $total_bull_money - $total_trust_money;
		return $usercancash;
	}

	function gourl ($str, $url = "")
	{
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
	
	//自动平仓
	function auto_sell($id)
	{
		$saleID=$id;
		if($row=$this->SQL->GetRow("SELECT * FROM `buy_deal` WHERE id='{$saleID}' and sell='0'"))
		{
			require_once("./Lib/curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$row['stock_type'].$row['stock_code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);
			preg_match("/\"(.+?)\";/is",$html_data,$data);
			$jy_time=date("Y-m-d h:i:s",time());
			$Net=split(',',$data[1]);
			//市价卖出多和空委托价格 多=$Net[7] 空=$Net[6]
			$sell_price=($row['buy_type']==1)?$Net[7]:$Net[6];
			if($Net[7]==0 || $Net[6]==0)
			{
				return;
			}
			//插入委托表
			$SQL_trust=array(
			'user'=>$row['user'],
			'code'=>$row['stock_code'],
			'type'=>$row['stock_type'],
			'name'=>$row['stock_name'],
			'trust_type'=>2,
			'buy_type'=>$row['buy_type'],
			'price'=>$sell_price,
			'app'=>2, //成交
			'num'=>$row['bull_num'],
			'money'=>$row['bull_num']*100*$sell_price,
			'agent'=>$row['agent'],
			'stock_trust_date'=>$this->sys_date,
			'stock_trust_time'=>$this->sys_time,
			'stock_deal_time'=>$this->sys_time);
			$insert_trust =$this->array2str($SQL_trust);
			$this->SQL->Execute("insert into `buy_trust` set $insert_trust");
			//委托卖单ID
			$newID=$this->SQL->LastRowId();
			//卖出金额
			$sell_money=$row['bull_num']*100*$sell_price;
			//会员卖出手续费金额
			$sell_cost_money= round($sell_money*$row['sell_cost'],2);
			//代理商获得的水费
			$agent_sell_cost_money = round($sell_cost_money*$row['agent_ret']/100,2);



			$stock_code = $this->SQL->GetRow("select * from `stock_code` where code='{$row['stock_code']}'");
			if($stock_code['can_sell']==0 && $stock_code['can_sell']!='')
				return '该股票已经满仓,不能卖出！!';
			if($stock_code['stop_pai']==1)
				return '该股票于 '.$stock_code['stop_date'].' 开始停牌,7天内不能买卖!!';


			//点差费
			$dc_money = $row['dc_money'];
			
			$dc_sellmoney = 0;

if ( $dc_money == 0	){

			$dc_num		= $stock_code["dc"];
			$dc_num1	= 0;
			$money		= $sell_money ;
			$new_price  = $sell_price ;
			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  开始 ====================
/*
			include_once("./Lib/curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$stock_code['type'].$stock_code['code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);

			preg_match("/\"(.+?)\";/is",$html_data,$data);
			$Net=split(',',$data[1]);
*/

			$chajia=($Net[4]-$Net[2])/$Net[2]*100;
			$chajia1 = ($Net[5]-$Net[2])/$Net[2]*100;
			$chajia = abs($chajia);
			$chajia1 = abs($chajia1);
			if ($chajia1 > $chajia ) $chajia = $chajia1;

			// $chajia  这个就是涨跌百分比。
			for( $d = 5; $d <=9; $d++){
				if ($chajia >= $d ){		//涨跌率大于 设置的百分比，就读取相应的点差率值。
					$dc_num1 = $this->config['dc'.$d];
				}
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			
			//成交额大于五千万 的点差率。
			if ( $Net[9] > 50000000){		
				$dc_num1 = $this->xltm->config['dc_wan'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于8千万 的点差率。
			if ( $Net[9] > 80000000){		
				$dc_num1 = $this->xltm->config['dc_wan2'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于1.2亿 的点差率。
			if ( $Net[9] > 120000000){		
				$dc_num1 = $this->xltm->config['dc_wan3'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//002开头300开头的点差率。
			if ( substr($stock_code['code'], 0, 3) == "002" or substr($stock_code['code'], 0, 3) == "300" ){
				$dc_num1 = $this->config['dc_tou'];
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


			//计算盈亏
			if($row['buy_type']==1)
			{
				//(多)盈亏计算 卖出金额-买入金额-卖出手续费
				$sell_gain=$sell_money-$row['bull_money'];
				$gain=$sell_money-$row['bull_money']-$sell_cost_money-$row['bull_cost_money']-$row['save_money']-$dc_money;
			}else {
				//(空)盈亏计算 买入金额-卖出金额-卖出手续费
				$sell_gain=$row['bull_money']-$sell_money;
				$gain=$row['bull_money']-$sell_money-$sell_cost_money-$row['bull_cost_money']-$row['save_money']-$dc_money;
			}



			$SQL_deal=array(
			'sell'=>1, //设置为已经卖出
			'sell_price'=>$sell_price,
			'sell_cost_money'=>$sell_cost_money,
			'sell_money'=>$sell_money,
			'sell_trust_id'=>$newID,
			'sell_trust_date'=>$this->sys_date,
			'sell_gain'=>$sell_gain,
			'gain'=>$gain,
			'sell_trust_time'=>$this->sys_time,
			'agent_sell_money'=>$agent_sell_cost_money,
			'can_sell_num'=>0,
			'dc_money'=>$dc_money
			);
			$update_deal=$this->array2str($SQL_deal);
			$this->SQL->Execute("UPDATE `buy_deal` set $update_deal where id='{$saleID}'");
			//返还用户保证金
			$this->SQL->Execute("UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money}-{$dc_sellmoney},cash=cash+{$sell_gain}-{$sell_cost_money}-{$dc_sellmoney} where username='{$row['user']}'");
			//插入帐单信息
			$this->bill_log($row['user'],$row['agent'],'手续费',(0-$sell_cost_money),$_SERVER['REMOTE_ADDR']."3[{$row['id']}]系统自动平仓{$this->config['cost_save_day']}天前的股票:{$row['stock_name']}({$row['stock_code']}), 卖出价:{$sell_price}元, 数量:{$row['bull_money']} 的手续费！");
			$this->bill_log($row['user'],$row['agent'],'浮盈',$sell_gain,"[{$row['id']}]系统自动平仓{$this->config['cost_save_day']}天前的股票:{$row['stock_name']}({$row['stock_code']}), 卖出价:{$sell_price}元, 数量:{$row['bull_money']} 的浮盈！");
			
			if($dc_sellmoney>0)
			{
				$this->bill_log($row['user'],$row['agent'],'点差费',(0-$dc_sellmoney),"[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的点差费(卖出)！");
			}
			
			
		}
	}
	
	/*
	价格符合自动买入
	*/
	function auto_bull_stock($trustID,$shijia)
	{
		if($row=$this->SQL->GetRow("SELECT * FROM `buy_trust` WHERE id='{$trustID}' and app='1'"))
		{
			$st=$this->SQL->GetRow("select dc from stock_code where code='{$row['code']}'");
			$costArr=$this->User->user_cost($row['user']);
			//$this->user_log(9,"委托成功买入股票:".$row['code']);
			$money=$row['price']*$row['num']*100; //买入总价
			$bull_cost_money=round($money*$costArr['member_bull'],2); //买入手续费
			$agent_bull_money = round($bull_cost_money*($costArr['agent_ret']/100),2);
			$dc_num = $st['dc'];


			$dc_num1	= 0;
			$new_price  = $row['price'];
			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  开始 ====================
			include_once("curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$st['type'].$st['code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);

			preg_match("/\"(.+?)\";/is",$html_data,$data);
			$Net=split(',',$data[1]);
			
			
			//买升并且委托价大于等于当前价或者买跌委托价等于当前价买入
			if(($row['buy_type']==1 && $row['price']>=$shijia) || ($row['buy_type']==2 && $row['price']<=$shijia))
			{
			}
			else //委托
			{
				return '市价已变动';
			}
			
			
			$chajia=($Net[4]-$Net[2])/$Net[2]*100;
			$chajia1 = ($Net[5]-$Net[2])/$Net[2]*100;
			$chajia = abs($chajia);
			$chajia1 = abs($chajia1);
			if ($chajia1 > $chajia ) $chajia = $chajia1;

			// $chajia  这个就是涨跌百分比。
			for( $d = 5; $d <=9; $d++){
				if ($chajia >= $d ){		//涨跌率大于 设置的百分比，就读取相应的点差率值。
					$dc_num1 = $this->config['dc'.$d];
				}
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			
			//成交额大于五千万 的点差率。
			if ( $Net[9] > 50000000){		
				$dc_num1 = $this->xltm->config['dc_wan'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于8千万 的点差率。
			if ( $Net[9] > 80000000){		
				$dc_num1 = $this->xltm->config['dc_wan2'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于1.2亿 的点差率。
			if ( $Net[9] > 120000000){		
				$dc_num1 = $this->xltm->config['dc_wan3'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;



			//002开头300开头的点差率。
			if ( substr($row['code'], 0, 3) == "002" or substr($row['code'], 0, 3) == "300" ){
				$dc_num1 = $this->config['dc_tou'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//股票股价低于5元的股票点差率
			if ( $new_price < 5 ){
				$dc_num1 = $this->config['dc_di'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  结束 ====================


			$dc_money = round(($dc_num/1000)*$money,2);
			$SQL_deal=array(
			'user'=>$row['user'],
			'stock_code'=>$row['code'],
			'stock_type'=>$row['type'],
			'stock_name'=>$row['name'],
			'bull_price'=>$row['price'],
			'bull_num'=>$row['num'],
			'can_sell_num'=>$row['num'],
			'bull_trust_id'=>$trustID,
			'bull_trust_date'=>$this->sys_date,
			'bull_trust_time'=>$this->sys_time,
			'buy_type'=>$row['buy_type'],
			'bull_money'=>$money,
			'bull_cost'=>$costArr['member_bull'],
			'sell_cost'=>$costArr['member_sell'],
			'bull_cost_money'=>$bull_cost_money,
			'save_cost'=>$costArr['member_save'],
			'agent'=>$row['agent'],
			'agent_bull_money'=>$agent_bull_money, //代理商水费
			'agent_ret'=>$costArr['agent_ret'],
			'dc_num'=>$dc_num,
			'dc_money'=>$dc_money
			);
			$insert_deal=$this->array2str($SQL_deal);
			$this->SQL->Execute("insert into `buy_deal` set $insert_deal");
			$this->SQL->Execute("UPDATE buy_trust set stock_deal_time='{$this->sys_time}',app='2' where id='{$trustID}'");
			//扣除买入手续费（保证金和可用资金）
			$this->SQL->Execute("Update `user_users` set cash=cash-{$bull_cost_money}-{$dc_money},money = money-{$bull_cost_money}-{$dc_money} where username='{$row['num']}'");
			//添加用户帐单信息
			$this->bill_log($row['user'],$row['agent'],'手续费',(0-$bull_cost_money),$_SERVER['REMOTE_ADDR']."3【委托】买入股票{$row['name']}({$row['code']})的手续费！买入价:{$row['price']}元, 数量:{$row['num']}手！");
			if($dc_money>0)
			{
				$this->bill_log($row['user'],$row['agent'],'点差费',(0-$dc_money),"【委托】买入股票{$row['name']}({$row['code']})的点差费！买入价:{$row['price']}元, 数量:{$row['num']}手, 点差:{$dc_num}‰！");
			}
			return "自动买入成功";
		}
		else
		{
			return "可能已经交易或撤销";
		}
	}
	
	//自动平仓股票
	function auto_sale_stock($trustID,$shijia)
	{
		if($this->startTIME()==0)
		{
			return "休市时间不允许卖出股票！！";
		}

		if($row=$this->SQL->GetRow("select * from `buy_trust` where id='{$trustID}' and trust_type='2' and app='1'"))
		{
			$cur_price = $row['price']; //委托价卖出
			$DealID = $row['deal_id']; //委托单ID
			
			
			//卖出验证
			if(($row['buy_type']==1 && $row['price']<=$shijia) || ($row['buy_type']==2 && $row['price']>=$shijia))
			{
			}
			else //委托
			{
				return '市价已重新变动';
			}
			
			
			//读取持仓单
			if(!$rowd=$this->SQL->GetRow("select * from `buy_deal` where id='{$DealID}' and sell='0'"))
			{
				return "";
			}
			$stock_code=$this->SQL->GetRow("select * from `stock_code` where code='{$row['code']}'");
			if($stock_code['can_sell']==0 && $stock_code['can_sell']!='')
				return '该股票已经满仓,不能卖出！!';
			if($stock_code['stop_pai']==1)
				return '该股票于 '.$stock_code['stop_date'].' 开始停牌,7天内不能买卖!!';

			//平仓操作
			
			if($rowd['can_sell_num']>0) //分拆旧持仓表,插入新的委托记录
			{
				$SQL_trust=array(
				'user'=>$row['user'],
				'code'=>$rowd['stock_code'],
				'type'=>$rowd['stock_type'],
				'name'=>$rowd['stock_name'],
				'trust_type'=>1,
				'buy_type'=>$rowd['buy_type'],
				'price'=>$rowd['bull_price'],
				'app'=>2,
				'num'=>$row['num'],
				'money'=>round($rowd['bull_price']*$row['num']*100,2),
				'agent'=>$rowd['agent'],
				'stock_trust_date'=>$rowd['bull_trust_date'],
				'stock_trust_time'=>$rowd['bull_trust_time'],
				'stock_deal_time'=>$rowd['bull_trust_time']
				);
				$insert_trust =$this->array2str($SQL_trust);
				$this->SQL->Execute("insert into `buy_trust` set {$insert_trust}");
				//新委托买单ID
				$newID=$this->SQL->LastRowId();
				
				//插入新的持仓记录
				$SQL_deal=array(
				'user'=>$rowd['user'],
				'app'=>'0',
				'sell'=>'0',//设置已卖出
				'stock_code'=>$rowd['stock_code'],
				'stock_type'=>$rowd['stock_type'],
				'stock_name'=>$rowd['stock_name'],
				'bull_price'=>$rowd['bull_price'],
				'bull_num'=>$row['num'],
				'can_sell_num'=>0,
				'bull_trust_id'=>$newID,
				'bull_trust_date'=>$rowd['bull_trust_date'],
				'bull_trust_time'=>$rowd['bull_trust_time'],
				'buy_type'=>$rowd['buy_type'],
				'bull_money'=>round($rowd['bull_price']*$row['num']*100,2),
				'bull_cost'=>$rowd['bull_cost'],
				'sell_cost'=>$rowd['sell_cost'],
				'bull_cost_money'=>round($rowd['bull_price']*$row['num']*100*$rowd['bull_cost'],2), //买入手续费
				'save_cost'=>$rowd['save_cost'],
				'agent'=>$rowd['agent'],
				'agent_bull_money'=>round($rowd['bull_price']*$row['num']*100*$rowd['bull_cost']*($rowd['agent_ret']/100),2), //代理商买入手续费水费
				'agent_ret'=>$rowd['agent_ret'],
				'dc_num'=>$rowd['dc_num'],
				'dc_money'=>round($rowd['bull_price']*$row['num']*100*($rowd['dc_num']/1000),2), //新的点差费
				'save_day'=>$rowd['save_day'],
				'save_money'=>round($rowd['bull_price']*$row['num']*100*$rowd['save_cost']*$rowd['save_day'],2), //新的留仓费
				'agent_save_money'=>round($rowd['bull_price']*$row['num']*100*$rowd['save_cost']*$rowd['save_day']*($rowd['agent_ret']/100),2) //代理商留仓费水费
				);
				$insert_deal=$this->array2str($SQL_deal);
				$this->SQL->Execute("insert into `buy_deal` set {$insert_deal}");
				$newDealID=$this->SQL->LastRowId();//新的持仓记录ID
	
				//更新旧持仓记录有关买入数量，买入总金额，买入手续费,代理商水费信息
				$SQL_Ary=array(
					'bull_num'=>$rowd['can_sell_num'],
					'bull_money'=>round($rowd['bull_price']*$rowd['can_sell_num']*100,2),
					'bull_cost_money'=>round($rowd['bull_price']*$rowd['can_sell_num']*100*$rowd['bull_cost'],2),
					'agent_bull_money'=>round($rowd['bull_price']*$rowd['can_sell_num']*100*$rowd['bull_cost']*($rowd['agent_ret']/100),2),
					'dc_money'=>round($rowd['bull_price']*$rowd['can_sell_num']*100*($rowd['dc_num']/1000),2),
					'save_money'=>round($rowd['bull_price']*$rowd['can_sell_num']*100*$rowd['save_cost']*$rowd['save_day'],2), //新的留仓费
					'agent_save_money'=>round($rowd['bull_price']*$rowd['can_sell_num']*100*$rowd['save_cost']*$rowd['save_day']*($rowd['agent_ret']/100),2) //代理商留仓费水费
				);
				$update = $this->array2str($SQL_Ary);
				$this->SQL->Execute("update `buy_deal` set {$update} where id={$DealID}");
			}
			else
			{
				$newDealID = $row['deal_id'];
			}
			
			//卖出操作
			$row = $this->SQL->GetRow("select * from `buy_deal` where id='{$newDealID}'");
			
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
			$new_price  = $cur_price ;
			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  开始 ====================

			include_once("curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$stock_code['type'].$stock_code['code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);

			preg_match("/\"(.+?)\";/is",$html_data,$data);
			$Net=split(',',$data[1]);


			$chajia=($Net[4]-$Net[2])/$Net[2]*100;
			$chajia1 = ($Net[5]-$Net[2])/$Net[2]*100;
			$chajia = abs($chajia);
			$chajia1 = abs($chajia1);
			if ($chajia1 > $chajia ) $chajia = $chajia1;

			// $chajia  这个就是涨跌百分比。
			for( $d = 5; $d <=9; $d++){
				if ($chajia >= $d ){		//涨跌率大于 设置的百分比，就读取相应的点差率值。
					$dc_num1 = $this->config['dc'.$d];
				}
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			
			//成交额大于五千万 的点差率。
			if ( $Net[9] > 50000000){		
				$dc_num1 = $this->xltm->config['dc_wan'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于8千万 的点差率。
			if ( $Net[9] > 80000000){		
				$dc_num1 = $this->xltm->config['dc_wan2'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于1.2亿 的点差率。
			if ( $Net[9] > 120000000){		
				$dc_num1 = $this->xltm->config['dc_wan3'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//002开头300开头的点差率。
			if ( substr($stock_code['code'], 0, 3) == "002" or substr($stock_code['code'], 0, 3) == "300" ){
				$dc_num1 = $this->config['dc_tou'];
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
			'sell_price'=>$cur_price,
			'sell_cost_money'=>$sell_cost_money,
			'dc_money'=>$dc_money,
			'sell_money'=>$sell_money,
			'sell_trust_id'=>$trustID,
			'sell_gain'=>$sell_gain,
			'gain'=>$gain,
			'agent_sell_money'=>$agent_sell_money,
			'sell_trust_date'=>$this->sys_date,
			'sell_trust_time'=>$this->sys_time
			);
			$update_deal=$this->array2str($SQL_deal);
			$this->SQL->Execute("Update `buy_deal` set {$update_deal} where id='{$newDealID}'");
			
			//返还用户可用值(以及保证金)
			$this->SQL->Execute("UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney},cash=cash+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney} where username='{$row['user']}'");
			
			//添加用户帐单信息
			$this->bill_log($row['user'],$row['agent'],'手续费',(0-$sell_cost_money),"【委托】卖出股票{$row['stock_name']}({$row['stock_code']})的手续费！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！");
			if($cost_sell_limit_money>0)
			{
				$this->bill_log($row['user'],$row['agent'],'点差费',(0-$cost_sell_limit_money),"【委托】卖出股票{$row['stock_name']}({$row['stock_code']})的点差费({$this->config['sel_max_time']}分钟内卖出加收)！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手, 加收手续率:".($this->config['cost_sell_limit']*100)."%！");
			}
			
			if($dc_sellmoney>0)
			{
				$this->bill_log($row['user'],$row['agent'],'点差费',(0-$dc_sellmoney),"[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的点差费(卖出)！");
			}
				
			
			$this->bill_log($row['user'],$row['agent'],'浮盈',$sell_gain,$_SERVER['REMOTE_ADDR']."3【委托】卖出股票{$row['stock_name']}({$row['stock_code']})的浮盈！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！");
			//更新委托单状态
			$this->SQL->Execute("Update `buy_trust` set app='2',stock_deal_time='{$this->sys_time}' where id='{$trustID}'");
			return "交易成功";
		}
	}
	
	function baocang()
	{
		$bc_num = 0;
		if($rows=$this->SQL->GetRows("select * from `user_users` where demo='0' and cash>'0' and last_login>'0' and active='yes' and blocked='no'"))
		{
			foreach($rows as $u)
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
							$sell_money = round($q[$i] * $d['bull_num'] * 100 ,2);
							$sell_cost_money = round($sell_money*$d['sell_cost'],2);
							$dc_money		 = $d['dc_money'];							//点差
							//$dc_money		 = 0;							//点差

							if($d['buy_type']=='1') //
							{
								$gain = $sell_money - $d['bull_money'] - $sell_cost_money - $dc_money - $d['bull_cost_money'];
							}
							else
							{
								$gain = $d['bull_money'] - $sell_money  - $sell_cost_money - $dc_money - $d['bull_cost_money'];
							}
							$gain_total += $gain;
							$sell_cost_money_total += $d['bull_cost_money'] ;
							$dc_money_total += $dc_money;
							$i++;
						}
						
						//当前保证金+持仓手续费+持仓点差费去乘以爆仓比例
						$cash = round(($cash + $sell_cost_money_total + $dc_money_total) * $this->config['baocang_precent'] /100 ,2);
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
			$this->bill_log($row['user'],$row['agent'],'手续费',(0-$sell_cost_money),$_SERVER['REMOTE_ADDR']."3[{$saleID}]卖出股票{$row['stock_name']}({$row['stock_code']})手续费!卖出总金额:￥{$sell_money}, 卖出价:￥{$sell_price}, 数量:{$row['bull_num']}手！",$saleID);
			$this->bill_log($row['user'],$row['agent'],'浮盈',$sell_gain,$_SERVER['REMOTE_ADDR']."3[{$saleID}]卖出股票{$row['stock_name']}({$row['stock_code']})浮盈!卖出总金额:￥{$sell_money}, 卖出价:￥{$sell_price}, 数量:{$row['bull_num']}手！",$saleID);
			return '平仓成功!!';
		}else {
			return '该交易已经平仓或不存在';
		}
	}

	function dateFrom($date)
	{
		$dateTemp=explode('-',$date);
		$YYYY = $dateTemp[0];
		$MM   = ($dateTemp[1]<10)?'0'.abs($dateTemp[1]):$dateTemp[1];
		$DD   = ($dateTemp[2]<10)?'0'.abs($dateTemp[2]):$dateTemp[2];
		return $YYYY.'-'.$MM.'-'.$DD;
	}
	
	function showMsg($title='系统提示',$msg,$success=false,$url='back')
	{
		$html = "<script type='text/javascript'>";
		if($success)
		{
			$html .= "parent.ymPrompt.succeedInfo({title:'{$title}',message:'{$msg}',handler:function(){";
		}
		else
		{
			$html .= "parent.ymPrompt.errorInfo({title:'{$title}',message:'{$msg}',handler:function(){";
		}
		if($url=='back')
		{
			$html .= "history.go(-1);";
		}
		else if($url=='nothing')
		{
			
		}
		else
		{
			$html .= "self.location.href='{$url}';";
		}
		$html .="}});</script>";
		print $html;
		exit();
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
