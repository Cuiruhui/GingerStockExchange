<?php
class HttpUtils {

   function  Post($Url,$strUrl)
    {	 
	$ch = curl_init();
	$this_header = array(
		"content-type: application/x-www-form-urlencoded; charset=gb2312"
	);
	curl_setopt($ch, CURLOPT_URL, $Url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $strUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
 	$result=curl_exec($ch);   

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

	return  $strUrl ;
 
    }              
}

?>