<?php
define('Load_Info', false);
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$show="<ul>";
$code=$_GET['code'];
$code=explode('=',$code);
$codeSO=end($code);
//print $codeSO;
if(preg_match("/[0-9]/",substr($codeSO,0,1))<1)
{
	$stock=$xltm->SQL->Rows("SELECT * FROM `stock_code` where pinyin like '".$codeSO."%' ORDER BY `code` LIMIT 0,5");
	foreach ($stock as $k=>$v)
	{
		$show .="<li id='".$v['code']."'>".$v['code']."---".$v['name']."(".$v['pinyin'].")</li>";
	}
}else {
    $stock=$xltm->SQL->Rows("SELECT * FROM `stock_code` where code like '".$codeSO."%' ORDER BY `code` LIMIT 0,5");
	foreach ($stock as $k=>$v)
	{
		$show .="<li id='".$v['code']."'>".$v['code']."---".$v['name']."(".$v['pinyin'].")</li>";
	}
}
$show .="</ul>";
print $show;
?>