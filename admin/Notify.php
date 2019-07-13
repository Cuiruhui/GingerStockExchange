<?php

include "TDESUtils.php";
include "HttpUtils.php";
date_default_timezone_set('PRC'); 
header("Content-type: text/html; charset=gb2312");
 
		$Syscode ="20000059";
		$Version = "002";
		$Account = '9201000472';
		$PayCode   = "100008";
		$QueryCode = "100009";
		$OutCode= "110301";
		$key = "as7fipszpay23";//¼ÓÃÜÃÜÔ¿
		$Key3DES="6BE219504286A1B2B1B27BA7B247C302";
		$Out_Pay ="http://www.yitianmao.com/cgi-bin/opay_single_im.cgi";
		$Out_Query="http://www.yitianmao.com/cgi-bin/opay_single_query_im.cgi";
 
		$context = file_get_contents("php://input"); //½ÓÊÕPOSTÊý¾Ý

		file_put_contents('Notify.log', "Calldata: ".$context.PHP_EOL, FILE_APPEND);
	   
		//$context="syscode=20000020&version=002&context=375Ax-IIccKkxGoCce4utEGOSuoGXNgFYFngfGZzkGsb48RGsN0M90efFo0PbJi5LoA_0kNoUfp-dUQ3W3lEAwPoH5lDnZIrRrU87bwJ3_MmYij6FMpxs4X3LdOrRPk_L69qDlCn_vVrmWTKXkvw2g&signature=4CABB2A0139270B9";  

        parse_str($context,$dic); 

	    $strSign=$dic["signature"];
		$context=$dic["context"];	     
		
		$Tdes = new CryptDes();
		$context=$Tdes->UnPackUrlBase64($context);
		$context = $Tdes->decrypt(($context), $Tdes->K16byteTo24str($Key3DES));//½âÃÜ×Ö·û´®
		$Tdes=null;
		file_put_contents('Notify.log', "Call context: ".$context.PHP_EOL, FILE_APPEND);

        parse_str($context,$dic); 
  
		// $dic["result"]="1";

         $Signstr = "{" . $dic["trans_id"] . "}|{" . $dic["app_id"] . "}|{" . $dic["result"] . "}|{" . $dic["amount"] . "}|{" . $key . "}";
  		 $Sign = substr(strtoupper(md5($Signstr)),0,16);
			file_put_contents('Notify.log', "Call Sign: ".$Signstr." ".$Sign.PHP_EOL, FILE_APPEND);         
        if ($strSign != $Sign)
        {
            echo "Ç©ÃûÊ§°Ü ".$Signstr." ".$Sign;
			file_put_contents('Notify.log', "NotifyÇ©ÃûÊ§°Ü! ".$Signstr." ".$Sign.PHP_EOL, FILE_APPEND);
            return;

        }
            if($dic["result"]=="1")
			{
			
				$data = array(

				"trans_id" =>  $dic["trans_id"],// 
				"app_id" =>  $dic["app_id"],
				'return_code' => "0000",

				);

				$Signstr = "{". $data["trans_id"] . "}|{". $data["app_id"] . "}|{" . $data["return_code"] . "}|{". $key . "}";
				$Sign = substr(strtoupper(md5($Signstr)),0,16);

				$Context = "trans_id=" . $data["trans_id"]. "&app_id=" . $data["app_id"]  . "&return_code=" . $data["return_code"]  ;

				file_put_contents("Notify.log", "ReturnContext: ".$Context.PHP_EOL, FILE_APPEND);	  
				$Tdes = new CryptDes();
				$result = $Tdes->encrypt($Context,$Tdes->K16byteTo24str($Key3DES));//¼ÓÃÜ×Ö·û´®
				$result =  $Tdes->PackUrlBase64($result);
				$Tdes=null;

				$strUrl = '{"syscode":"' . $Syscode . '","version":"' . $Version . '","context":"' . $result . '","signature":"' . $Sign.'"}' ; 	
				echo $strUrl;

				file_put_contents("Notify.log", "ReturnOk: ".$strUrl.PHP_EOL, FILE_APPEND);

				return;
            }
			else {
				file_put_contents("Notify.log", "ReturnErr: ".$context.PHP_EOL, FILE_APPEND);
			  	return;
			};
  
?>

