<?
define('Load_Info', false);
//sleep(2);
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
$t=$_GET['t'];
$hy_id=$_GET['hy_id'];
if($_GET['page']>1)
$page=$_GET['page'];
else
$page=1;
if($page>0)
$lpage=($page-1)*10;
else
$lpage=$page*10;
switch ($t)
{
	case '0':
		$code_id=$_GET['code_id'];
		if($code_id){
			$where=" where code='{$code_id}'";
		}else {
			$where="";
		}
		$stock_count=$xltm->SQL->GetRow("SELECT count(*) as totla FROM `stock_code` $where");
		$amount=$stock_count['totla'];
		$page_size=10;
		if( $amount ){
			if( $amount < $page_size ){ $page_count = 1; }
			if( $amount % $page_size ){
				$page_count = (int)($amount / $page_size) + 1;
			}else{
				$page_count = $amount / $page_size;
			}
		}
		else{
			$page_count = 0;
		}
		$stock=$xltm->SQL->Rows("SELECT * FROM `stock_code` $where ORDER BY `code` LIMIT $lpage,10");
		$print_stock="0,".$page_count.",".$page.",".$hy_id."|";
		foreach ($stock as $k=>$v)
		{
			$print_stock .=$v['type'].$v['code'].",";
		}
		$print_stock=substr($print_stock,0,-1);
		echo $print_stock;
		break;
	case '1':
		$stock_count=$xltm->SQL->GetRow("SELECT count(*) as totla FROM `stock_code` WHERE `type` = 'sz'");
		$amount=$stock_count['totla'];
		$page_size=10;
		if( $amount ){
			if( $amount < $page_size ){ $page_count = 1; }
			if( $amount % $page_size ){
				$page_count = (int)($amount / $page_size) + 1;
			}else{
				$page_count = $amount / $page_size;
			}
		}
		else{
			$page_count = 0;
		}
		$stock=$xltm->SQL->Rows("SELECT * FROM `stock_code` WHERE `type` = 'sz' ORDER BY `code` LIMIT $lpage,10");
		$print_stock="1,".$page_count.",".$page.",".$hy_id."|";
		foreach ($stock as $k=>$v)
		{
			$print_stock .=$v['type'].$v['code'].",";
		}
		$print_stock=substr($print_stock,0,-1);
		echo $print_stock;
		break;
	case '2':
		$stock_count=$xltm->SQL->GetRow("SELECT count(*) as totla FROM `stock_code` WHERE `type` = 'sh'");
		$amount=$stock_count['totla'];
		$page_size=10;
		if( $amount ){
			if( $amount < $page_size ){ $page_count = 1; }
			if( $amount % $page_size ){
				$page_count = (int)($amount / $page_size) + 1;
			}else{
				$page_count = $amount / $page_size;
			}
		}
		else{
			$page_count = 0;
		}
		$stock=$xltm->SQL->Rows("SELECT * FROM `stock_code` WHERE `type` = 'sh' ORDER BY `code` LIMIT $lpage,10");
		$print_stock="2,".$page_count.",".$page.",".$hy_id."|";
		foreach ($stock as $k=>$v)
		{
			$print_stock .=$v['type'].$v['code'].",";
		}
		$print_stock=substr($print_stock,0,-1);
		echo $print_stock;
		break;
	case '3':
		$hy_id=$_GET['hy_id'];
		if($hy_id){
			$where=" where hy='{$hy_id}'";
		}else {
			$where="";
		}
		$stock_count=$xltm->SQL->GetRow("SELECT count(*) as totla FROM `stock_code`$where");
		$amount=$stock_count['totla'];
		$page_size=10;
		if( $amount ){
			if( $amount < $page_size ){ $page_count = 1; }
			if( $amount % $page_size ){
				$page_count = (int)($amount / $page_size) + 1;
			}else{
				$page_count = $amount / $page_size;
			}
		}
		else{
			$page_count = 0;
		}
		$stock=$xltm->SQL->Rows("SELECT * FROM `stock_code`$where ORDER BY `code` LIMIT $lpage,10");
		$print_stock="3,".$page_count.",".$page.",".$hy_id."|";
		foreach ($stock as $k=>$v)
		{
			$print_stock .=$v['type'].$v['code'].",";
		}
		$print_stock=substr($print_stock,0,-1);
		echo $print_stock;
		break;
	default:
		$stock_count=$xltm->SQL->GetRow("SELECT count(*) as totla FROM `stock_code`");
		$amount=$stock_count['totla'];
		$page_size=10;
		if( $amount ){
			if( $amount < $page_size ){ $page_count = 1; }
			if( $amount % $page_size ){
				$page_count = (int)($amount / $page_size) + 1;
			}else{
				$page_count = $amount / $page_size;
			}
		}
		else{
			$page_count = 0;
		}
		$stock=$xltm->SQL->Rows("SELECT * FROM `stock_code` ORDER BY `code` LIMIT $lpage,10");
		$print_stock="3,".$page_count.",".$page.",".$hy_id."|";
		foreach ($stock as $k=>$v)
		{
			$print_stock .=$v['type'].$v['code'].",";
		}
		$print_stock=substr($print_stock,0,-1);
		echo $print_stock;
		break;
}
//echo '<pre>';
//print_r($xltm);
?>