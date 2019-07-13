<?php
session_start();
define('Load_Info', true);
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$type=$_REQUEST['type'];
if($xltm->startTIME()==0)
//$xltm->ShowPrompt('休市中,不能买卖股票!!');
exit("flase|休市中,不能买卖股票!!");
switch ($type)
{
	case 'dc':
		$row=$xltm->SQL->GetRow("select dc from `stock_code` where code='{$_GET['code']}'");
		echo $row['dc'];
		break;
	case 'buytime':
		$canSellTime=date('Y-m-d H:i:s',time()-($xltm->config['sel_max_time']*60));
		$row=$xltm->SQL->GetRow("Select bull_trust_time from `buy_deal` where id='{$_GET['id']}'");
		if(is_array($row))
		{
			if($row['bull_trust_time']>$canSellTime)
			{
				echo 'false';
			}
			else
			{
				echo 'true';
			}
		}
		else
		{
			echo 'false';
		}
		break;
	case 'bull':
		$xltm->buy->bull_stock();
		break;
	case 'sale':
		$xltm->buy->sale_stock();
		break;	
}
//print print_r($_POST);
/*
[0] => 名称
[1] => 今日开
[2] => 昨日收
[3] => 7.53
[4] => 7.61
[5] => 7.45
[6] => 7.52
[7] => 7.53
[8] => 1635117 成交量
[9] => 12286480.18 成交额
[10] => 5200 买1量
[11] => 7.52 买1价
[12] => 2600
[13] => 7.51
[14] => 16802
[15] => 7.50
[16] => 300
[17] => 7.49
[18] => 2400
[19] => 7.48
[20] => 33750 卖1价
[21] => 卖1量
[22] => 53800
[23] => 7.54
[24] => 35711
[25] => 7.55
[26] => 10100
[27] => 7.56
[28] => 4800
[29] => 7.57
[30] => 2009-06-18 日期
[31] => 15:05:46   最后更新时间
*/
?>
