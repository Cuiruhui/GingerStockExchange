<?php 
require('common.php');
date_default_timezone_set("Asia/Shanghai");

if($_POST){
    $prikey = loadPk12Cert(PRI_KEY_PATH,CERT_PWD);
    $pubkey = loadX509Cert(PUB_KEY_PATH);   

    $data = array(
         'head' => array(
            'version' => '1.0',
            'method' => 'sandpay.trade.pay',
            'productId' => '00000007',
            'accessType' => '1',
            'mid' => $_POST['mid'],
            'channelType' => '07',
            'reqTime' => date('YmdHis', time())
         ),
         'body' => array(
            'orderCode' => $_POST['orderCode'],
            'totalAmount' => $_POST['totalAmount'],
            'subject' => $_POST['subject'],
            'body' => $_POST['body'],
            'txnTimeOut' => $_POST['txnTimeOut'],
            'payMode' => $_POST['payMode'],
            'payExtra' => json_encode(array('payType'=> $_POST['payType'],'bankCode' => $_POST['bankCode'])),
            'clientIp' => $_POST['clientIp'],
            'notifyUrl' => $_POST['notifyUrl'],
            'frontUrl' => $_POST['frontUrl'],
            'extend' => ''
            ) 
);
    $sign = sign($data,$prikey);

    $post = array(
        'charset' => 'utf-8',
        'signType' => '01',
        'data' => json_encode($data),
        'sign' => $sign
   );

    $result = http_post_json(API_HOST.'/order/pay',$post);

    parse_str(urldecode($result), $arr);

         $arr['data']=str_replace(array("  ","\t","\n","\r"),array('','','',''),$arr['data']);
        
         $data = json_decode($arr['data'],true);
     
        $credential = json_decode($data['body']['credential'],true);
        
        if(isset($credential['params']['orig']) && isset($credential['params']['sign']) ){

             $arr['data']= mb_array_chunk($data);
			 $arr['data'] = str_replace(array("\\\/","\\/","\/"),array("/","/","/"), $arr['data']); 
             
        }else {

            $data['body']['credential'] = json_encodes($credential);    
                    //使用第二参数JSON_UNESCAPED_UNICODE,阻止json_encode()转译汉字
            $arr['data'] = str_replace(array("\\\/","\\/","\/"," "),array("/","/","/","+"),json_encodes($data));
        }

         $arr['sign'] = preg_replace('/\s/', '+', $arr['sign']);
        //验签
        verify($arr['data'], $arr['sign'],$pubkey);

         $data = json_decode($arr['data'],320);
        
       if ($data['head']['respCode']  == "000000" ) {
            $credential = str_replace(array('"{','}"'),array('{','}'),stripslashes($data['body']['credential']));
        }else{
           print_r($arr['data']);
        }

    
}else{
    echo "不是POST传输  ";
}
  
    
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="renderer" content="webkit" />
    <title>Insert title here</title>
    <script type="text/javascript" src="scripts/paymentjs.js"></script>
    <script type="text/javascript" src="scripts/jquery-1.7.2.min.js"></script>
</head>
<body>
<script>
    function wap_pay() {
        var responseText = $("#credential").text();
        console.log(responseText);
        paymentjs.createPayment(responseText, function(result, err) {
            console.log(result);
            console.log(err.msg);
            console.log(err.extra);
        });
    }
</script>

<div style="display: none" >
    <p id="credential"><?php echo $credential; ?></p>
</div>
</body>

<script>
    window.onload=function(){
        wap_pay();
    };
</script>
</html>