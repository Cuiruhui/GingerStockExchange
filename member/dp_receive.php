<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: text/html; charset=utf-8");
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$curl=&new Curl_HTTP_Client();
$html_data=$curl->fetch_url("http://hq.sinajs.cn/rn=1244000422000&list=sh000001,sz399001","",10);
$html_data=iconv("GB2312","UTF-8",$html_data);
if($html_data)
{
	preg_match_all("/str_(.+?)\";/is",$html_data,$data);
	$messages=str_replace('="',",",$data[1]);
}
$list="";
foreach($messages as $l)
{
	$N=split(',',$l,12);
	$N[0]=substr($N[0],2);
	$temp="";
	for($i=0;$i<11;$i++)
	{
		
		$temp .=$N[$i].",";	
	}
	$list .=substr($temp,0,-1)."|*|";
    
}
echo $list;
?>