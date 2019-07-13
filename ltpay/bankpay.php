<?php
  header("Content-type: text/html; charset=utf-8"); 
	require_once($_SERVER['DOCUMENT_ROOT']."/common.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Lib/xltm.class.php");


function makeCurl($url,$params=false,$ispost=0){
    global $token;
    $httpInfo = array();
    $ch = curl_init();
    $headers = array();
    array_push($headers, "token: " . $token);
    if (1 == strpos("$".$url, "https://"))
    {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}



 //版本
    $version="1.1";

    //支付网关地址
  	$gateway_url = "https://pay.heng2468.com/api.php/webRequest/tradePay";

    //查询网关地址
    $query_url = "https://pay.heng2468.com/api.php/webRequest/orderQuery";

    //异步回调
     $notify_url="http://www.wanmeicelue.com/ltpay/notify.php";

    //同步返回
     $return_url="http://www.wanmeicelue.com/ltpay/return.php";

	//编码格式
	 $charset = "UTF-8";

	//签名方式 
     $sign_type = "MD5";

    //商户id
     $appid="80062708";
    //商户密匙
     $appsecret="5c45dffb3f1b5d7a78706f7f791ebfd9";
	 
	 
	 $out_trade_no=date('YmdHis',time()).rand(100,200);
	 $total_fee=sprintf("%.2f", $_REQUEST["money"]);
	 $pay_id="QWJ_QUICK";
	 $bank_code="102";

$orderid=$out_trade_no;
$agent=$_REQUEST["agent"];
$currentusername=$_REQUEST["username"];
$moneya=$total_fee;
$timexi=date("Y-m-d H:i:s", time());
$xltm->SQL->Execute("insert into `payment` set orderno='{$orderid}',agent='{$agent}',username='{$currentusername}',money='{$moneya}',add_time='{$timexi}'");

$sign=md5('appid='.$appid.'&bank_code='.$bank_code.'&notify_url='.$notify_url.'&out_trade_no='.$out_trade_no.'&pay_id='.$pay_id.'&return_url='.$return_url.'&total_fee='.$total_fee.'&version='.$version.$appsecret);


 $param = array();
        $param['version'] =$version;
        $param['appid'] = $appid;
        $param['out_trade_no'] = $out_trade_no;
        $param['total_fee'] = $total_fee;
        $param['pay_id'] = $pay_id;
        $param['bank_code'] = $bank_code;
        $param['return_url'] = $return_url;
        $param['notify_url'] = $notify_url;
      
		 $param['sign'] = $sign;

ksort($param);


  $paramstring = http_build_query($param);
    $content = makeCurl($gateway_url,$paramstring,1);
    $result = json_decode($content,true);
$urlss = $result['data']['payment']; 
	//var_dump($result);
	header("Location:$urlss"); 
//echo "<script language='javascript' type='text/javascript'>"; 
//echo "window.location.href='$urlss'"; 
//echo "</script>"; 


?>