<?php
date_default_timezone_set ('Asia/Hong_Kong');
define('ROOT',dirname(__FILE__));
define('SYSTEM_INSTALLED', true);

error_reporting(E_ALL);
error_reporting(E_ALL || ~E_NOTICE);


$database_prefix = 'user_';
$database_type = 'MySQL';
$database_server_name = 'localhost';
$database_username = 'root';
$database_password = 'root';
$database_name = 'sql550527';
$database_port = 3306;
$activation_code="aassddffgghh";

$database_persistent = false;
$domain = $_SERVER['HTTP_HOST'];
$AgentID = "daili";
$session_prefix = 'se'.$AgentID.'_';



function mb($msg,$url,$type=0)
{
	$result = "<script type='text/javascript'>\r\n";
	$result .="alert('$msg');\r\n";
	if($type==1)
	{
		$result .="document.location.href='$url';\r\n";
	}
	else
	{
		$result .="history.go(-1);\r\n";
	}
	
	$result .="</script>\r\n";
	echo $result;
	exit();
}

function DateAdd($part, $number, $date)
{
$date_array = getdate(strtotime($date));
$hor = $date_array["hours"];
$min = $date_array["minutes"];
$sec = $date_array["seconds"];
$mon = $date_array["mon"];
$day = $date_array["mday"];
$yar = $date_array["year"];
switch($part)
{
case "y": $yar += $number; break;
case "q": $mon += ($number * 3); break;
case "m": $mon += $number; break;
case "w": $day += ($number * 7); break;
case "d": $day += $number; break;
case "h": $hor += $number; break;
case "n": $min += $number; break;
case "s": $sec += $number; break;
}
return date("Y-m-d H:i:s", mktime($hor, $min, $sec, $mon, $day, $yar));
}
?>
