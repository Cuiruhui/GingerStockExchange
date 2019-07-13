<?php
session_start();
define('Load_Info', true);
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");
require 'QueryList.class.php';
$type=$_REQUEST['type'];

switch ($type)
{
	
	case 'bull':
		
		$stockcode=$_POST['code']; //代码
		$price_type=$_POST['price_type'];//市价
		$buy_type=$_POST['buy_type']; //多空
		$num=$_POST['buy_num'];
		
		if(!$stockcode)
			exit("false|请选择要买入的股票！");
		if(!$num||$num<0)
			exit("false|请输入购买数量！");
		if(!$buy_type)
			exit("false|请选择买升或买跌！");
		if(!$price_type)
			exit("false|请选择购买价格类型(市价)！");
	
			
		
		
		if($row=$xltm->SQL->GetRow("SELECT * FROM `usstock_code` WHERE code='$stockcode'")){
			if($row['can_bull']==0)
				exit("false|该股票已经满仓！<br />“满仓”是指:其他会员购买该只股票数量已经超过500万股无法再买入！");
			
			if($row['can_bull_up']==0 && $buy_type==1) //买升且系统设置禁买升
			{
				exit("false|该股票已经满仓！<br />“满仓”是指:其他会员购买该只股票数量已经超过500万股无法再买入！");
			}
			if($row['can_bull_down']==0 && $buy_type==2) //买跌且系统设置禁买跌
			{
				exit("false|该股票已经满仓！<br />“满仓”是指:其他会员购买该只股票数量已经超过500万股无法再买入！");
			}
			
			$code =strtoupper($stockcode);
			$stockarea =$row['type'];
			$url = "http://gu.qq.com/us".$code.".".$stockarea."/gg";
	
			$reg = array("xianjia"=>array(".item-1 span:eq(0)","text"),"jinkai"=>array(".col-2.fr span:eq(1)","text"),"zuoshou"=>array(".col-2.fr span:eq(9)","text"),"zuigao"=>array(".col-2.fr span:eq(3)","text"),"zuidi"=>array(".col-2.fr span:eq(11)","text"),"chengjiao"=>array(".col-2.fr span:eq(7)","text"),"shizhi"=>array(".col-2.fr span:eq(15)","text"),"zd"=>array(".item-2 span:eq(0)","text"),"zdf"=>array(".item-2 span:eq(1)","text"),"codename"=>array(".title_bg h1:eq(0)","text"));
			$qy = new QueryList($url,$reg);
			$arr = $qy->jsonArr;
			//$arrstr=$arr[0][codename].','.$arr[0][xianjia].','.$arr[0][shizhi].','.$arr[0][jinkai].','.$arr[0][zuigao].','.$arr[0][zuidi].','.$arr[0][zuoshou].','.$arr[0][zd].','.$arr[0][zdf].','.$arr[0][chengjiao];
			
			$jy_time=date("Y-m-d h:i:s",time());
			//市价买入多和空委托价格 多=$Net[7] 空=$Net[6]
			//$cur_price=($buy_type==1)?$Net[7]:$Net[6];
			$cur_price = floatval($arr[0][xianjia]);
			
			//委托价格交易
			if($price_type==2){
				
			
			}else { //市价交易

				//2012-4-12 李兴，这里加入滑价金额。 单价加滑价后，再四舍五入。==================================
				$price_float	= 0 ;
				$app			= 2 ;

				if ( $buy_type == 1 ){		//买涨
					$price_float = $cur_price * $xltm->config['up_float'] ;
					$new_price = $cur_price + $price_float;
					$new_price = round($new_price, 2);

				}elseif ( $buy_type == 2 ){	//买跌
					$price_float = $cur_price * $xltm->config['down_float'] ;
					$new_price = $cur_price - $price_float;
					$new_price = round($new_price, 2);

				}else{
					$new_price = $cur_price;

				}
				//2012-4-12 李兴，这里加入滑价金额。 单价加滑价后，再四舍五入。==================================

			}
			//exit("false|$new_price");
			
			
			$buy_sd=$xltm->config['buy_sd']; //7.5幅度
			$nowcje=$cur_price*$num*100;
			$zuidijiaoyi=$xltm->config['turnover_min'];
			if($nowcje<=$zuidijiaoyi)
			{
				exit("false|下单金额必须大于{$zuidijiaoyi}万！");
			}


			if($num<$xltm->user_info['num_min'])
				exit("false|最低要买入{$xltm->user_info['num_min']}手！");
			if($num>$xltm->user_info['num_max'])
				exit("false|最多只能买入{$xltm->user_info['num_max']}手！");
			
			$money = floatval($cur_price)*floatval($num)*100;
			$bull_cost_money = round($money*($xltm->user_info['cost_bull']),2); //交易手续费


			

			$dc_num		= $row['dc'];
			$dc_num1	= 0;
			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  开始 ====================
			
			$chajia1 = $arr[0][zdf];
			$chajia = abs(floatval(str_replace('%', '', $chajia1)));
			
			// $chajia  这个就是涨跌百分比。
			for( $d = 1; $d <=9; $d++){
				if ($chajia >= $d ){		//涨跌率大于 设置的百分比，就读取相应的点差率值。
					$dc_num1 = $xltm->config['dc'.$d];
				}
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			$nowchengjiaoe = floatval($arr[0][chengjiao])*10000;
			
			
			//成交额大于五千万 的点差率。
			if ( $nowchengjiaoe > 2000000){		
				$dc_num1 = $xltm->config['dc_wan200'];
			}
			if ( $nowchengjiaoe > 5000000){		
				$dc_num1 = $xltm->config['dc_wan500'];
			}
			if ( $nowchengjiaoe > 8000000){		
				$dc_num1 = $xltm->config['dc_wan800'];
			}
			if ( $nowchengjiaoe > 12000000){		
				$dc_num1 = $xltm->config['dc_wan1200'];
			}
			if ( $nowchengjiaoe > 20000000){		
				$dc_num1 = $xltm->config['dc_wan2000'];
			}
			if ( $nowchengjiaoe > 50000000){		
				$dc_num1 = $xltm->config['dc_wan'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于8千万 的点差率。
			if ( $nowchengjiaoe > 80000000){		
				$dc_num1 = $xltm->config['dc_wan2'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于1.2亿 的点差率。
			if ( $nowchengjiaoe > 120000000){		
				$dc_num1 = $xltm->config['dc_wan3'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;



			//股票股价低于5元的股票点差率
			if ( $new_price < 5 ){
				$dc_num1 = $xltm->config['dc_di'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  结束 ====================

			$dc_money = round($money*($dc_num/1000),2); //点差费，买入收取

			
			$totalmoney = $money + $bull_cost_money + $dc_money; //总金额(买入总价+手续费+点差费)
			
			$usercancash = round($xltm->AvailableCash($xltm->user_info['username']),2); //用户当前可用保证金

			if($totalmoney>($usercancash * $xltm->config['cost_exchange_rate'] ))		//2012-4-14李兴，修改。
			{
				exit("false|你的可用资金不足".round($totalmoney,2)."!请充值后再来购买！");
			}




			//******************************* 2012-4-14李兴，这里再加上，将 1 人民币兑换成 100 股币后，单支股票只能是金额的十分之一。

			$money_percent = $xltm->config['money_percent'];

			if($money_percent > 0 )
			{
				$percent10 = $usercancash * $xltm->config['cost_exchange_rate'] * ($money_percent / 100) ;	//可用金额的百分之十.

				if( $money > $percent10 ) //不能超过十分之一。
				{
					exit("false|购买提示：单只股票交易额限已超出，最多<font color=red>{$percent10}</font>元。<br/>");
				}
			}

			//******************************* 2012-4-14李兴，这里再加上，将 1 人民币兑换成 100 股币后，单支股票只能是金额的十分之一。



			//插入交易临时表
			$SQL_trust=array(
			'user'=>$xltm->user_info['username'],
			'code'=>$row['code'],
			'type'=>$row['type'],
			'name'=>$row['name'],
			'trust_type'=>1,
			'buy_type'=>$buy_type,
			'price'=>$new_price,
			'app'=>$app,
			'num'=>$num,
			'money'=>$money,
			'agent'=>$xltm->user_info['agent'],
			'stock_trust_date'=>$xltm->sys_date,
			'stock_trust_time'=>$xltm->sys_time
			);
			$insert_trust =$xltm->array2str($SQL_trust);
			
			$xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." insert into `buy_trust` set $insert_trust");
			
			
			$xltm->SQL->Execute("insert into `buy_trust` set $insert_trust");
			//委托买单ID
			$newID=$xltm->SQL->LastRowId();
			//扣除用户可用资金
			$xltm->SQL->Execute("UPDATE `user_users` set money=money-'{$money}' where username='{$xltm->user_info['username']}'");
			//如果价格符合或市价交易马上写入交易结果
			if($app==2 && ($cur_price>0))
			{
				$xltm->user_log(9,"买入股票:".$row['code']);
				$costArr=$xltm->User->user_cost($xltm->user_info['username']);
				$SQL_deal=array(
				'user'=>$xltm->user_info['username'],
				'stock_code'=>$row['code'],
				'stock_type'=>$row['type'],
				'stock_name'=>$row['name'],
				'bull_price'=>$new_price,
				'bull_num'=>$num,
				'can_sell_num'=>$num,
				'bull_trust_id'=>$newID,
				'bull_trust_date'=>$xltm->sys_date,
				'bull_trust_time'=>$xltm->sys_time,
				'buy_type'=>$buy_type,
				'bull_money'=>$money,
				'bull_cost'=>$costArr['member_bull'],
				'sell_cost'=>$costArr['member_sell'],
				'bull_cost_money'=>$bull_cost_money, //买入手续费
				'save_cost'=>$costArr['member_save'],
				'agent'=>$xltm->user_info['agent'],
				'agent_bull_money'=>round(($bull_cost_money+$dc_money)*($costArr['agent_ret']/100),2),
				'agent_ret'=>$costArr['agent_ret'],
				'dc_num'=>$dc_num,
				'dc_money'=>$dc_money
				);
				$insert_deal=$xltm->array2str($SQL_deal);
				
				$ip = $_SERVER['REMOTE_ADDR'];
				
				$xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." insert into `buy_deal` set $insert_deal");
				$xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." UPDATE `buy_trust` set stock_deal_time='{$xltm->sys_time}' where id='{$newID}'");
				
				$xltm->SQL->Execute("insert into `buy_deal` set $insert_deal");
				$xltm->SQL->Execute("UPDATE `buy_trust` set stock_deal_time='{$xltm->sys_time}' where id='{$newID}'");
				
				
				
				//扣除买入手续费（保证金和可用资金）
				$xltm->SQL->Execute("Update `user_users` set cash=cash-{$bull_cost_money}-{$dc_money},money=money-{$bull_cost_money}-{$dc_money} where username='{$xltm->user_info['username']}'");
				//添加用户帐单信息
				$xltm->bill_log($xltm->user_info['username'],$xltm->user_info['agent'],'手续费',(0-$bull_cost_money),$ip."买入股票{$row['name']}({$row['code']})手续费！买入价:{$new_price}元, 数量:{$num}(手)！");
				if($dc_money>0)
				{
					$xltm->bill_log($xltm->user_info['username'],$xltm->user_info['agent'],'点差费',(0-$dc_money),$ip."买入股票{$row['name']}({$row['code']})点差费(买入收取)！买入价:{$new_price}元, 数量:{$num}(手), 点差:{$dc_num}‰！");
				}
				exit("true|交易成功！");
			}else {
				$xltm->user_log(9,"委托买入:".$row['code']);
				exit("true|委托单已送出！委托单状态请到“当日委托查询”功能查看！");
			}
		}else {
			exit("false|没有这只股票");
		}
		
		break;
	case 'sale':
		$sell_name=$_GET['stockname'];
		$sell_code=$_GET['stockcode'];
		$sell_id=$_GET['id'];
		$sell_num=$_GET['num'];
		$SellTIME=$xltm->SellTIME();
		$sell_cost = 0;
		if($SellTIME==0)
		{
			exit("false|休市中");
		}
		if($sell_num==0||$sell_num<0)
		{
			exit("false|卖出数量必须大于0");
		}
		if($sell_code=='' || $sell_id=='')
		{
			exit("false|股票代码或者订单号不能为空");
		}
		if($row=$xltm->SQL->GetRow("SELECT * FROM `buy_deal` WHERE user='{$xltm->user_info['username']}' and id='{$sell_id}' and app='0'"))
		{
			$stock_code=$xltm->SQL->GetRow("select * from `usstock_code` where code='{$row['stock_code']}'");
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
			if(floatval($arr[0][xianjia])==0)
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
						$price_float = $cur_price * $xltm->config['up_float'] ;
						$new_price = $cur_price - $price_float;
						$new_price = round($new_price, 2);

					}elseif ( $buy_type == 2 ){	//买跌
						$price_float = $cur_price * $xltm->config['down_float'] ;
						$new_price = $cur_price + $price_float;
						$new_price = round($new_price, 2);

					}else{
						$new_price = $cur_price;

					}
					$sell_price = $cur_price = $new_price;

					
				}

//exit($new_price);
				//2012-4-12 李兴，这里加入滑价金额。 单价加滑价后，再四舍五入。==================================



				//卖出股票操作
				$xltm->user_log(9,"卖出股票:{$row['stock_code']}({$row['bull_num']}手)，卖出价:{$cur_price}。");
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
					'stock_trust_date'=>$xltm->sys_date,
					'stock_trust_time'=>$xltm->sys_time,
					'stock_deal_time'=>$xltm->sys_time
				);
				$insert_trust =$xltm->array2str($SQL_trust);
				$xltm->SQL->Execute("insert into buy_trust set $insert_trust");
				//委托卖单ID
				$newID=$xltm->SQL->LastRowId();
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
					$dc_num1 = $xltm->config['dc'.$d];
				}
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			
			//成交额大于五千万 的点差率。
			if ( $nowchengjiaoe > 50000000){		
				$dc_num1 = $xltm->config['dc_wan'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于8千万 的点差率。
			if ( $nowchengjiaoe > 80000000){		
				$dc_num1 = $xltm->config['dc_wan2'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于1.2亿 的点差率。
			if ( $nowchengjiaoe > 120000000){		
				$dc_num1 = $xltm->config['dc_wan3'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//股票股价低于5元的股票点差率
			if ( $new_price < 5 ){
				$dc_num1 = $xltm->config['dc_di'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  结束 ====================

			$dc_money = round(($dc_num/1000)*$money,2);
			
			$dc_sellmoney = $dc_money;
}

				//允许卖出时间内卖出加收手续费
				$canSellTime=date('Y-m-d H:i:s',time()-($xltm->config['sel_max_time']*60));
				$cost_sell_limit = $row['bull_trust_time']>$canSellTime ? $xltm->config['cost_sell_limit'] : 0;
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
				'sell_trust_date'=>$xltm->sys_date,
				'sell_trust_time'=>$xltm->sys_time
				);
				$update_deal=$xltm->array2str($SQL_deal);
				
				
			$xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." Update `buy_deal` set {$update_deal} where id='{$newDealID}'");
			$xltm->SQL->_Message($_SERVER['REMOTE_ADDR']." UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney},cash=cash+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney} where username='{$row['user']}'");
				
				$xltm->SQL->Execute("Update `buy_deal` set {$update_deal} where id='{$newDealID}'");
				
								$ip = $_SERVER['REMOTE_ADDR'];

				//返还用户可用值(以及保证金)
				$xltm->SQL->Execute("UPDATE `user_users` set money=money+{$sell_money}+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney},cash=cash+{$sell_gain}-{$sell_cost_money}-{$cost_sell_limit_money}-{$dc_sellmoney} where username='{$row['user']}'");
				//添加用户帐单信息
				$xltm->bill_log($row['user'],$row['agent'],'手续费',(0-$sell_cost_money),$ip."[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的手续费！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！");
				if($cost_sell_limit_money>0)
				{
					$xltm->bill_log($row['user'],$row['agent'],'点差费',(0-$cost_sell_limit_money),"[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的点差费({$xltm->config['sel_max_time']}分钟内卖出加收)！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！, 加收手续率:".($xltm->config['cost_sell_limit']*100)."%！");
				}
				
				if($dc_sellmoney>0)
				{
					$xltm->bill_log($row['user'],$row['agent'],'点差费',(0-$dc_sellmoney),"[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的点差费(卖出)！");
				}
				
				$xltm->bill_log($row['user'],$row['agent'],'浮盈',$sell_gain,$ip."[{$newDealID}]卖出股票{$row['stock_name']}({$row['stock_code']})的浮盈！卖出价:{$cur_price}元, 数量:{$row['bull_num']}手！");
				exit("true|成功平仓");
			}
		}else {
			exit("false|该交易已经平仓");
		}
		
		break;
	case 'buytime':
		$canSellTime=date('Y-m-d H:i:s',time()-($xltm->config['sel_max_time']*60));
		$row=$xltm->SQL->GetRow("Select bull_trust_time from `buy_deal` where id='{$_GET['id']}'");
		if(is_array($row))
		{
			if($row['bull_trust_time']>$canSellTime)
			{
				echo 'false';
			}
			else
			{
				echo 'true';
			}
		}
		else
		{
			echo 'false';
		}
		break;	
		
}

?>
