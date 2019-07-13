<?
header("Content-Type: text/html; charset=utf-8");
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
require_once(ROOT."/Lib/curl_http.php");
$curl=&new Curl_HTTP_Client();
$list=$_GET['stockcode'];
if($list)
{
	$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$list,"",5);
	$html_data=iconv("GB2312","UTF-8",$html_data);
	$data='';
	if($html_data)
	{
		preg_match_all("/str_(.+?)\";/is",$html_data,$data);
		$messages=str_replace('="',",",$data[1]);
	}
	$list="";
	foreach($messages as $l)
	{
		$N=split(',',$l,31);
		$N[0]=substr($N[0],2);
		$temp="";
		for($i=0;$i<30;$i++)
		{

			$temp .=$N[$i].",";
		}
		$list .=substr($temp,0,-1)."^";
	}
	echo $list;
}
?>