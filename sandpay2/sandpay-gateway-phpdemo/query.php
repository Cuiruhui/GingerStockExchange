<?php 
require('common.php');
date_default_timezone_set("Asia/Shanghai");

    $prikey = loadPk12Cert(PRI_KEY_PATH,CERT_PWD);
    $pubkey = loadX509Cert(PUB_KEY_PATH);   

    $data = array(
         'head' => array(
            'version' => '1.0',
            'method' => 'sandpay.trade.query',
            'productId' => '00000007',
            'accessType' => '1',
            'mid' => '10930313',
            'channelType' => '07',
            'reqTime' => date('YmdHis', time())
         ),
         'body' => array(
            'orderCode' => '订单号',
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

    $result = http_post_json(API_HOST.'/order/query',$post);
    parse_str(urldecode($result),$arr);
   
     print_r($arr);