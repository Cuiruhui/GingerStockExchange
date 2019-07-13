<?php
/* *
 * 功能：支付接口调试入口页面
 * 版本：1.0
 * 修改日期：2018-06-11
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */
ini_set('display_errors','off');
error_reporting(E_ALL);
header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Shanghai');
require_once dirname ( __FILE__ ).'/lib/PayUtils.php';
if($_POST['out_trade_no']){
    echo "<pre>";
    //$_POST['out_trade_no']='OF1531124025';
    $data['out_trade_no']=$_POST['out_trade_no'];
    $Pay=new PayUtils();
    $result=$Pay->payQuery($data);
    print_r($result);
    $temp=json_decode($result,true);
    print_r($temp);
    exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
	<title>支付接口</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
    * {
        padding:0;
        margin:0;
    }
    body{
        padding:30px 50px;
    }
    ul,ol{
        list-style:none;
    }
   .header{
        font-size:30px;
    }
    .main{
        margin-top:10px;
        font-size:14px;
        line-height:28px;
    }
    .main ul:after{
        content:".";
        clear:both;
        display:block;
        height:0;
        overflow:hidden;
        visibility:hidden;
    }
    .main li{
        float: left;
    }
    .main button{
        padding:2px 5px;
    }
</style>
</head>
<body>
<div class="header">
    订单查询接口
</div>
<div class="main">
    <form name="payment" action='' method=post>
        <ul>
            <li>商户订单号：</li>
            <li>
                <input id="out_trade_no" name="out_trade_no" value=""/>
            </li>
        </ul>
        <ul>
            <li></li>
            <li>
                <button  type="submit" style"text-align:center;">确 认</button>
            </li>
        </ul>
    </form>
</div>
</body>
</html>