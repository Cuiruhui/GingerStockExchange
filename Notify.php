<?php
include "Config.php";
	date_default_timezone_set('PRC'); 
	header('Content-Type:text/html; charset=UTF-8' );
    
		$input = file_get_contents("php://input"); //接收POST数据

		// $input='context= "YW1vdW50PTEyJmFwcF9pZD0yMDE3MTExNTAwMDAwMDAzJnJlc3VsdD0xJnNpZ25hdHVyZT02RDExN0YwQ0IxN0Y0OUREJnRyYW5zX2lkPTIwMTcxMTI5MDEwMDAxMzg"';
		   $input='context="YW1vdW50PTEmYXBwX2lkPUdDMTM1MDk1NDg2JnJlc3VsdD0xJnNpZ25hdHVyZT0zRERCOEQxNzA3QUEyRTU1JnRyYW5zX2lkPTIwMTcxMjEzMDEwMDA2MTQ"';
		file_put_contents('notify.log', $input.PHP_EOL, FILE_APPEND);
	       
         parse_str($input,$dic); 
         
	    $res=new HttpUtils() ;
        $context = $res->UnPackUrlBase64($dic["context"]);           
		    $context = base64_decode($context);
        $res=null;
		 //amount=12&app_id=2017111500000003&result=1&signature=6D117F0CB17F49DD&trans_id=2017112901000138

	     file_put_contents('notify.log', $context.PHP_EOL, FILE_APPEND);
	     parse_str($context,$dic); 

		 $strSign=$dic["signature"];

         $Signstr = "{" . $dic["trans_id"] . "}|{" . $dic["result"] . "}|{" . $dic["amount"] . "}|{" . $key . "}";
		 //echo "回调  ".$Signstr;
         
		 $Sign = substr(strtoupper(md5($Signstr)),0,16);
         
        if ($strSign != $Sign)
        {
            echo ("签名失败!" . $Sign . " resp:" . $strSign);
            return;

        }
 
            echo("SUCCESS");
			echo "回调成功！ ".$context;
            return;


 

 
        
 
?>

