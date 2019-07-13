<?php
//if ( function_exists("date_default_timezone_set"))date_default_timezone_set ("Etc/GMT+4");
//$dbhost                                = "localhost";                 // 数据库主机名
date_default_timezone_set('PRC');


$dbhost                                = "localhost";                 // 数据库主机名

$dbuser                                = "root";                 // 数据库用户名
$dbpass                                = "aa123456789";                         // 数据库密码
$dbname                                = "mynewgp";                 // 数据库名

mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname);
mysql_query("SET NAMES 'utf8'"); 
mysql_query("SET CHARACTER_SET_CLIENT=utf8"); 
mysql_query("SET CHARACTER_SET_RESULTS=utf8"); 
$str="and|select|update|from|where|order|by|*|delete|'|insert|into|values|create|table|database";  //非法字符 
$arr=explode("|",$str);//数组非法字符，变单个 

foreach ($_REQUEST as $key=>$value){
	for($i=0;$i<sizeof($arr);$i++){
		if (substr_count(strtolower($_REQUEST[$key]),$arr[$i])>0){       //检验传递数据是否包含非法字符 
		    /*echo "<script>window.open('/','_top')</script>";
            //exit; //退出不再执行后面的代码*/
		}
	} 
}

?>