<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
@set_time_limit(0);
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");


$REQUEST = array_merge($_POST, $_GET);
$type=isset($REQUEST['type']) ? $REQUEST['type'] : '';

switch($type)
{
	case 'get_order':
		$username	= $REQUEST['username'];
		$agent		= $REQUEST['agent'];
		$ddo		= $REQUEST['ddo'];
		
		$tsql = "";
		if ( $ddo == "cut"){
			$tsql = "and dc_money=0";		//扣款时判断点差金额是否为0.
		}
		$arr = array();
		$query = "SELECT id,stock_name FROM buy_deal where user='{$username}' and agent='{$agent}' {$tsql} and sell=0";
		$arr["orders"] = $xltm->SQL->GetRows($query);

		echo json_encode($arr);
		break;
	
}

?>