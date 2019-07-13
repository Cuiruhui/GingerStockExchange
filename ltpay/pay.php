<?php
/* *
 * 功能：支付接口调试入口页面
 * 版本：1.0
 * 修改日期：2018-06-11
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */
ini_set('display_errors', 'on');
error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once dirname(__FILE__) . '/lib/PayUtils.php';
require_once dirname(__FILE__) . '/lib/phpqrcode/phpqrcode.php';
if (!empty($_POST['out_trade_no'])) {
    $pay_data = array();
    $pay_data['out_trade_no'] = date('YmdHis',time()).rand(100,200);//订单号
   // $pay_data['total_fee'] = $_POST['total_fee'];//订单金额
	 $pay_data['total_fee'] = '101.00';//订单金额
    $pay_data['pay_id'] = 'QWJ_QUICK';//支付方式
    $pay_data['bank_code'] = $_POST['bank_code'];//银行编码
    $Pay = new PayUtils();
    $result = $Pay->paySubmit($pay_data);
    if ($result['data']['resp_code'] != '00') {
        echo $result['data']['resp_desc'];
        exit;
    }
    if ($pay_data['pay_id'] == 'QWJ_ALIH5' || $pay_data['pay_id'] == 'QWJ_ALIPC') {
        var_dump($result['data']);
    } else{
        //header("location:$result['data']['payment']");
		$url = $result['data']['payment'];  
		echo "<script language='javascript' type='text/javascript'>";  
		echo "window.location.href='$url'";  
		echo "</script>";  
		
		//print($url);
		//exit;
    }
} 
?>

