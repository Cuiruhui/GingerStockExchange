<?php

include "TDESUtils.php";
include "HttpUtils.php";
date_default_timezone_set("PRC"); 
header("Content-type: text/html; charset=gb2312");
 
		$Syscode ="20000059";
		$Version = "002";
		$Account = "9201000472";
		$PayCode   = "100008";
		$QueryCode = "100009";
		$OutCode= "110301";
		$key = "as7fipszpay23";//加密密钥
		$Key3DES="6BE219504286A1B2B1B27BA7B247C302";
		$Out_Pay ="http://www.yitianmao.com/cgi-bin/opay_single_im.cgi";
		$Out_Query="http://www.yitianmao.com/cgi-bin/opay_single_query_im.cgi";
 
		$context = file_get_contents("php://input"); //接收POST数据
	
		file_put_contents("OutNotify.log", "1 Outcall: ".$context.PHP_EOL, FILE_APPEND);

	   //  $context="syscode=20000020&version=002&context=375Ax-IIccKkxGoCce4utEGOSuoGXNgFYFngfGZzkGsb48RGsN0M90efFo0PbJi5LoA_0kNoUfp-dUQ3W3lEAwPoH5lDnZIrRrU87bwJ3_MmYij6FMpxs4X3LdOrRPk_L69qDlCn_vVrmWTKXkvw2g&signature=4CABB2A0139270B9";  

        parse_str($context,$dic); 

	    $strSign=$dic["signature"];
		$context=$dic["context"];

		$Tdes = new CryptDes();
		$context=$Tdes->UnPackUrlBase64($context);
		$context = $Tdes->decrypt(($context), $Tdes->K16byteTo24str($Key3DES));//解密字符串
		$Tdes=null;
		file_put_contents("OutNotify.log", "2 Callcontext: ".$context.PHP_EOL, FILE_APPEND);
        //amount=10&app_id=20171202164709&in_card_no=6217857000071701237&random=20171202164830&trans_id=2017120201000256
		
         parse_str($context,$dic); 
  
         $Signstr = "{" . $dic["trans_id"] . "}|{" . $dic["app_id"] . "}|{" . $dic["in_card_no"] . "}|{" . $dic["amount"] . "}|{" . $dic["random"] . "}|{" . $key . "}";
         
		 $Sign = substr(strtoupper(md5($Signstr)),0,16);
         file_put_contents("OutNotify.log", "3 Sign: ".$Signstr." ".$Sign."  ".$strSign.PHP_EOL, FILE_APPEND);
        if ($strSign != $Sign)
        {
             echo ("签名失败!" . $Sign . " resp:" . $strSign);
			file_put_contents("OutNotify.log", "4 Outcall 签名失败!" . $Sign . " resp:" . $strSign.PHP_EOL, FILE_APPEND);
            return;
        }
  
  //检验数据ok，则响应数据服务器

		$data = array(
	 
			"trans_id" =>  $dic["trans_id"],// 
			"app_id" =>  $dic["app_id"],
			"in_card_no" => $dic["in_card_no"],
			"amount" =>$dic["amount"] ,// 
			"random" => $dic["random"],
			'return_code' => "0000",
			'return_desc' => "success",
	 
		);
		  
		$Signstr = "{". $data["trans_id"] . "}|{". $data["app_id"] . "}|{". $data["in_card_no"] . "}|{". $data["amount"]  . "}|{". $data["return_code"] . "}|{" . $data["random"]. "}|{". $key . "}";
 
		$Sign = substr(strtoupper(md5($Signstr)),0,16);

        file_put_contents("OutNotify.log", "5 Return data: ".$Signstr." ".$Sign .PHP_EOL, FILE_APPEND);	
		
		$Context = "trans_id=" . $data["trans_id"]. "&app_id=" . $data["app_id"]   . "&in_card_no=" . $data["in_card_no"]  . "&amount=" . $data["amount"] . "&random=" . $data["random"] . "&return_code=" . $data["return_code"] . "&return_desc=" . $data["return_desc"]  ;

        file_put_contents("OutNotify.log", "6 Return: ".$Context.PHP_EOL, FILE_APPEND);	  
		$Tdes = new CryptDes();
		$result = $Tdes->encrypt($Context,$Tdes->K16byteTo24str($Key3DES));//加密字符串
		$result =  $Tdes->PackUrlBase64($result);
		$Tdes=null;
 
	    $strUrl = '{"syscode":"' . $Syscode . '","version":"' . $Version . '","context":"' . $result . '","signature":"' . $Sign.'"}' ; 	
		echo $strUrl;
 
        file_put_contents("OutNotify.log", "7 Return: ".$strUrl.PHP_EOL, FILE_APPEND);

	    return;
	 
?>

