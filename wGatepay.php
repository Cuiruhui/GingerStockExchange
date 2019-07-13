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
		'pay_mode'=>"API_GATEWAY",
 
	);
	
	$key= "as7fipszpay23";//加密密钥
	  
	$Signstr = "{" . $data["server_no"] . "}|{" . $data["trans_time"] . "}|{" .$data["account"] . "}|{" . $data["amount"] . "}|{" . $data["pay_mode"] . "}|{" . $key . "}";

	

	$Sign = substr(strtoupper(md5($Signstr)),0,16);
	 
    

 	$data['aging']= '2' ;
	$data['app_id']=date("YmdHis");
     $data['callback_url']='http://yujtkd.top/notify2.php';
	$data['notify_url']='http://yujtkd.top/callback.php';
	$data['memo']='china';
	
	
	
	$timexi =date("Y-m-d H:i:s", time());
	
	$currentusername = $_REQUEST['username']? $_REQUEST['username']:'';

	if(empty($currentusername))
	{
		$msg="请输入正确的账户";
		$msg=iconv("GB2312","UTF-8",$msg);
		$result = "<script type='text/javascript'>\r\n";
		$result .="alert('$msg');\r\n";
		$result .="history.go(-1);\r\n";
		$result .="</script>\r\n";
		echo $result;
		exit();
		
	}
	$row = $xltm->SQL->GetRow("select * from `user_users` where username='{$currentusername}'");
	if($row=='')
	{
		$msg="请输入正确的账户";
		$msg=iconv("GB2312","UTF-8",$msg);
		$result = "<script type='text/javascript'>\r\n";
		$result .="alert('$msg');\r\n";
		$result .="history.go(-1);\r\n";
		$result .="</script>\r\n";
		echo $result;
		exit();
	}
	$agent = $xltm->SQL->GetRow("select agent from `user_users` where username='{$currentusername}'");
	
	
	$xltm->SQL->Execute("insert into `payment` set orderno='{$data['app_id']}',agent='{$row['agent']}',username='{$currentusername}',money='{$_REQUEST['money']}',add_time='{$timexi}'");

	//调用接口的平台服务地址
	$remote_server = "http://www.yitianmao.com/cgi-bin/gateway_pay.cgi";

    $Context = "server_no=" . $data["server_no"]. "&trans_time=" . $data["trans_time"] . "&account=" . $data["account"] . "&amount=" . $data["amount"] . "&pay_mode=" . $data["pay_mode"] .
            "&aging=" . $data["aging"] . "&app_id=" . $data["app_id"] . "&callback_url=" . $data["callback_url"] . "&notify_url=" . $data["notify_url"] . "&memo=" . $data["memo"];
    

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

    $strUrl = "syscode=" . $data["syscode"] . "&version=" . $data["version"] . "&context=" . $data["context"] . "&signature=" . $data["signature"] ;
   
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
         echo $pay_url;
              
        header('Location:'.$pay_url)  ;


?>
