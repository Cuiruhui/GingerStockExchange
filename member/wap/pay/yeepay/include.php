<?php
//if ( function_exists("date_default_timezone_set"))date_default_timezone_set ("Etc/GMT+4");
//$dbhost                                = "localhost";                 // ���ݿ�������
date_default_timezone_set('PRC');


$dbhost                                = "localhost";                 // ���ݿ�������

$dbuser                                = "root";                 // ���ݿ��û���
$dbpass                                = "aa123456789";                         // ���ݿ�����
$dbname                                = "mynewgp";                 // ���ݿ���

mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname);
mysql_query("SET NAMES 'utf8'"); 
mysql_query("SET CHARACTER_SET_CLIENT=utf8"); 
mysql_query("SET CHARACTER_SET_RESULTS=utf8"); 
$str="and|select|update|from|where|order|by|*|delete|'|insert|into|values|create|table|database";  //�Ƿ��ַ� 
$arr=explode("|",$str);//����Ƿ��ַ����䵥�� 

foreach ($_REQUEST as $key=>$value){
	for($i=0;$i<sizeof($arr);$i++){
		if (substr_count(strtolower($_REQUEST[$key]),$arr[$i])>0){       //���鴫�������Ƿ�����Ƿ��ַ� 
		    /*echo "<script>window.open('/','_top')</script>";
            //exit; //�˳�����ִ�к���Ĵ���*/
		}
	} 
}

?>