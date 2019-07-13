<?php
include "Config.php";
 
	date_default_timezone_set('PRC'); 
	header("ontent-Type:text/html; charset=UTF-8");

class HttpUtils {

   function  Post($Url,$strUrl)
    {	 
    $strUrl = mb_convert_encoding($strUrl, "GBK","UTF-8");  
	$ch = curl_init();
	$this_header = array(
		"content-type: application/x-www-form-urlencoded; charset=GBK");
	curl_setopt($ch, CURLOPT_URL, $Url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $strUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
 	$result=curl_exec($ch);   
    $result =  mb_convert_encoding($result,"UTF-8", "GBK"); 
   
	$curl_errno = curl_errno($ch);
    curl_close($ch);
	 
	return 	array($curl_errno,$result);			 
 
    }

   function  Postdata($Syscode,$Version,$result,$Sign)
    {	 
	$data=array(
	
		 "syscode"=>$Syscode,
		 "version"=>$Version,
		 "context" => $result,
		 "signature"=> $Sign,
	);

	$strUrl = "syscode=" . $data["syscode"] . "&version=" . $data["version"] . "&context=" . $data["context"] . "&signature=" . $data["signature"] ; 
	$strUrl = mb_convert_encoding($strUrl, "GBK","UTF-8");  
	return  $strUrl ;
 
    }   

    function PackUrlBase64($strdata) {
		$strdata = str_ireplace("+","-",$strdata);
		$strdata = str_ireplace("/","_",$strdata);
		$strdata = str_ireplace("=","",$strdata);

		return $strdata;
     }

	 function UnPackUrlBase64($strdata) {
		$strdata = str_ireplace("-","+",$strdata);
		$strdata = str_ireplace("_","/",$strdata);
	   while (strlen($strdata) % 4 != 0)
        {
             $strdata .= "=";
        }    	
  
		return $strdata;
     }
			
}

?>