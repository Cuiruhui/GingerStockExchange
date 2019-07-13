<?php  
include "Config.php";
include "HttpUtils.php";
 
	date_default_timezone_set('PRC'); 
	header("ontent-Type:text/html; charset=UTF-8");

  $dic["context"]="c2VydmVyX25vPTEwMDAwOCZ0cmFuc190aW1lPTIwMTcxMjEyMTQwNTQzJmFjY291bnQ9OTEwMDAw
MDAyMCZhbW91bnQ9MiZwYXlfbW9kZT1BUElfUVdBQiZhZ2luZz0yJmFwcF9pZD0yMDE3MTExMzEy
MzQmY2FsbGJhY2tfdXJsPWh0dHAlM0ElMkYlMkZ3d3cuaGVsbG8uY29tbSUyRnN1Y2Nlc3MuaHRt
bCZub3RpZnlfdXJsPWh0dHAlM0ElMkYlMkZ3d3cuaGVsbG8uY29tJTJGbm9yaWZ5LmNnaSZtZW1v
PWNoaW5h";
  	
	 $cont="c2VydmVyX25vPTEwMDAwOCZ0cmFuc190aW1lPTIwMTcxMjEyMTQwNTU3JmFjY291bnQ9OTEwMDAwMDAyMCZhbW91bnQ9MiZwYXlfbW9kZT1BUElfUVdBQiZhZ2luZz0yJmFwcF9pZD0yMDE3MTExMzEyMzQmY2FsbGJhY2tfdXJsPWh0dHAlM0ElMkYlMkZ3d3cuaGVsbG8uY29tbSUyRnN1Y2Nlc3MuaHRtbCZub3RpZnlfdXJsPWh0dHAlM0ElMkYlMkZ3d3cuaGVsbG8uY29tJTJGbm9yaWZ5LmNnaSZtZW1vPWNoaW5h
";

	    $res=new HttpUtils() ;
        $context = $res->UnPackUrlBase64($dic["context"]);           
		$context = base64_decode($context);
	    $cont = $res->UnPackUrlBase64($cont);           
		$cont = base64_decode($cont);
        $res=null;

        echo  $context."<br/>";
		echo  $cont ;
 

?>
