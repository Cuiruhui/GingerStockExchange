<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
@set_time_limit(0);
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
include_once("./Lib/curl_http.php");
$type=isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
switch($type)
{
	case 'quote':
		$code = $_REQUEST['code'];
		require_once('Lib/curl_http.php');
		$curl=&new Curl_HTTP_Client();
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$code,"",10);
		$html_data=iconv("GB2312","UTF-8",$html_data);
		$data='';
		if($html_data)
		{
			$quote = $html_data;
			exit($quote);
		}
		else
		{
			$quote = false;
		}
		return $quote;
		break;
	case 'deal_quote':
		$code = $_REQUEST['code'];
		$data='';
		$quote = '';
		require_once('Lib/curl_http.php');
		$curl=&new Curl_HTTP_Client();
		$codeary = explode(',',$code);
		//判断总共要循环多少次(每次100条)
		$times = 0;
		if(count($codeary) % 100 ==0)
		{
			$times = count($codeary) / 100;
		}
		else
		{
			$times = floor(count($codeary) / 100) + 1;
		}
		for($i=0;$i<$times;$i++)
		{
			$code = '';
			foreach(array_slice($codeary,$i*100,100) as $item)
			{
				$code .= (empty($code) ? '' : ',').$item;
			}
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$code,"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);
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
		}
		echo $quote;
		exit();
		break;
	case 'servertime':
		echo $xltm->sys_time;
		break;
	case 'dealrepaireall':
		if($rows = $xltm->SQL->GetRows("select id from `buy_deal` where sell='0' and can_sell_num='0'"))
		{
			foreach($rows as $row)
			{
				repaire($row['id']);
			}
		}
		echo "success";
		break;
	case 'dealrepaire':
		$id = isset($_REQUEST['dealid']) && is_numeric($_REQUEST['dealid']) ? intval($_REQUEST['dealid']) : 0;
		if($id<=0) exit('faile,请正确输入要修复的持仓订单号！');
		echo repaire($id);
		break;
	case 'message':
		$msgnum = 0;
		if($row=$xltm->SQL->GetRow("select count(id) as msgnum from `message` where tousername='administrator' and isread='0'"))
		{
			if(is_numeric($row['msgnum']) && $row['msgnum']>0)
			{
				$msgnum = $row['msgnum'];
			}
		}
		echo $msgnum;
		break;
	case 'baocang':
		$open = $xltm->startTIME();
		$num = 0;
		if($open)
		{
			$num = $xltm->baocang();
		}
		echo $num;
		break;
	case 'control':
		
		$num = 0;
		
		
		//if($num = control())
		//{
			//echo $num;
		//}
		break;
	case 'payok':
		$id=$_REQUEST['id'];
		if($row=$xltm->SQL->GetRow("select * from `payment` where result='0' and id='{$id}'"))
		{
			$cash = $row['money'];
			
			$money = $row['money'] * 10;
			$xltm->SQL->Execute("update `user_users` set cash=cash+{$cash},money=money+{$money} where username='{$row['username']}'");
			$xltm->SQL->Execute("update `payment` set result='1',return_time='{$xltm->sys_time}' where result='0' and id='{$id}'");
			exit("true");
		}
		else
		{
			exit("false");
		}
		break;
	case 'cancel_trust':
		$id=$_GET['id'];
		if($row=$xltm->SQL->GetRow("SELECT * FROM `buy_trust` WHERE id='{$id}' and app='1'"))
		{
			$xltm->SQL->Execute("UPDATE `buy_trust` set app='4',stock_deal_time='{$xltm->sys_time}' where id='{$row['id']}'");
			//判断是买入还是卖出
			if($row['trust_type']== 1) //买入
			{
				$xltm->SQL->Execute("UPDATE `user_users` set money=money+'{$row['money']}' where username='{$row['user']}'");
			}
			else
			{
				$xltm->SQL->Execute("UPDATE `buy_deal` set can_sell_num= can_sell_num+{$row['num']} where id='{$row['deal_id']}'");
			}
			exit("true|撤单成功！");
		}else {
			exit("false|该委托交易已经撤销过了！");
		}
		break;
}

function repaire($id=0)
{
	global $xltm;
	if($row = $xltm->SQL->GetRow("select id from `buy_deal` where sell='0' and can_sell_num='0' and id='{$id}'"))
	{
		$xltm->SQL->Execute("update `buy_trust` set app='4',stock_deal_time='{$xltm->sys_time}' where deal_id='{$id}'");
		$xltm->SQL->Execute("update `buy_deal` set can_sell_num=bull_num where sell='0' and can_sell_num='0' and id='{$id}'");
		return 'success';
	}
	else
	{
		return 'faile,该持仓单不存在“无法平仓”的情况，不需要修复！';
	}
}

function control()
{
	global $xltm;
	$num = 0;
	if($sell2=$xltm->SQL->GetRows("select * from `buy_deal` where sell='0'"))
	{
		foreach($sell2 as $d)
		{	
		
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$d['stock_type'].$d['stock_code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);
			if($html_data)
			{
				preg_match("/\"(.+?)\";/is",$html_data,$data);
				$Net=split(',',$data[1]);
		    	if($Net[3]!=0 && $Net[3]!='')
				{
					if(abs($Net[3]-$d['bull_price'])/$d['bull_price']>$xltm->config['more9'])
					{
						$xltm->auto_sell($d['id']);
						$num++;
					}
				}
			}
		}
	}
	return $num;
}
?>