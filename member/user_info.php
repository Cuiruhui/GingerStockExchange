<?php
session_start();
define('Load_Info', 1);
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
if($xltm->user_info['username']!='')
{
	ini_set('display_errors',true);
	//获取用户可用保证金
	$type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
	if($type=='cancash')
	{
		$cancash = round($xltm->AvailableCash(),2);
		if($cancash<$xltm->config['lower_cash'] && $xltm->user_info['demo']=='0') //只对正式会员提示
		{
			print 'lower您当前可用保证金：'.$cancash.' 低于系统设定的 <font color=red><b>￥'.$xltm->config['lower_cash'].'</b></font>！<br />为了保证您的交易能正常进行，请及时充值！<br />是否现在去充值？';
		}
		else
		{
			print 'ok';
		}
		exit();
	}
	$list='sh000001,sz399001';
	$i=0;
	$trust_ary=array();
	if ($xltm->startTIME()) {
		if($Row=$xltm->SQL->GetRows("SELECT * FROM `buy_trust` WHERE user='{$xltm->user_info['username']}' and app='1'"))
		{
			foreach ($Row as $k)
			{
				$trust_ary[$i]=$k;
				$list .=','.$k['type'].$k['code'];
				$i++;
			}
		}
	}
	require_once("./Lib/curl_http.php");
	$curl=&new Curl_HTTP_Client();
	$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$list,"",10);
	$html_data=iconv("GB2312","UTF-8",$html_data);
	if($html_data)
	{
		preg_match_all("/str_(.+?)\";/is",$html_data,$data);
		$quote=str_replace('="',",",$data[1]);
		$dp_data =$quote[0]."#".$quote[1];
	}
	else
	{
		$dp_data='#';
	}
	$dp_data .= '|'.$xltm->SellTIME();
	print $dp_data; //大盘数据
	
	/*委托撮合*/
	$isopen = $xltm->startTIME();
	if(count($trust_ary)>0 && $isopen==1)
	{
		$i=2;
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
					$xltm->buy->auto_bull_stock($r['id'],$cur_price);
				}
			}
			else if($r['trust_type']==2) //卖出
			{
				if(($r['buy_type']==1 && $r['price']<=$cur_price) || ($r['buy_type']==2 && $r['price']>=$cur_price))
				{
					$xltm->buy->auto_sale_stock($r['id'],$cur_price);
				}
			}
			$i++;
		}
	}
} else {
	$xltm->outlogin();
}
?>