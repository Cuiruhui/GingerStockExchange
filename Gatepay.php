<?php  
include "Config.php";
include "HttpUtils.php";
 
	date_default_timezone_set('PRC'); 
	header("ontent-Type:text/html; charset=UTF-8");

    $data = array(
	    'server_no' => $PayCode,
  		'trans_time' => date("YmdHis"),// 
  		'account' => $Account,// 
		'amount' => "2",// 
		'pay_mode'=>$API_QWAB ,//$API_GATEWAY,
 
	);
	
 	  
	$Signstr = "{" . $data["server_no"] . "}|{" . $data["trans_time"] . "}|{" .$data["account"] . "}|{" . $data["amount"] . "}|{" . $data["pay_mode"] . "}|{" . $key . "}";

	echo $Signstr;

	$Sign = substr(strtoupper(md5($Signstr)),0,16);
	 
    echo ("  ".$Sign);

 	$data['aging']= '2' ; //t0=1 t1=2
	$data['app_id']=date("YmdHis");
    $data['callback_url']='http://yujtkd.top/notify2.php';
	$data['notify_url']='http://yujtkd.top/callback.php';
	$data['memo']='china';
 

    $Context = "server_no=" . $data["server_no"]. "&trans_time=" . $data["trans_time"] . "&account=" . $data["account"] . "&amount=" . $data["amount"] . "&pay_mode=" . $data["pay_mode"] .
            "&aging=" . $data["aging"] . "&app_id=" . $data["app_id"] . "&callback_url=" . $data["callback_url"] . "&notify_url=" . $data["notify_url"] . "&memo=" . $data["memo"];
 
    $Context=mb_convert_encoding($Context, "GBK","UTF-8");

	$Context=base64_encode($Context);

	$res=new HttpUtils() ;
    $Context = $res->PackUrlBase64( $Context);
    $strUrl=$res->Postdata($Syscode,$Version,$Context,$Sign);
	
	echo $strUrl."<br/>";

	list ( $curl_errno, $result )=$res->Post($Pay_server,$strUrl);
    $res=null;

	if ( $curl_errno >0) { 
		echo '通讯失败 errorno: '.$curl_errno; 
		return ;
    } 
  
     	$dic=json_decode($result,true);
         
      if ($dic["errorcode"] != "0000")
        {
           
            echo ("\n请求失败!" . $dic["errorcode"] ."  ". $dic["errormessage"] );
            return;
        }

		$strSign = $dic["signature"];
	    $res=new HttpUtils() ;
        $context = $res->UnPackUrlBase64($dic["context"]);           
		$context = base64_decode($context);
        $res=null;
        echo $context;
		parse_str($context,$dic); 
        
        $Signstr = "{" . $dic["trans_id"] . "}|{" . $dic["amount"] . "}|{" . $dic["pay_url"] . "}|{" . $key . "}";
        $Sign = substr(strtoupper(md5($Signstr)),0,16);
        if ($strSign != $Sign)
        {
            echo("\n签名失败! Get：" . $strSign." Sign: ".$Sign);
            return;
        }  
		 $res=new HttpUtils() ;
         $pay_url =   $res->UnPackUrlBase64( $dic["pay_url"] );
         $pay_url =   base64_decode( $pay_url ) ;
         $res=null;        
        // header('Location:'. base64_decode( $pay_url ))  ;

		 header('Location:' .$pay_url);

?>
