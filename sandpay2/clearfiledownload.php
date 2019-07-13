<?php 
require('common.php');
date_default_timezone_set("Asia/Shanghai");

    $prikey = loadPk12Cert(PRI_KEY_PATH,CERT_PWD);
    $pubkey = loadX509Cert(PUB_KEY_PATH);   

    $data = array(
         'head' => array(
            'version' => '1.0',
            'method' => 'sandpay.trade.download',
            'productId' => '00000007',
            'accessType' => '1',
            'mid' => '19571651',
            'channelType' => '07',
            'reqTime' => date('YmdHis', time())
         ),
         'body' => array(
            'clearDate' => '结算日期',
            'fileType' => '文件返回类型',
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

    $result = http_post_json(API_HOST.'/clearfile/download',$post);

    parse_str(urldecode($result),$arr);
   
   print_r($arr['data']);