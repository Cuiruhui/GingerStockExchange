<?php
//if ( function_exists("date_default_timezone_set"))date_default_timezone_set ("Etc/GMT+4");
//$dbhost                                = "127.0.0.1";                 // ݿ
date_default_timezone_set('PRC');

$dbhost                                = "localhost";                 // ݿ

$dbuser                                = "root";                 // ݿû
$dbpass                                = "along520!@";                         // ݿ
$dbname                                = "mynewgp";                 // ݿ
mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname);
mysql_query("SET NAMES 'utf8'"); 
mysql_query("SET CHARACTER_SET_CLIENT=utf8"); 
mysql_query("SET CHARACTER_SET_RESULTS=utf8"); 
$str="and|select|update|from|where|order|by|*|delete|'|insert|into|values|create|table|database";  //Ƿַ 
$arr=explode("|",$str);//Ƿַ䵥 

foreach ($_REQUEST as $key=>$value){
	for($i=0;$i<sizeof($arr);$i++){
		if (substr_count(strtolower($_REQUEST[$key]),$arr[$i])>0){       //鴫ǷǷַ 
		    /*echo "<script>window.open('/','_top')</script>";
            //exit; //˳ִкĴ*/
		}
	} 
}


$result = mysql_query("SELECT value FROM user_config where name='epay_returnurl'");
$row = mysql_fetch_array($result);
$returnurl = $row['value'];

$result = mysql_query("SELECT value FROM user_config where name='money_big'");
$row = mysql_fetch_array($result);
$money_big = $row['value'];


?>