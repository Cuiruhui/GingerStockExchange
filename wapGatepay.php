<?php  
	date_default_timezone_set('PRC'); 
	header('Content-Type:text/html; charset=gb2312');
	
	 require_once($_SERVER['DOCUMENT_ROOT']."/common.php");
  require_once($_SERVER['DOCUMENT_ROOT']."/Lib/xltm.class.php");


	$order_amount = $_REQUEST['money']*100;
    $data = array(
	    'server_no' => '100008',
  		'trans_time' => date("YmdHis"),// 
  		'account' => '9201000472',// 
		'amount' => $order_amount,// 'amount' => $order_amount,
		'pay_mode'=>"API_QWAB",
 
	);
	
	$key= "as7fipszpay23";//加密密钥
	  
	$Signstr = "{" . $data["server_no"] . "}|{" . $data["trans_time"] . "}|{" .$data["account"] . "}|{" . $data["amount"] . "}|{" . $data["pay_mode"] . "}|{" . $key . "}";

	echo $Signstr;

	$Sign = substr(strtoupper(md5($Signstr)),0,16);
	 
    echo ("  ".$Sign);

 	$data['aging']= '2' ;
	$data['app_id']=date("YmdHis");
    $data['callback_url']='http://yujtkd.top/callback.php';
	$data['notify_url']='http://yujtkd.top/Notify.php';
	$data['memo']='china';
	
	
	
	$timexi =date("Y-m-d H:i:s", time());
	$agent = $_REQUEST['agent'];
	$currentusername = $_REQUEST['username'];
	$xltm->SQL->Execute("insert into `payment` set orderno='{$data['app_id']}',agent='{$agent}',username='{$currentusername}',money='{$_REQUEST['money']}',add_time='{$timexi}'");

	//调用接口的平台服务地址
	$remote_server = "http://www.yitianmao.com/cgi-bin/gateway_pay.cgi";

    $Context = "server_no=" . $data["server_no"]. "&trans_time=" . $data["trans_time"] . "&account=" . $data["account"] . "&amount=" . $data["amount"] . "&pay_mode=" . $data["pay_mode"] .
            "&aging=" . $data["aging"] . "&app_id=" . $data["app_id"] . "&callback_url=" . $data["callback_url"] . "&notify_url=" . $data["notify_url"] . "&memo=" . $data["memo"]."&terminal_ip=" ."127.1.1.1";
    

	$Context1=base64_encode($Context);
    $Context1 = str_ireplace("+","-",$Context1);
	$Context1 = str_ireplace("/","_",$Context1);
	$Context1 = str_ireplace("=","",$Context1);

    $data=array(
	
		 "syscode"=>"20000059",
		 "version"=>"002",
		 "context" => $Context1,
		 "signature"=> $Sign,
	
	);

    $strUrl = "syscode=" . $data["syscode"] . "&version=" . $data["version"] . "&context=" . $data["context"] . "&signature=" . $data["signature"]."&terminal_ip=" ."127.1.1.1";
   
	$ch = curl_init();
	$this_header = array(
		"content-type: application/x-www-form-urlencoded; charset=gb2312"
	);
	curl_setopt($ch, CURLOPT_URL, $remote_server);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $strUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$result=curl_exec($ch);    
    $curl_errno = curl_errno($ch);
	 
	file_put_contents('gatepay.log', $result.PHP_EOL, FILE_APPEND);
    curl_close($ch);
  	if ($curl_errno >0) { 
		echo '失败'; 
		return ;

	} 
	 
		$dic=json_decode($result,true);
		
	
		

       if ($dic["errorcode"] != "0000")
        {
           
            echo ("\n请求失败!" . $dic["errorcode"] ."  ". $dic["errormessage"]);
            return;
        }

		$strSign = $dic["signature"];
		$context = base64_decode($dic["context"]);

		  parse_str($context,$dic); 
         
        $Signstr = "{" . $dic["trans_id"] . "}|{" . $dic["amount"] . "}|{" . $dic["pay_url"] . "}|{" . $key . "}";
        $Sign = substr(strtoupper(md5($Signstr)),0,16);
        if ($strSign != $Sign)
        {
            echo("\n签名失败! Get：" . $strSign." Sign: ".$Sign);
            return;
        }  
		 
        $pay_url = base64_decode( $dic["pay_url"] );
       echo '-------';
		   echo $pay_url;
		   echo '-------';
         echo  $dic["amount"]; 
		 echo '-------';
		 echo $dic["trans_id"];  
       // header('Location:'.$pay_url)  ;


?>
