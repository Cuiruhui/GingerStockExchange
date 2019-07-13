<?php
/*
委托买卖股票
*/
class buy{
	var $xltm;
	function buy(&$xltm)
	{
		$this->xltm = &$xltm;
	}

	/*委托买*/
	function bull_stock()
	{
		$code=$_POST['code']; //代码
		$price=$_POST['buys_price']; //价格
		$type=$_POST['buy_type']; //价格类型
		$price_type=$_POST['price_type'];//市价or委托
		$buy_type=$_POST['buy_type']; //多空
		$num=$_POST['buy_num'];

		if(!$code)
			exit("false|请选择要买入的股票！");
		if(!$num||$num<0)
			exit("false|请输入购买数量！");
		if(!$type)
			exit("false|请选择买升或买跌！");
		if(!$price_type||$price<0)
			exit("false|请选择购买价格类型(市价或委托价)！");
		if($price_type==2 && !$price)
			exit("false|请输入委托价格！");
		if($row=$this->xltm->SQL->GetRow("SELECT * FROM `stock_code` WHERE code='$code'")){
			if($row['can_bull']==0)
				exit("false|该股票已经满仓！<br />“满仓”是指:其他会员购买该只股票数量已经超过500万股无法再买入！");
			if($row['stop_pai']==1)
				exit("false|该股票于 ".$row['stop_date']."开始停牌,7天内不能买卖!");
			if($row['can_bull_up']==0 && $buy_type==1) //买升且系统设置禁买升
			{
				exit("false|该股票已经满仓！<br />“满仓”是指:其他会员购买该只股票数量已经超过500万股无法再买入！");
			}
			if($row['can_bull_down']==0 && $buy_type==2) //买跌且系统设置禁买跌
			{
				exit("false|该股票已经满仓！<br />“满仓”是指:其他会员购买该只股票数量已经超过500万股无法再买入！");
			}
			include("./Lib/curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$row['type'].$row['code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);

			preg_match("/\"(.+?)\";/is",$html_data,$data);
			$Net=split(',',$data[1]);
			$jy_time=date("Y-m-d h:i:s",time());
			//市价买入多和空委托价格 多=$Net[7] 空=$Net[6]
			//$cur_price=($buy_type==1)?$Net[7]:$Net[6];
			$cur_price = $Net[3];
			
			//委托价格交易
			if($price_type==2){
				//买升并且委托价大于等于当前价或者买跌委托价等于当前价买入
				if(($buy_type==1 && $price>=$cur_price) || ($buy_type==2 && $price<=$cur_price))
				{
					$app = 2;
					//$new_price = $price;
					
					$new_price = $cur_price;  //成交价格
					
				}
				else //委托
				{
					$app = 1;
					$new_price = $price;
				}
			}else { //市价交易

				//2012-4-12 李兴，这里加入滑价金额。 单价加滑价后，再四舍五入。==================================
				$price_float	= 0 ;
				$app			= 2 ;

				if ( $buy_type == 1 ){		//买涨
					$price_float = $cur_price * $this->xltm->config['up_float'] ;
					$new_price = $cur_price + $price_float;
					$new_price = round($new_price, 2);

				}elseif ( $buy_type == 2 ){	//买跌
					$price_float = $cur_price * $this->xltm->config['down_float'] ;
					$new_price = $cur_price - $price_float;
					$new_price = round($new_price, 2);

				}else{
					$new_price = $cur_price;

				}
				//2012-4-12 李兴，这里加入滑价金额。 单价加滑价后，再四舍五入。==================================

			}
			//exit("false|$new_price");

			$chajia=($Net[3]-$Net[2])/$Net[2]*100;
			$buy_sd=$this->xltm->config['buy_sd'];
			$st_buy_sd=$this->xltm->config['st_buy_sd'];
			/*2011.11.30增加 ST涨跌幅控制*/
			if(strpos(strtolower($row['name']),'st')===false)
			{
				if($chajia>=$buy_sd || $chajia<=-$buy_sd)
					exit("false|涨跌幅超出{$buy_sd}个点,暂停交易！");
			}
			else //ST股
			{
				if($chajia>=$st_buy_sd || $chajia<=-$st_buy_sd)
					exit("false|ST股涨跌幅超出{$st_buy_sd}个点,暂停交易！");
			}


			if($num<$this->xltm->user_info['num_min'])
				exit("false|最低要买入{$this->xltm->user_info['num_min']}手！");
			if($num>$this->xltm->user_info['num_max'])
				exit("false|最多只能买入{$this->xltm->user_info['num_max']}手！");
			
			//统计当前持仓股票金额
			$curTotalMoney = 0;
			if($rowt = $this->xltm->SQL->GetRow("select sum(bull_money) as totalm from `buy_deal` where sell='0' and stock_code='{$code}' and user='{$this->xltm->user_info['username']}'"))
			{
				if(is_numeric($rowt['totalm']))
				{
					$curTotalMoney = $rowt['totalm'];
				}
			}
			$money=$num*100*$new_price;
			$money_max = $this->xltm->user_info['money_max'];
			if($money_max==0) $money_max = 9999;
			if($curTotalMoney + $money > $money_max*10000) //最多只能持股200万
			{
				exit("false|您的用户交易级别：单只股票交易额限<font color=red>{$money_max}</font>万。<br />（如需扩大交易金额，请联系工作人员！）");
				//exit("false|<font color=red>每支股票最多只允许持仓金额{$money_max}万！</font><br />您当前[{$code}]持仓金额为:￥{$curTotalMoney};<br />您本次买入金额为:￥{$money};<br />总金额已超过{$money_max}万！");
			}




			$bull_cost_money = round($money*($this->xltm->user_info['cost_bull']),2); //交易手续费


			

			$dc_num		= $row['dc'];
			$dc_num1	= 0;
			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  开始 ====================
			
			$chajia = ($Net[4]-$Net[2])/$Net[2]*100;
			$chajia1 = ($Net[5]-$Net[2])/$Net[2]*100;
			$chajia = abs($chajia);
			$chajia1 = abs($chajia1);
			if ($chajia1 > $chajia ) $chajia = $chajia1;

			// $chajia  这个就是涨跌百分比。
			for( $d = 5; $d <=9; $d++){
				if ($chajia >= $d ){		//涨跌率大于 设置的百分比，就读取相应的点差率值。
					$dc_num1 = $this->xltm->config['dc'.$d];
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
				$dc_num1 = $this->xltm->config['dc_tou'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//股票股价低于5元的股票点差率
			if ( $new_price < 5 ){
				$dc_num1 = $this->xltm->config['dc_di'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  结束 ====================

			$dc_money = round($money*($dc_num/1000),2); //点差费，买入收取


			$totalmoney = $money + $bull_cost_money + $dc_money; //总金额(买入总价+手续费+点差费)
			
			$usercancash = round($this->xltm->AvailableCash($this->xltm->user_info['username']),2); //用户当前可用保证金

			if($totalmoney>($usercancash * $this->xltm->config['cost_exchange_rate'] ))		//2012-4-14李兴，修改。
			{
				exit("false|信息错误，请确认之后重试！");
			}




			//******************************* 2012-4-14李兴，这里再加上，将 1 人民币兑换成 100 股币后，单支股票只能是金额的十分之一。

			$money_percent = $this->xltm->config['money_percent'];

			if($money_percent > 0 )
			{
				$percent10 = $usercancash * $this->xltm->config['cost_exchange_rate'] * ($money_percent / 100) ;	//可用金额的百分之十.

				if( $money > $percent10 ) //不能超过十分之一。
				{
					exit("false|购买提示：单只股票交易额限已超出，最多<font color=red>{$percent10}</font>元。<br/>");
				}
			}

			//******************************* 2012-4-14李兴，这里再加上，将 1 人民币兑换成 100 股币后，单支股票只能是金额的十分之一。



			//插入交易临时表
			$SQL_trust=array(
			'user'=>$this->xltm->user_info['username'],
			'code'=>$row['code'],
			'type'=>$row['type'],
			'name'=>$row['name'],
			'trust_type'=>1,
			'buy_type'=>$buy_type,
			'price'=>$new_price,
			'app'=>$app,
			'num'=>$num,
			'money'=>$money,
			'agent'=>$this->xltm->user_info['agent'],
			'stock_trust_date'=>$this->xltm->sys_date,
			'stock_trust_time'=>$this->xltm->sys_time
			);
			$insert_trust =$this->xltm->array2str($SQL_trust);
			
			$this->xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." insert into `buy_trust` set $insert_trust");
			
			
			$this->xltm->SQL->Execute("insert into `buy_trust` set $insert_trust");
			//委托买单ID
			$newID=$this->xltm->SQL->LastRowId();
			//扣除用户可用资金
			$this->xltm->SQL->Execute("UPDATE `user_users` set money=money-'{$money}' where username='{$this->xltm->user_info['username']}'");
			//如果价格符合或市价交易马上写入交易结果
			if($app==2 && ($Net[7] && $Net[6]))
			{
				$this->xltm->user_log(9,"买入股票:".$row['code']);
				$costArr=$this->xltm->User->user_cost($this->xltm->user_info['username']);
				$SQL_deal=array(
				'user'=>$this->xltm->user_info['username'],
				'stock_code'=>$row['code'],
				'stock_type'=>$row['type'],
				'stock_name'=>$row['name'],
				'bull_price'=>$new_price,
				'bull_num'=>$num,
				'can_sell_num'=>$num,
				'bull_trust_id'=>$newID,
				'bull_trust_date'=>$this->xltm->sys_date,
				'bull_trust_time'=>$this->xltm->sys_time,
				'buy_type'=>$buy_type,
				'bull_money'=>$money,
				'bull_cost'=>$costArr['member_bull'],
				'sell_cost'=>$costArr['member_sell'],
				'bull_cost_money'=>$bull_cost_money, //买入手续费
				'save_cost'=>$costArr['member_save'],
				'agent'=>$this->xltm->user_info['agent'],
				'agent_bull_money'=>round(($bull_cost_money+$dc_money)*($costArr['agent_ret']/100),2),
				'agent_ret'=>$costArr['agent_ret'],
				'dc_num'=>$dc_num,
				'dc_money'=>$dc_money
				);
				$insert_deal=$this->xltm->array2str($SQL_deal);
				
				$ip = $_SERVER['REMOTE_ADDR'];
				
				$this->xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." insert into `buy_deal` set $insert_deal");
				$this->xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." UPDATE `buy_trust` set stock_deal_time='{$this->xltm->sys_time}' where id='{$newID}'");
				
				$this->xltm->SQL->Execute("insert into `buy_deal` set $insert_deal");
				$this->xltm->SQL->Execute("UPDATE `buy_trust` set stock_deal_time='{$this->xltm->sys_time}' where id='{$newID}'");
				
				
				
				//扣除买入手续费（保证金和可用资金）
				$this->xltm->SQL->Execute("Update `user_users` set cash=cash-{$bull_cost_money}-{$dc_money},money=money-{$bull_cost_money}-{$dc_money} where username='{$this->xltm->user_info['username']}'");
				//添加用户帐单信息
				$this->xltm->bill_log($this->xltm->user_info['username'],$this->xltm->user_info['agent'],'手续费',(0-$bull_cost_money),$ip."买入股票{$row['name']}({$row['code']})手续费！买入价:{$new_price}元, 数量:{$num}(手)！");
				if($dc_money>0)
				{
					$this->xltm->bill_log($this->xltm->user_info['username'],$this->xltm->user_info['agent'],'点差费',(0-$dc_money),$ip."买入股票{$row['name']}({$row['code']})点差费(买入收取)！买入价:{$new_price}元, 数量:{$num}(手), 点差:{$dc_num}‰！");
				}
				exit("true|交易成功！");
			}else {
				$this->xltm->user_log(9,"委托买入:".$row['code']);
				exit("true|委托单已送出！委托单状态请到“当日委托查询”功能查看！");
			}
		}else {
			exit("false|没有这只股票");
		}
	}
	/*
	价格符合自动买入
	*/
	function auto_bull_stock($trustID,$shijia)
	{
		if($row=$this->xltm->SQL->GetRow("SELECT * FROM `buy_trust` WHERE id='{$trustID}' and app='1'"))
		{
			$st=$this->xltm->SQL->GetRow("select dc from `stock_code` where code='{$row['code']}'");
			$costArr=$this->xltm->User->user_cost($this->xltm->user_info['username']);
			$this->xltm->user_log(9,"委托成功买入股票:".$row['code']);


			$money=$row['price']*$row['num']*100; //买入总价
			$bull_cost_money=round($money*$costArr['member_bull'],2); //买入手续费
			$agent_bull_money = round($bull_cost_money*($costArr['agent_ret']/100),2);


			$dc_num		= $st['dc'];
			$dc_num1	= 0;
			$new_price  = $row['price'];
			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  开始 ====================
			include_once("./Lib/curl_http.php");
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
					$dc_num1 = $this->xltm->config['dc'.$d];
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
				$dc_num1 = $this->xltm->config['dc_tou'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//股票股价低于5元的股票点差率
			if ( $new_price < 5 ){
				$dc_num1 = $this->xltm->config['dc_di'];
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
			'bull_trust_date'=>$this->xltm->sys_date,
			'bull_trust_time'=>$this->xltm->sys_time,
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
			$insert_deal=$this->xltm->array2str($SQL_deal);
			
			$this->xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." insert into `buy_deal` set $insert_deal");
			$this->xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." UPDATE buy_trust set stock_deal_time='{$this->xltm->sys_time}',app='2' where id='{$trustID}'");
			
			$this->xltm->SQL->Execute("insert into `buy_deal` set $insert_deal");
			$this->xltm->SQL->Execute("UPDATE buy_trust set stock_deal_time='{$this->xltm->sys_time}',app='2' where id='{$trustID}'");
			//扣除买入手续费（保证金和可用资金）
			$this->xltm->SQL->Execute("Update `user_users` set cash=cash-{$bull_cost_money}-{$dc_money},money = money-{$bull_cost_money}-{$dc_money} where username='{$this->xltm->user_info['username']}'");
			//添加用户帐单信息
			$this->xltm->bill_log($this->xltm->user_info['username'],$this->xltm->user_info['agent'],'手续费',(0-$bull_cost_money),$_SERVER['REMOTE_ADDR']."1买入股票{$row['name']}({$row['code']})手续费！买入价:￥{$row['price']}元, 数量:{$row['num']}(手)！");
			if($dc_money>0)
			{
				$this->xltm->bill_log($this->xltm->user_info['username'],$this->xltm->user_info['agent'],'点差费',(0-$dc_money),"买入股票{$row['name']}({$row['code']})点差费(买入收取)！买入价:￥{$row['price']}元, 数量:{$row['num']}(手), 点差:{$dc_num}‰！");
			}
		}
	}
	/*
	#市价平仓(卖出部分)
	[sell_id]=持仓单ID
	[sell_num]=卖出数量
	[price_type]=价格类型(1:市价平仓 2:委托价平仓)
	[sell_price]=委托价，等于0市价平仓
	*/
	function sale_stock_part($sell_id,$sell_num=0,$price_type=1,$sell_price=0)
	{
		$SellTIME=$this->xltm->SellTIME();
		$sell_cost = 0;
		if($SellTIME==0)
		{
			return "休市时间不允许卖出股票！！";
		}
		if($sell_num==0||$sell_num<0)
		{
			return "卖出股票数量必须大于0！！";
		}
		
		if($sell_price<0)
		{
			return "卖出价格必须大于0！！";
		}
		
		if($row=$this->xltm->SQL->GetRow("SELECT * FROM `buy_deal` WHERE user='{$this->xltm->user_info['username']}' and id='{$sell_id}' and app='0'"))
		{
			$stock_code=$this->xltm->SQL->GetRow("select * from `stock_code` where code='{$row['stock_code']}'");
			if($stock_code['can_sell']==0 && $stock_code['can_sell']!='')
				return '该股票已经满仓,不能卖出！!';
			if($stock_code['stop_pai']==1)
				return '该股票于 '.$stock_code['stop_date'].' 开始停牌,7天内不能买卖!!';
				
			//获取当前价
			include_once("./Lib/curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$row['stock_type'].$row['stock_code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);
			preg_match("/\"(.+?)\";/is",$html_data,$data);
			$jy_time=date("Y-m-d h:i:s",time());
			$Net=split(',',$data[1]);
			if($Net[3]==0)
				return '卖出股票失败，该股票已停牌！';
			//市价卖出多和空委托价格 多=$Net[7] 空=$Net[6]
			//$cur_price = ($row['buy_type']=='1')?$Net[7]:$Net[6];
			$cur_price = $Net[3];



			if($price_type==1) $sell_price = $cur_price; //如果是市价卖出，设置卖出价等于当前价
			$can_sell_num = $row['bull_num']-$sell_num;
			
			//委托价卖出或当前的价格小于委托价做委托记录
			if($price_type==2 && (($row['buy_type']==1 && $sell_price>$cur_price) || ($row['buy_type']==2 && $sell_price<$cur_price)))
			{
				$this->xltm->user_log('9','委托卖出股票'.$row['stock_name'].'('.$row['stock_code'].')'.$sell_num.'手，委托价:'.$sell_price);
				//插入卖出委托记录
				$SQL_trust=array(
					'deal_id'=>$sell_id,
					'user'=>$row['user'],
					'code'=>$row['stock_code'],
					'type'=>$row['stock_type'],
					'name'=>$row['stock_name'],
					'trust_type'=>2, //卖出
					'buy_type'=>$row['buy_type'],
					'price'=>$sell_price,
					'app'=>1,
					'num'=>$sell_num,
					'money'=>round($sell_price*$sell_num*100,2),
					'agent'=>$row['agent'],
					'stock_trust_date'=>$this->xltm->sys_date,
					'stock_trust_time'=>$this->xltm->sys_time
				);
				$insert_trust =$this->xltm->array2str($SQL_trust);
				
				$this->xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." insert into `buy_trust` set $insert_trust");
				
				$this->xltm->SQL->Execute("insert into `buy_trust` set $insert_trust");
				//更新可用数量
				$this->xltm->SQL->Execute("update `buy_deal` set can_sell_num='$can_sell_num' where id='$sell_id'");
				return "委托卖出股票{$row['stock_name']}({$row['stock_code']})成功！委托价:{$sell_price}元,委托数量:{$sell_num}(手)！";
			}
			else //市价卖出或当前价大于等于委托价，卖出股票
			{
				if($sell_num<$row['bull_num']) //卖出部分，分拆表
				{
					/*
					分拆原则：
					1、插入新的买入委托记录；
					2、插入新的持仓记录；
					3、更新原持仓记录持仓手数、交易金额、手续费、代理水费；
					*/
					//插入新的买入委托记录
					$SQL_trust=array(
					'user'=>$row['user'],
					'code'=>$row['stock_code'],
					'type'=>$row['stock_type'],
					'name'=>$row['stock_name'],
					'trust_type'=>1,
					'buy_type'=>$row['buy_type'],
					'price'=>$row['bull_price'],
					'app'=>2,
					'num'=>$sell_num,
					'money'=>round($row['bull_price']*$sell_num*100,2),
					'agent'=>$row['agent'],
					'stock_trust_date'=>$row['bull_trust_date'],
					'stock_trust_time'=>$row['bull_trust_time'],
					'stock_deal_time'=>$row['bull_trust_time']
					);
					$insert_trust =$this->xltm->array2str($SQL_trust);
					$this->xltm->SQL->Execute("insert into `buy_trust` set {$insert_trust}");
					//新委托买单ID
					$newID=$this->xltm->SQL->LastRowId();
					
					//插入新的持仓记录
					$SQL_deal=array(
					'user'=>$row['user'],
					'app'=>'0',
					'sell'=>'0',//设置已卖出
					'stock_code'=>$row['stock_code'],
					'stock_type'=>$row['stock_type'],
					'stock_name'=>$row['stock_name'],
					'bull_price'=>$row['bull_price'],
					'bull_num'=>$sell_num,
					'can_sell_num'=>0,
					'bull_trust_id'=>$newID,
					'bull_trust_date'=>$row['bull_trust_date'],
					'bull_trust_time'=>$row['bull_trust_time'],
					'buy_type'=>$row['buy_type'],
					'bull_money'=>round($row['bull_price']*$sell_num*100,2),
					'bull_cost'=>$row['bull_cost'],
					'sell_cost'=>$row['sell_cost'],
					'bull_cost_money'=>round($row['bull_price']*$sell_num*100*$row['bull_cost'],2), //买入手续费
					'save_cost'=>$row['save_cost'],
					'agent'=>$row['agent'],
					'agent_bull_money'=>round($row['bull_price']*$sell_num*100*$row['bull_cost']*($row['agent_ret']/100),2), //代理商买入手续费水费
					'agent_ret'=>$row['agent_ret'],
					'dc_num'=>$row['dc_num'],
					'dc_money'=>round($row['bull_price']*$sell_num*100*($row['dc_num']/1000),2), //新的点差费
					'save_day'=>$row['save_day'],
					'save_money'=>round($row['bull_price']*$sell_num*100*$row['save_cost']*$row['save_day'],2), //新的留仓费
					'agent_save_money'=>round($row['bull_price']*$sell_num*100*$row['save_cost']*$row['save_day']*($row['agent_ret']/100),2) //代理商留仓费水费
					);
					$insert_deal=$this->xltm->array2str($SQL_deal);
					$this->xltm->SQL->Execute("insert into `buy_deal` set {$insert_deal}");
					$newDealID=$this->xltm->SQL->LastRowId();//新的持仓记录ID
		
					//更新旧持仓记录有关买入数量，买入总金额，买入手续费,代理商水费信息
					$SQL_Ary=array(
						'bull_num'=>$can_sell_num,
						'can_sell_num'=>$can_sell_num,
						'bull_money'=>round($row['bull_price']*$can_sell_num*100,2),
						'bull_cost_money'=>round($row['bull_price']*$can_sell_num*100*$row['bull_cost'],2),
						'agent_bull_money'=>round($row['bull_price']*$can_sell_num*100*$row['bull_cost']*($row['agent_ret']/100),2),
						'dc_money'=>round($row['bull_price']*$can_sell_num*100*($row['dc_num']/1000),2),
						'save_money'=>round($row['bull_price']*$can_sell_num*100*$row['save_cost']*$row['save_day'],2), //新的留仓费
						'agent_save_money'=>round($row['bull_price']*$can_sell_num*100*$row['save_cost']*$row['save_day']*($row['agent_ret']/100),2) //代理商留仓费水费
					);
					$update = $this->xltm->array2str($SQL_Ary);
					$this->xltm->SQL->Execute("update `buy_deal` set {$update} where id={$sell_id}");
					
					//读取持仓新记录
					$row = $this->xltm->SQL->GetRow("select * from `buy_deal` where id='{$newDealID}'");
				}
				else
				{
					$newDealID = $sell_id;
				}



				//2012-4-12 李兴，这里加入滑价金额。 单价加滑价后，再四舍五入。==================================
				if ( $price_type==1){		//市价卖出，才加滑价。
					$price_float	= 0 ;
					$buy_type = $row['buy_type'] ;

					if ( $buy_type == 1 ){		//买涨
						$price_float = $cur_price * $this->xltm->config['up_float'] ;
						$new_price = $cur_price - $price_float;
						$new_price = round($new_price, 2);

					}elseif ( $buy_type == 2 ){	//买跌
						$price_float = $cur_price * $this->xltm->config['down_float'] ;
						$new_price = $cur_price + $price_float;
						$new_price = round($new_price, 2);

					}else{
						$new_price = $cur_price;

					}
					$sell_price = $cur_price = $new_price;

					$this->xltm->config['ttt_sell_price'] = $sell_price; 
				}

//exit($new_price);
				//2012-4-12 李兴，这里加入滑价金额。 单价加滑价后，再四舍五入。==================================



				//卖出股票操作
				$this->xltm->user_log(9,"卖出股票:{$row['stock_code']}({$row['bull_num']}手)，卖出价:{$cur_price}。");
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
					'stock_trust_date'=>$this->xltm->sys_date,
					'stock_trust_time'=>$this->xltm->sys_time,
					'stock_deal_time'=>$this->xltm->sys_time
				);
				$insert_trust =$this->xltm->array2str($SQL_trust);
				$this->xltm->SQL->Execute("insert into buy_trust set $insert_trust");
				//委托卖单ID
				$newID=$this->xltm->SQL->LastRowId();
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

			$chajia=($Net[4]-$Net[2])/$Net[2]*100;
			$chajia1 = ($Net[5]-$Net[2])/$Net[2]*100;
			$chajia = abs($chajia);
			$chajia1 = abs($chajia1);
			if ($chajia1 > $chajia ) $chajia = $chajia1;

			// $chajia  这个就是涨跌百分比。
			for( $d = 5; $d <=9; $d++){
				if ($chajia >= $d ){		//涨跌率大于 设置的百分比，就读取相应的点差率值。
					$dc_num1 = $this->xltm->config['dc'.$d];
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
				$dc_num1 = $this->xltm->config['dc_tou'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//股票股价低于5元的股票点差率
			if ( $new_price < 5 ){
				$dc_num1 = $this->xltm->config['dc_di'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  结束 ====================

			$dc_money = round(($dc_num/1000)*$money,2);
			
			$dc_sellmoney = $dc_money;
}

				//允许卖出时间内卖出加收手续费
				$canSellTime=date('Y-m-d H:i:s',time()-($this->xltm->config['sel_max_time']*60));
				$cost_sell_limit = $row['bull_trust_time']>$canSellTime ? $this->xltm->config['cost_sell_limit'] : 0;
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
				'sell_trust_date'=>$this->xltm->sys_date,
				'sell_trust_time'=>$this->xltm->sys_time
				);
				$update_deal=$this->xltm->array2str($SQL_deal);
				
				
			$this->xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." Update `buy_deal` set {$update_deal} where id='{$newDealID}'");
			$this->xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney},cash=cash+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney} where username='{$row['user']}'");
				
				$this->xltm->SQL->Execute("Update `buy_deal` set {$update_deal} where id='{$newDealID}'");
				
								$ip = $_SERVER['REMOTE_ADDR'];

				//返还用户可用值(以及保证金)
				$this->xltm->SQL->Execute("UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney},cash=cash+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney} where username='{$row['user']}'");
				//添加用户帐单信息
				$this->xltm->bill_log($row['user'],$row['agent'],'手续费',(0-$sell_cost_money),$ip."[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的手续费！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！");
				if($cost_sell_limit_money>0)
				{
					$this->xltm->bill_log($row['user'],$row['agent'],'点差费',(0-$cost_sell_limit_money),"[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的点差费({$this->xltm->config['sel_max_time']}分钟内卖出加收)！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！, 加收手续率:".($this->xltm->config['cost_sell_limit']*100)."%！");
				}
				
				if($dc_sellmoney>0)
				{
					$this->xltm->bill_log($row['user'],$row['agent'],'点差费',(0-$dc_sellmoney),"[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的点差费(卖出)！");
				}
				
				$this->xltm->bill_log($row['user'],$row['agent'],'浮盈',$sell_gain,$ip."[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的浮盈！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！");
				return "交易成功";
			}
		}else {
			return '该交易已经平仓了!!';
		}
	}
	
	//自动平仓股票
	function auto_sale_stock($trustID,$shijia)
	{
		if($this->xltm->SellTIME()==0)
		{
			return "休市时间不允许卖出股票！！";
		}

		if($row=$this->xltm->SQL->GetRow("select * from `buy_trust` where id='{$trustID}' and trust_type='2' and app='1'"))
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
			if(!$rowd=$this->xltm->SQL->GetRow("select * from `buy_deal` where id='{$DealID}' and sell='0'"))
			{
				return "";
			}
			$stock_code=$this->xltm->SQL->GetRow("select * from `stock_code` where code='{$row['code']}'");
			if($stock_code['can_sell']==0 && $stock_code['can_sell']!='')
				return '该股票已经满仓,不能卖出！!';
			if($stock_code['stop_pai']==1)
				return '该股票于 '.$stock_code['stop_date'].' 开始停牌,7天内不能买卖!!';

			//平仓操作
			$this->xltm->user_log(9,"卖出股票:{$row['code']}({$row['num']}手)，卖出价:{$cur_price}。");
			
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
				$insert_trust =$this->xltm->array2str($SQL_trust);
				$this->xltm->SQL->Execute("insert into `buy_trust` set {$insert_trust}");
				//新委托买单ID
				$newID=$this->xltm->SQL->LastRowId();
				
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
				$insert_deal=$this->xltm->array2str($SQL_deal);
				$this->xltm->SQL->Execute("insert into `buy_deal` set {$insert_deal}");
				$newDealID=$this->xltm->SQL->LastRowId();//新的持仓记录ID
	
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
				$update = $this->xltm->array2str($SQL_Ary);
				$this->xltm->SQL->Execute("update `buy_deal` set {$update} where id={$DealID}");
			}
			else
			{
				$newDealID = $row['deal_id'];
			}
			
			//卖出操作
			$row = $this->xltm->SQL->GetRow("select * from `buy_deal` where id='{$newDealID}'");
			
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
			$new_price  = $cur_price;
			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  开始 ====================

			include_once("./Lib/curl_http.php");
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
					$dc_num1 = $this->xltm->config['dc'.$d];
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
				$dc_num1 = $this->xltm->config['dc_tou'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//股票股价低于5元的股票点差率
			if ( $new_price < 5 ){
				$dc_num1 = $this->xltm->config['dc_di'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  结束 ====================

			$dc_money = round(($dc_num/1000)*$money,2);
			
			$dc_sellmoney = $dc_money;
}



			//允许卖出时间内卖出加收手续费
			$canSellTime=date('Y-m-d H:i:s',time()-($this->xltm->config['sel_max_time']*60));
			$cost_sell_limit = $row['bull_trust_time']>$canSellTime ? $this->xltm->config['cost_sell_limit'] : 0;
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
			'sell_trust_date'=>$this->xltm->sys_date,
			'sell_trust_time'=>$this->xltm->sys_time
			);
			$update_deal=$this->xltm->array2str($SQL_deal);
			
			$this->xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." Update `buy_deal` set {$update_deal} where id='{$newDealID}''");

			
			$this->xltm->SQL->Execute("Update `buy_deal` set {$update_deal} where id='{$newDealID}'");
			
			//返还用户可用值(以及保证金)
			$this->xltm->SQL->Execute("UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney},cash=cash+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney} where username='{$row['user']}'");
			//添加用户帐单信息
			$this->xltm->bill_log($row['user'],$row['agent'],'手续费',(0-$sell_cost_money),"[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的手续费！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！");
			if($cost_sell_limit_money>0)
			{
				$this->xltm->bill_log($row['user'],$row['agent'],'点差费',(0-$cost_sell_limit_money),"[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的点差费({$this->xltm->config['sel_max_time']}分钟内卖出加收)！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！, 加收手续率:".($this->xltm->config['cost_sell_limit']*100)."%！");
			}
			
			if($dc_sellmoney>0)
			{
				$this->xltm->bill_log($row['user'],$row['agent'],'点差费',(0-$dc_sellmoney),"[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的点差费(卖出)！");
			}
			
			$this->xltm->bill_log($row['user'],$row['agent'],'浮盈',$sell_gain,$_SERVER['REMOTE_ADDR']."{1}[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的浮盈！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！");

			//更新委托单状态
			
			$this->xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." Update `buy_trust` set app='2',stock_deal_time='{$this->xltm->sys_time}' where id='{$trustID}'");

			
			$this->xltm->SQL->Execute("Update `buy_trust` set app='2',stock_deal_time='{$this->xltm->sys_time}' where id='{$trustID}'");
			return "交易成功";
		}
	}
}
?>
