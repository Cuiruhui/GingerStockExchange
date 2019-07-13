<?php 
     

			
			$apiurl = "http://pay.maisos.com/PayBank.aspx";
            $partner = $_POST[txtpartner];
            $key = $_POST[txtKey];
            $ordernumber =$_POST[txtordernumber];
            $banktype =$_POST[txtbanktype];
            $attach = $_POST[txtattach];
            $paymoney =$_POST[txtpaymoney];
            $callbackurl = $_POST[txtcallbackurl];
            $hrefbackurl = $_POST[txthrefbackurl];
            $signSource = sprintf("partner=%s&banktype=%s&paymoney=%s&ordernumber=%s&callbackurl=%s%s", $partner, $banktype, $paymoney, $ordernumber, $callbackurl, $key);
            $sign = md5($signSource);
            $postUrl = $apiurl. "?banktype=".$banktype;
			$postUrl.="&partner=".$partner;
            $postUrl.="&paymoney=".$paymoney;
            $postUrl.="&ordernumber=".$ordernumber;
            $postUrl.="&callbackurl=".$callbackurl;
            $postUrl.="&hrefbackurl=".$hrefbackurl;
            $postUrl.="&attach=".$attach;
            $postUrl.="&sign=".$sign;
			header ("location:$postUrl");
		
?>