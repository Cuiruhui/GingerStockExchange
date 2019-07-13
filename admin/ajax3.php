<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
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
			//preg_match("/str_(.+?)\";/is",$html_data,$data);
			//$Net = str_replace('="',",",$data[1]);
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
		require_once('Lib/curl_http.php');
		$curl=&new Curl_HTTP_Client();
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$code,"",10);
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
			exit($quote);
		}
		else
		{
			$quote = false;
		}
		return $quote;
		break;
	case 'servertime':
		echo $xltm->sys_time;
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
		baocang();
		break;
}

function baocang()
{
	global $xltm;
}
?>