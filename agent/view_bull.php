<?php
include_once('global.php');
$type = isset($_GET['type'])?$_GET['type']:'';
if($type=='get_stock')
{
	    include_once("../Lib/curl_http.php");
		$page=$_GET['page'];
		$PageSize=$_GET['PageSize'];
		$limitPage=($page==1)?0:($page*$PageSize)-$PageSize;
		$bull=$xltm->SQL->GetRows("SELECT id,stock_code,stock_type FROM buy_deal where sell='0' and agent='{$MyUser}' ORDER BY `bull_trust_time` DESC LIMIT $limitPage,$PageSize");
		$list='';
		foreach ($bull as $tr)
		{
			$list .=$tr['stock_type'].$tr['stock_code'].',';
		}
		$list=substr($list,0,-1);
		$curl=&new Curl_HTTP_Client();
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$list,"",10);
		$html_data=iconv("GB2312","UTF-8",$html_data);
		preg_match_all("/str_(.+?)\";/is",$html_data,$data);
		$messages=str_replace('="',",",$data[1]);
		$list="0,0|*|";
		$iv=0;
		foreach($messages as $l)
		{
			$N=split(',',$l,12);
			$N[0]=substr($N[0],2);
			$temp=$bull[$iv]['id'].",";
			for($i=0;$i<8;$i++)
			{
				$temp .=$N[$i].",";
			}
			$list .=substr($temp,0,-1)."|*|";
			$iv++;
		}
		echo $list;
		exit;
}
else if($type=='sale_stock')//强制平仓
{
	$dealID=$_GET['id'];
	$xltm->auto_sell($dealID);
	exit();
}
$xltm->user_log(1,"浏览未平仓列表");
$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
$demo = isset($_GET['demo']) ? $_GET['demo'] : '-1';
$inquery = '';
if($demo!='-1') $inquery .= " and b.demo='$demo'";
if($username) $inquery .=" and a.user='$username'";
$PageSize = 30;
$query = "SELECT a.* FROM buy_deal a left join user_users b on a.user=b.username where a.sell='0' $inquery and a.agent='{$MyUser}' ORDER BY a.bull_trust_time DESC ";
$bull=$xltm->SQL->GetRows($query);
$xltm->tpls->LoadTemplate("./tmpfiles/view_bull.html");
$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
$RecCnt =$xltm->tpls->MergeBlock('tr','array',$bull);
$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
$showPage=($RecCnt>0)?1:'';
$xltm->tpls->Show();
function bull_event($BlockName,&$CurrRec,$RecNum){
	global $xltm;
	$CurrRec['save_day']=$xltm->save_day($xltm->sys_date,$CurrRec['bull_trust_date']);
}
?>
