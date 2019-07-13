<?php  
define('Load_Info', 1);
session_start();
include "TDESUtils.php";
include "HttpUtils.php";
include_once("./Lib/xltm.class.php");

date_default_timezone_set('PRC'); 
header('Content-Type:text/html; charset=gb2312');


$atmid=$_REQUEST['id'];
$money=$_REQUEST['money']*100;
$atmres=$xltm->SQL->GetRow("Select * from `atmlog` where id='{$atmid}'");

if($atmres['ispay']==0)
{
 
   	$Syscode ="20000059";
    $Version = "002";
	$Account = '9201000472';
    $PayCode   = "100008";
	$QueryCode = "100009";
    $OutCode= "110301";
	$key = "as7fipszpay23";//加密密钥
	$Key3DES="6BE219504286A1B2B1B27BA7B247C302";
	$Out_Pay ="http://www.yitianmao.com/cgi-bin/opay_single_im.cgi";
    $Out_Query="http://www.yitianmao.com/cgi-bin/opay_single_query_im.cgi";
    $NotifyIP="113.69.97.78";

	
	$kahao =trim($_REQUEST['bankno']);   //卡号
	$kahao2 =trim($_REQUEST['bankrealname']); //名字
	$kahao3 =trim($_REQUEST['yinhang']);    //银行名字
	$kahao4 =trim($_REQUEST['kaihusheng']);    //开户省
	$kahao5 =trim($_REQUEST['kaihushi']);    //开户市
	
	$kahao=iconv("UTF-8","GB2312",$kahao);
	$kahao2=iconv("UTF-8","GB2312",$kahao2);
	$kahao3=iconv("UTF-8","GB2312",$kahao3);
	$kahao4=iconv("UTF-8","GB2312",$kahao4);
	$kahao5=iconv("UTF-8","GB2312",$kahao5);
	
    $data = array(
	    'server_no' => $OutCode ,
  		'trans_time' =>  date("YmdHis"),// 
  		'account' => $Account,// 
		//'in_card_no' => "6217857000071701237",
		'in_card_no' => $kahao,
		'amount' => $money,// 
        'app_id' =>  date("YmdHis"),
		'ack_notify_url' => 'http://yujtkd.top/admin/OutNotify.php',
	);
	  
	$Signstr = "{" . $data["server_no"] . "}|{" . $data["trans_time"] . "}|{" .$data["account"] . "}|{" . $data["in_card_no"] . "}|{" . $data["amount"] . "}|{" . $data["app_id"] . "}|{" . $data["ack_notify_url"]. "}|{" . $key . "}";
	$Sign = substr(strtoupper(md5($Signstr)),0,16);
 	


	//$data["in_card_name"]= "黄果皮";
	$data["in_card_name"]= $kahao2;
//	$data["in_bank_name"]= "中国银行";
	$data["in_bank_name"]= $kahao3;
	$data["in_province_name"]= $kahao4;
	$data["in_city_name"]= $kahao5;
	//$data["in_bank_branch_name"]= "佛山支行";
	$data["opay_notify_url"]= "http://yujtkd.top/admin/Notify.php";
	$data["use"]= "装修";
	
    $Context = "server_no=" . $data["server_no"]. "&trans_time=" . $data["trans_time"] . "&account=" . $data["account"] . "&in_card_no=" . $data["in_card_no"] . "&in_card_name=" . $data["in_card_name"] . "&in_bank_name=" . $data["in_bank_name"] . "&in_province_name=" . $data["in_province_name"] ."&in_city_name=" . $data["in_city_name"] . "&amount=" . $data["amount"] . "&app_id=" . $data["app_id"]. "&opay_notify_url=" . $data["opay_notify_url"] . "&ack_notify_url=" . $data["ack_notify_url"] . "&use=" . $data["use"]  ;

    file_put_contents("OutPay.log", $Context.PHP_EOL, FILE_APPEND);
    $Tdes = new CryptDes();
  	$result = $Tdes->encrypt($Context,$Tdes->K16byteTo24str($Key3DES));//加密字符串
    $result =  $Tdes->PackUrlBase64($result);
    $Tdes=null;

	
	 
	$res=new HttpUtils() ;
 
    $strUrl=$res->Postdata($Syscode,$Version,$result,$Sign);
	
	

	list ( $curl_errno, $result )=$res->Post($Out_Pay,$strUrl);
    $res=null;

	if ( $curl_errno >0) { 
		echo 'no1 errorno: '.$curl_errno; 
		return ;
    } 
  
	$strgbk2utf = iconv("GB2312","UTF-8",$result);
	$dic=json_decode($strgbk2utf,true);
    
	if ($dic["errorcode"] != "0000")
	{
		echo ("\n no2--" .  $dic["errorcode"]  ."  ". $dic["errormessage"] .$result);
		return;
	}

	$strSign = $dic["signature"];
	$Tdes = new CryptDes();
	$context=$Tdes->UnPackUrlBase64($dic["context"]);
	$context = $Tdes->decrypt(($context), $Tdes->K16byteTo24str($Key3DES));//解密字符串
	$Tdes=null;

	//app_id=20171202190558&result=3&trans_id=2017120201000261
	parse_str($context,$dic); 
	 
	$Signstr = "{" . $dic["trans_id"] . "}|{" . $dic["app_id"] .  "}|{" . $key . "}";
	$Sign = substr(strtoupper(md5($Signstr)),0,16);

	

	if ($strSign != $Sign)
	{
		echo("no3! Get：" . $strSign." Sign: ".$Sign."<br/>");
		return;
	}  
	 
	echo "no4:". $dic["result"]."<br/>";
    
	file_put_contents('Outpay.log', $context.PHP_EOL, FILE_APPEND);
	if ( $dic["result"] !="3") { 
		echo 'no5 errorno: '.$context; 
		return ;
    } 
	if ( $dic["result"] =="3"){ 
		$xltm->SQL->Execute("update `atmlog` set ispay='1',pay_time='{$xltm->sys_time}' where id='{$atmid}'");
		$msg="该提款已经发起代付成功";
		$msg=iconv("GB2312","UTF-8",$msg);
		$result = "<script type='text/javascript'>\r\n";
		$result .="alert('$msg');\r\n";
		$result .="history.go(-1);\r\n";
		$result .="</script>\r\n";
		echo $result;
		return ;
	}
	exit();  


}else{
	$msg="该提款已经发起支付，请等待";
	$msg=iconv("GB2312","UTF-8",$msg);
	
		$result = "<script type='text/javascript'>\r\n";
		$result .="alert('$msg');\r\n";
		$result .="history.go(-1);\r\n";
		$result .="</script>\r\n";
		echo $result;
		exit();
}		  
       
?>
