<?
/*
委托买卖股票

*/
class buy{
	var $xltm;
	function buy(&$xltm)
	{
		$this->xltm = &$xltm;
	}

	/*委托买
	*/
	function bull_stock()
	{
		$code=$_POST['code']; //代码
		$price=$_POST['buys_price']; //价格
		$type=$_POST['buy_type']; //价格类型
		$price_type=$_POST['price_type'];//市价or委托
		$buy_type=$_POST['buy_type']; //多空
		$num=$_POST['buy_num'];
		//echo '<pre>';
		//print_r($_POST);
		//exit();
		if(!$code)
		$this->xltm->ShowPrompt('请选择要买入的股票.');
		if(!$num)
		$this->xltm->ShowPrompt('请输入购买数量.');
		if($price_type>1 && !$price)
		$this->xltm->ShowPrompt('你选择的股票已经停止购买.');
		if($price_type==2 && !$price)
		$this->xltm->ShowPrompt('请输入委托价格.');
		if(!$type)
		$this->xltm->ShowPrompt('请选择购买方式!!');
		if(!$price_type)
		$this->xltm->ShowPrompt('请选择购买价格!!');
		if($row=$this->xltm->SQL->GetRow("SELECT * FROM stock_code WHERE code='$code'")){
			include("./Lib/curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$row['type'].$row['code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);
			preg_match("/\"(.+?)\";/is",$html_data,$data);
			$Net=split(',',$data[1]);
			$jy_time=date("Y-m-d h:i:s",time());
			//市价买入多和空委托价格 多=$Net[7] 空=$Net[6]
			$bull_price=($buy_type==1)?$Net[7]:$Net[6];
			//委托价格交易
			if($price_type==2){
				$new_price=$price;
				$app=($price == $bull_price)?2:1;
			}else { //市价交易
				$new_price=$bull_price;
				$app=2;
			}
			$money=$num*100*$new_price;
			if($money>$this->xltm->user_info['money'])
			$this->xltm->ShowPrompt('对不起!你信誉额度不够!');
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
			'user_L2'=>$this->xltm->user_info['l2_user'],
			'user_L3'=>$this->xltm->user_info['l3_user'],
			'stock_trust_date'=>$this->xltm->sys_date,
			'stock_trust_time'=>$this->xltm->sys_time
			);
			$insert_trust =$this->xltm->array2str($SQL_trust);
			$this->xltm->SQL->Execute("insert into buy_trust set $insert_trust");
			//委托买单ID
			$newID=$this->xltm->SQL->LastRowId();
			//扣除用户信誉额
			$this->xltm->SQL->Execute("UPDATE user_users set money=money-'".($num*100*$new_price)."' where username='{$this->xltm->user_info['username']}'");
			//如果价格符合或市价交易马上写入交易结果
			if($app==2 && ($Net[7] && $Net[6]))
			{
				$this->xltm->user_log(9,"买入股票:".$row['code']);
				$bull_cost_money=$num*100*$new_price*$this->xltm->user_info['cost_bull'];
				$costArr=$this->xltm->User->user_cost($this->xltm->user_info['username']);
				$SQL_deal=array(
				'user'=>$this->xltm->user_info['username'],
				'stock_code'=>$row['code'],
				'stock_type'=>$row['type'],
				'stock_name'=>$row['name'],
				'bull_price'=>$new_price,
				'bull_num'=>$num,
				'bull_trust_id'=>$newID,
				'bull_trust_date'=>$this->xltm->sys_date,
				'bull_trust_time'=>$this->xltm->sys_time,
				'buy_type'=>$buy_type,
				'bull_money'=>$num*100*$new_price,
				'bull_cost'=>$this->xltm->user_info['cost_bull'],
				'sell_cost'=>$this->xltm->user_info['cost_sell'],
				'bull_cost_money'=>$bull_cost_money,
				'save_cost'=>$this->xltm->user_info['cost_save'],
				'ret'=>$this->xltm->user_info['cost_ret'],
				'agent'=>$this->xltm->user_info['agent'],
				'L2_user'=>$this->xltm->user_info['l2_user'],
				'L3_user'=>$this->xltm->user_info['l3_user'],
				'agent_bull'=>$costArr['agent_bull'],
				'agent_bull_money'=>$costArr['agent_bull']*$money,
				'L2_bull'=>$costArr['login2_bull'],
				'L2_bull_money'=>$costArr['login2_bull']*$money,
				'L3_bull'=>$costArr['agent_bull'],
				'L3_bull_money'=>$costArr['login3_bull']*$money,
				'agent_cent'=>$costArr['agent_cent'],
				'l2_cent'=>$costArr['l2_cent'],
				'l3_cent'=>$costArr['l3_cent']
				);
				$insert_deal=$this->xltm->array2str($SQL_deal);
				$this->xltm->SQL->Execute("insert into buy_deal set $insert_deal");
				$this->xltm->SQL->Execute("UPDATE buy_trust set stock_deal_time='{$this->xltm->sys_time}' where id='{$newID}'");
				$this->xltm->ShowPrompt('交易成功!!',"user_log();$('#buytabs .selected').removeClass('selected');buylist('buy1');$('#buytabs div:nth-child(2)').addClass('selected');");
			}else {
				$this->xltm->user_log(9,$_SERVER['REMOTE_ADDR']."2委托买入:".$row['code']);
				$this->xltm->ShowPrompt('委托交易中,请查看委托交易列表',"$('#buytabs .selected').removeClass('selected');buylist('buy0');$('#buytabs div:nth-child(1)').addClass('selected');");
			}

		}else {
			$this->xltm->ShowPrompt('没有这只股票!');
		}
	}
	/*
	价格符合自动买入
	*/
	/*function auto_bull_stock($trustID)
	{

		if($row=$this->xltm->SQL->GetRow("SELECT * FROM buy_trust WHERE id='$trustID' and app=1")){
			$this->xltm->user_log(9,"委托成功买入股票:".$row['code']);
			$money=$row['num']*100*$row['price'];
			$bull_cost_money=$money*$this->xltm->user_info['cost_bull'];
			$costArr=$this->xltm->User->user_cost($this->xltm->user_info['username']);
			$SQL_deal=array(
			'user'=>$this->xltm->user_info['username'],
			'stock_code'=>$row['code'],
			'stock_type'=>$row['type'],
			'stock_name'=>$row['name'],
			'bull_price'=>$row['price'],
			'bull_num'=>$row['num'],
			'bull_trust_id'=>$trustID,
			'bull_trust_date'=>$this->xltm->sys_date,
			'bull_trust_time'=>$this->xltm->sys_time,
			'buy_type'=>$row['buy_type'],
			'bull_money'=>$money,
			'bull_cost'=>$this->xltm->user_info['cost_bull'],
			'sell_cost'=>$this->xltm->user_info['sell_bull'],
			'bull_cost_money'=>$bull_cost_money,
			'save_cost'=>$this->xltm->user_info['cost_save'],
			'ret'=>$this->xltm->user_info['cost_ret'],
			'agent'=>$this->xltm->user_info['agent'],
			'L2_user'=>$this->xltm->user_info['l2_user'],
			'L3_user'=>$this->xltm->user_info['l3_user'],
			'agent_bull'=>$costArr['agent_bull'],
			'agent_bull_money'=>$costArr['agent_bull']*$money,
			'L2_bull'=>$costArr['login2_bull'],
			'L2_bull_money'=>$costArr['login2_bull']*$money,
			'L3_bull'=>$costArr['agent_bull'],
			'L3_bull_money'=>$costArr['login3_bull']*$money,
			'agent_cent'=>$costArr['agent_cent'],
			'l2_cent'=>$costArr['l2_cent'],
			'l3_cent'=>$costArr['l3_cent']
			);
			$insert_deal=$this->xltm->array2str($SQL_deal);
			$this->xltm->SQL->Execute("insert into buy_deal set $insert_deal");
			$this->xltm->SQL->Execute("UPDATE buy_trust set stock_deal_time='{$this->xltm->sys_time}',app=2 where id='{$trustID}'");
			//$this->xltm->ShowPrompt('交易成功!!',"minute();$('#buytabs .selected').removeClass('selected');buylist('buy1');$('#buytabs div:nth-child(2)').addClass('selected');");
		}
	}*/
	/*
	#市价平仓
	*/
	function sale_stock()
	{
		$SellTIME=$this->xltm->SellTIME();
		if($SellTIME==0)
		$this->xltm->ShowPrompt('当前时间不允许平仓.',"");
		
		$saleID=$_GET['id'];
		if($row=$this->xltm->SQL->GetRow("SELECT * FROM buy_deal WHERE user='{$this->xltm->user_info['username']}' and id='{$saleID}' and app='0'"))
		{
			include("./Lib/curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$row['stock_type'].$row['stock_code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);
			preg_match("/\"(.+?)\";/is",$html_data,$data);
			$jy_time=date("Y-m-d h:i:s",time());
			$Net=split(',',$data[1]);
			$this->xltm->user_log(9,"卖出股票:".$row['stock_code']);
			//市价卖出多和空委托价格 多=$Net[7] 空=$Net[6]
			$sell_price=($row['buy_type']==1)?$Net[7]:$Net[6];
			if($Net[7]==0 || $Net[6]==0)
			$this->xltm->ShowPrompt('平仓失败.(514)',"");
			//插入交易临时表
			$SQL_trust=array(
			'user'=>$this->xltm->user_info['username'],
			'code'=>$row['stock_code'],
			'type'=>$row['stock_type'],
			'name'=>$row['stock_name'],
			'trust_type'=>2,
			'buy_type'=>$row['buy_type'],
			'price'=>$sell_price,
			'app'=>2,
			'num'=>$row['bull_num'],
			'money'=>$row['bull_num']*100*$sell_price,
			'agent'=>$this->xltm->user_info['agent'],
			'user_L2'=>$this->xltm->user_info['l2_user'],
			'user_L3'=>$this->xltm->user_info['l3_user'],
			'stock_trust_date'=>$this->xltm->sys_date,
			'stock_trust_time'=>$this->xltm->sys_time,
			'stock_deal_time'=>$this->xltm->sys_time);
			$insert_trust =$this->xltm->array2str($SQL_trust);
			$this->xltm->SQL->Execute("insert into buy_trust set $insert_trust");
			//委托卖单ID
			$newID=$this->xltm->SQL->LastRowId();
			//卖出金额
			$sell_money=$row['bull_num']*100*$sell_price;
			//卖出手续费金额
			$sell_cost_money=$sell_money*$this->xltm->user_info['cost_sell'];
			//留仓天数
			$save_day=$this->xltm->save_day($this->xltm->sys_date,$row['bull_trust_date']);
			//留仓费用
			$save_money=($sell_money*$row['save_cost'])*$save_day;
			//佣金计算
			$ret_money=$sell_cost_money*$row['ret'];
			if($row['buy_type']==1)
			{
				//盈亏计算 卖出金额-买入金额-买入手续费-卖出手续费-留仓费用/+佣金/
				$sell_gain=$sell_money-$row['bull_money']-$row['bull_cost_money']-$sell_cost_money-$save_money;
				$gain=$sell_money-$row['bull_money'];
			}else {
				//盈亏计算 买入金额-买入金额-买入手续费-卖出手续费-留仓费用+佣金
				$sell_gain=$row['bull_money']-$sell_money-$row['bull_cost_money']-$sell_cost_money-$save_money;
				$gain=$row['bull_money']-$sell_money;
			}
			$costArr=$this->xltm->User->user_cost($row['user']);
			$agent_gain=$gain-(($costArr['agent_sell']*$sell_money)+$row['agent_bul_money']+(($costArr['agent_save']*$sell_money)*$save_day));
			$L2_gain=$gain-(($costArr['login2_sell']*$sell_money)+$row['L2_bul_money']+(($costArr['login2_save']*$sell_money)*$save_day));
			$L3_gain=$gain-(($costArr['login3_sell']*$sell_money)+$row['L3_bul_money']+(($costArr['login3_save']*$sell_money)*$save_day));
			$SQL_deal=array(
			'sell'=>1, //设置为已经卖出
			'sell_price'=>$sell_price,
			'sell_cost'=>$this->xltm->user_info['cost_sell'],
			'sell_cost_money'=>$sell_cost_money,
			'sell_money'=>$sell_money,
			'sell_trust_id'=>$newID,
			'sell_trust_date'=>$this->xltm->sys_date,
			'sell_trust_time'=>$this->xltm->sys_time,
			'save_money'=>$save_money,
			'sell_gain'=>$sell_gain,
			'gain'=>$gain,
			'agent_gain'=>$agent_gain,
			'L2_gain'=>$L2_gain,
			'L3_gain'=>$L3_gain,
			'ret_money'=>$ret_money,
			'agent_sell'=>$costArr['agent_sell'],
			'agent_sell_money'=>$costArr['agent_sell']*$sell_money,
			'L2_sell'=>$costArr['login2_sell'],
			'L2_sell_money'=>$costArr['login2_sell']*$sell_money,
			'L3_sell'=>$costArr['agent_bull'],
			'L3_sell_money'=>$costArr['login3_sell']*$sell_money,
			'agent_save'=>$costArr['agent_save'],
			'agent_save_money'=>($costArr['agent_save']*$sell_money)*$save_day,
			'L2_save'=>$costArr['login2_save'],
			'L2_save_money'=>($costArr['login2_save']*$sell_money)*$save_day,
			'L3_save'=>$costArr['agent_bull'],
			'L3_save_money'=>($costArr['login3_save']*$sell_money)*$save_day,
			'agent_ret'=>$costArr['agent_ret'],
			'agent_ret_money'=>$costArr['agent_ret']*$sell_money,
			'L2_ret'=>$costArr['login2_ret'],
			'L2_ret_money'=>$costArr['login2_ret']*$sell_money,
			'L3_ret'=>$costArr['agent_bull'],
			'L3_ret_money'=>$costArr['login3_ret']*$money
			);
			$update_deal=$this->xltm->array2str($SQL_deal);
			$this->xltm->SQL->Execute("UPDATE buy_deal set $update_deal where id='{$saleID}'");
			//返还用户信誉额
			$this->xltm->SQL->Execute("UPDATE user_users set money=money+'{$row['bull_money']}' where username='{$this->xltm->user_info['username']}'");
			if($_COOKIE['buy4'])
			$this->xltm->ShowPrompt('平仓成功.',"user_log();buylist('buy4');");
			else 
			$this->xltm->ShowPrompt('平仓成功.',"user_log();$('#buytabs .selected').removeClass('selected');buylist('buy2');$('#buytabs div:nth-child(3)').addClass('selected');");
		}else {
			$this->xltm->ShowPrompt('该交易已经平仓了!!',"$('#buytabs .selected').removeClass('selected');buylist('buy2');$('#buytabs div:nth-child(3)').addClass('selected');");
		}
	}
}
?>
