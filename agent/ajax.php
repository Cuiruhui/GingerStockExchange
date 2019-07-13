<?php
require_once('global.php');
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
if($type=='lockuser')
{
	$username = $_REQUEST['username'];
	if($username)
	{
		if($row=$xltm->SQL->GetRow("select * from `user_users` where username='{$username}' and agent='{$xltm->user_info['username']}'"))
		{
			$xltm->SQL->Execute("update `user_users` set active='no' where username='{$username}'");
			exit("true");
		}
	}
	else
	{
		exit("false");
	}
}
else if($type=='deal_quote')
{
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
		exit();
	}
}
?>