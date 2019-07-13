<?php
set_time_limit(0);
ob_end_clean();
//ob_implicit_flush(true);
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
include_once("./Lib/curl_http.php");
echo str_pad(" ", 4096);
if($xltm->startTIME())
{
	exit("<font color=red>目前处于开市时间，不能进行清算！</font>");
}
echo "<style>*{font-size:12px; line-height:23px;}</style>";
echo "<font color=red>控盘中，请不要关闭本页面！请稍候...</font><br />";
ob_flush();
flush();
$i = 1;
$j = 1;
$can_bull=1;
$totalcount = 0;
$totalpage = 0;
$pagesize = 10;
$curl=&new Curl_HTTP_Client();
if($row=$xltm->SQL->GetRow("select count(id) as totalcount from `stock_code`"))
{
	if(is_numeric($row['totalcount']) && $row['totalcount']>0)
	{
		$totalcount = $row['totalcount'];
	}
}
if($totalcount==0) exit("暂无找到股票代码数据");
if($totalcount % $pagesize ==0)
{
	$totalpage = $totalcount / $pagesize;
}
else
{
	$totalpage = ceil($totalcount / $pagesize) + 1;
}
for($i=1;$i<=$totalpage;$i++)
{
	if($rows=$xltm->SQL->GetRows("select * from `stock_code` order by code asc limit ".($i-1)*$pagesize.",".$pagesize))
	{
		$cl = '';
		foreach($rows as $r)
		{
			$cl.=(($cl=='')?'':',').$r['type'].$r['code'];
		}
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$cl,"",10);
		$html_data=iconv("GB2312","UTF-8",$html_data);
		preg_match_all("/var hq_str_(sz|sh)(.+?)\";/is",$html_data,$data);
		$quote = str_replace("=\"",",",$data[2]);
		foreach($quote as $q)
		{
			
			$Net=split(',',$q);
			if(count($Net)<5) //退市
			{
				continue;
			}
			if(ceil($Net[10])<$xltm->config['turnover_min']*10000) //小于设定的值
			{
				$can_bull=0;
				echo $j.".".$Net[1]."(".$Net[0].") 成交额:".round(ceil($Net[10])/10000,2)."万元 小于设定值设为禁止买入…<br />";
				$j++;
			}
			else
			{
				$cj = abs(round($Net[4] - $Net[3],2));
				$zdf = (round($cj / $Net[3],4))*100;

				if(preg_match("/st/i",$Net[1]) && $zdf>=$xltm->config['st_buy_sd']) //st股
				{
					$can_bull = 0;
					echo $j.".".$Net[1]."(".$Net[0].") 涨跌幅>=".$xltm->config['st_buy_sd']."%设为禁止买入…<br />";
					$j++;
				}
				else if(!preg_match("/st/i",$Net[1]) && $zdf>=$xltm->config['buy_sd']) //正常股
				{
					$can_bull = 0;
					echo $j.".".$Net[1]."(".$Net[0].") 涨跌幅>=".$xltm->config['buy_sd']."%设为禁止买入…<br />";
					$j++;
				}
				else
				{
					$can_bull=1;
				}
			}
			$xltm->SQL->Execute("Update `stock_code` set can_bull='{$can_bull}' where code='{$Net[0]}'");
			sleep(0.1);
		}
	}
}
ob_end_flush();
echo "<font color=red>控盘完毕！</font><script>alert('买入控盘完毕!')</script>";
?>
