<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
ob_end_clean();
define('Load_Info', false);
include_once("./Lib/xltm.class.php");
include_once("./Lib/curl_http.php");
include_once("./Lib/ChineseSpell.php");
echo str_pad(" ", 4096);
echo "<style>*{font-size:12px; line-height:23px;}</style>";
echo "<font color=red>[{$xltm->sys_time}]更新股票代码中,请稍候...</font><br />";
ob_flush();
flush();
$curl=new Curl_HTTP_Client();
$chs = new ChineseSpell();
$curl->set_referrer("http://quote.eastmoney.com/");
$html=$curl->fetch_url("http://quote.eastmoney.com/stocklist.html","",10);
if($html)
{
	$html = iconv("GB2312","UTF-8",$html);
	preg_match_all("/<li><a target=\"_blank\" href=\"http:\/\/quote\.eastmoney\.com\/(.+?)\.html\">(.+?)\(\d+\)<\/a><\/li>/is",$html,$stock);
	$code = $stock[1];
	$name = $stock[2];
	$i=0;
	foreach($code as $c)
	{
		$stockcode = substr($c,2);
		$stocktype = substr($c,0,2);
		$stockname = trim($name[$i]);
		//获取拼音简写
		$pinyin = strtolower($chs->getFirstLetter(iconv('UTF-8','GB2312',str_replace('*','',$stockname))));
		if(substr($stockcode,0,1)=='0' || substr($stockcode,0,1)=='6' || substr($stockcode,0,1)=='3')
		{
			//不存在添加
			if(!$row=$xltm->SQL->GetRow("SELECT * FROM stock_code where code='{$stockcode}'")){
				echo "[{$xltm->sys_time}]成功添加股票代码:{$stockcode}, 【{$stockname}】 <br />";
				$xltm->SQL->Execute("insert into `stock_code` set stop_date='0000-00-00',can_sell='0',can_bull='0',can_bull_up='1',can_bull_down='1',code='{$stockcode}',name='{$stockname}',type='$stocktype',pinyin='{$pinyin}'");
			}
			else if(trim($row['name'])!=trim($stockname)) //更新名称
			{
				echo "[{$xltm->sys_time}]成功的更新了股票名称:{$stockcode},从【{$row['name']}】更改成【{$stockname}】<br />";
				$xltm->SQL->Execute("update `stock_code` set name='{$stockname}',pinyin='{$pinyin}' where code='{$stockcode}'");
			}
		}
		$i++;
	}
}
echo "[{$xltm->sys_time}] 更新完毕！";
$xltm->showMsg('检查新股','更新股票完毕！',true,'nothing');
	
function get_code($pn)
{
	global $xltm,$curl;
	$html=$curl->fetch_url("http://stockqt.gtimg.cn/cgi-bin/hcenter/q?v=1&id=107&pn=$pn&r=4006768584","",10);
	if($html){
		preg_match("/v_tpage=\"(.+?)\";/is",$html,$temp1);
		$t_page=$temp1[1];
		preg_match("/v_cpage=\"(.+?)\";/is",$html,$temp2);
		$c_page=$temp2[1];
		preg_match("/v_hq_info=\"(.+?)\"/is",$html,$temp3);
		$tem_list=explode("~^",$temp3[1],-1);
		foreach ($tem_list as $t)
		{
			$t=iconv("GB2312","UTF-8",$t);
			$tv=explode('~',$t);
			if(!$row=$xltm->SQL->GetRow("SELECT * FROM stock_code where code='{$tv[1]}'")){
				$t_code=substr($tv[1], 0, 3);
				$type=($t_code=='000' || $t_code=='300')?'sz':'sh';
				$xltm->SQL->Execute("insert into stock_code set can_sell='0',can_bull='0',can_bull_up='1',can_bull_down='1',code='{$tv[1]}',name='{$tv[0]}',type='$type'");
			}
			else if(trim($row['name'])!=trim($tv[0])) //更新名称
			{
				$xltm->SQL->Execute("update `stock_code` set name='{$tv[0]}' where code='{$tv[1]}'");
			}
		}
		if($t_page>$c_page)
			$pn=$pn+1;
		else 
			$pn=1000000;
	}
	return $pn;
}
?>