<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();

include_once("./Lib/xltm.class.php");
$type=$_GET['type'];
$times=date('H:i:s',time());

//撤销委托
if($type=='1')
{
	//sleep(3);
	$open=$xltm->startTIME();
	$it=0;
	$h = date('H',time());
	echo '['.$times.']';
	if($open==0)
	{
		echo ' 休市中..';
		if($h<9 || $h>=15)
		{
			$buyRow=$xltm->SQL->GetRows("SELECT * FROM `buy_trust` WHERE app='1'");
			foreach ($buyRow as $r)
			{
				$xltm->SQL->Execute("UPDATE `buy_trust` set app='4',stock_deal_time='{$xltm->sys_time}' where id='{$r['id']}'");
				//返还用户的委托股票的资金(买入委托)
				if($r['trust_type']==1)
				{
					$xltm->SQL->Execute("UPDATE `user_users` set money=money+'{$r['money']}' where username='{$r['user']}'");
				}
				else
				{
					$xltm->SQL->Execute("UPDATE `buy_deal` set can_sell_num=can_sell_num+{$r['num']} where id='{$r['deal_id']}'");
				}
				$it++;
			}
			echo ' 撤销数:'.$it;
		}
	}else {
		echo ' 开市中..';
	}

}
else if($type==2) //平仓留仓股票
{
	echo '['.$times.']';
	$saveDate = $xltm->calc_day();
	$Row=$xltm->SQL->GetRow("SELECT count(*) as count FROM buy_deal WHERE bull_trust_date<'$saveDate' and sell='0'");
	echo '平仓'.$saveDate.'之前 ['.$Row['count'].']';
	$sell=$xltm->SQL->GetRow("select id from buy_deal where bull_trust_date<'$saveDate' and sell='0' order by rand() limit 1");
	if($sell)
	{
		echo $sell['id'];
		$xltm->auto_sell($sell['id']);
	}
}else if ($type==3){ //自动买入卖出
	$open=$xltm->startTIME();
	$success = 0;
	echo '['.$times.']';
	if($open==1)
	{
		$list = '';
		$i=0;
		$trust_ary=array();
		if($Row=$xltm->SQL->GetRows("SELECT * FROM `buy_trust` WHERE app='1'"))
		{
			foreach ($Row as $k)
			{
				$trust_ary[$i]=$k;
				$list .=','.$k['type'].$k['code'];
				$i++;
			}
			include_once("./Lib/curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$list,"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);
			preg_match_all("/str_(.+?)\";/is",$html_data,$data);
			$quote=str_replace('="',",",$data[1]);
			
			$i=0;
			foreach($trust_ary as $r)
			{
				//判断当前价格是否符合
				$Net=explode(',',$quote[$i]);
				if($Net[8]==0 || $Net[7]==0) continue;
				//$cur_price = $r['buy_type']==1 ? $Net[8] : $Net[7];
				$cur_price = $Net[4];
				if($r['trust_type']==1) //买入
				{
					if(($r['buy_type']==1 && $r['price']>=$cur_price) || ($r['buy_type']==2 && $r['price']<=$cur_price))
					{
						$xltm->auto_bull_stock($r['id'],$cur_price);
						$success++;
					}
				}
				else if($r['trust_type']==2) //卖出
				{
					if(($r['buy_type']==1 && $r['price']<=$cur_price) || ($r['buy_type']==2 && $r['price']>=$cur_price))
					{
						$xltm->auto_sale_stock($r['id'],$cur_price);
						$success++;
					}
				}
				$i++;
			}
			echo '成功撮合'.$success.'单';
			
		}
	}else {
		echo '休市中..';
	}
}
else if($type=='4')
{
	$open=$xltm->startTIME();
	echo '['.$times.']';
	if($open==0)
	{
		echo ' 休市中..';
	}else {
		$num = $xltm->baocang();
		echo "爆仓:".$num;
	}

}else {
	$xltm->tpls->LoadTemplate("./tmpfiles/auto.html");
	$xltm->tpls->Show();
}
?>