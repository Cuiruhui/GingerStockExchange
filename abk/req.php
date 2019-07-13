<?php

/*
 * @Description API支付产品通用支付接口范例 
 */
  require_once(dirname(dirname(__FILE__))."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
include 'payCommon.php';	
		
#	商家设置用户购买商品的支付信息.
##API支付平台统一使用GBK/GB2312编码方式,参数如用到中文，请注意转码

#	商户订单号,选填.
##若不为""，提交的订单号必须在自身账户交易中唯一;为""时，API支付会自动生成随机的商户订单号.
$p2_Order					= mt_rand(100000000,999999999)."-".$_POST["username"];

#	支付金额,必填.
##单位:元，精确到分.
$p3_Amt						= $_POST['money'];

#	交易币种,固定值"CNY".
$p4_Cur						= "CNY";

#	商品名称
##用于支付时显示在API支付网关左侧的订单产品信息.
$p5_Pid						= "paybak";

#	商品种类
$p6_Pcat					= "paybak";

#	商品描述
$p7_Pdesc					= "paybak";

#	商户接收支付成功数据的地址,支付成功后API支付会向该地址发送两次成功通知.
$p8_Url						="http://".$_SERVER["HTTP_HOST"]."/abk/callback.php";// $_REQUEST['p8_Url'];	


//在这里对订单进行入库保存

$currentusername = trim($_POST["username"]);
$payid=trim($_REQUEST["payid"]);
$urow_s = $xltm->SQL->GetRow("Select agent from `user_users` where username='{$extra_return_param}'");
$agent = $urow_s['agent'];
$date=date("Y-m-d");
$datetime=date("Y-m-d H:i:s");
$sql="INSERT INTO  payment set username='{$currentusername}', cmd='充值',mp='在线支付入款',agent='".$agent."',money='".$p3_Amt."',result='0',trxid='".$p2_Order."',return_time='".$datetime."',add_time='".$datetime."',orderno='".$p2_Order."',payid='".$payid."' ";	
$xltm->SQL->Execute($sql);





#	商户扩展信息
##商户可以任意填写1K 的字符串,支付成功时将原样返回.												
$pa_MP						=$_POST["username"];

#	支付通道编码
##默认为""，到API支付网关.若不需显示API支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.			
$pd_FrpId					=  $_POST['Bankco'];

#	应答机制
##默认为"1": 需要应答机制;
$pr_NeedResponse	= "1";

#调用签名函数生成签名串
$hmac = getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);
     
?> 
<html>
<head>
<title>To API Page</title>
</head>
<body onLoad="document.API.submit();">
<form name='API' action='<?php echo $reqURL_onLine; ?>' method='post'>
<input type='hidden' name='p0_Cmd'					value='<?php echo $p0_Cmd; ?>'>
<input type='hidden' name='p1_MerId'				value='<?php echo $p1_MerId; ?>'>
<input type='hidden' name='p2_Order'				value='<?php echo $p2_Order; ?>'>
<input type='hidden' name='p3_Amt'					value='<?php echo $p3_Amt; ?>'>
<input type='hidden' name='p4_Cur'					value='<?php echo $p4_Cur; ?>'>
<input type='hidden' name='p5_Pid'					value='<?php echo $p5_Pid; ?>'>
<input type='hidden' name='p6_Pcat'					value='<?php echo $p6_Pcat; ?>'>
<input type='hidden' name='p7_Pdesc'				value='<?php echo $p7_Pdesc; ?>'>
<input type='hidden' name='p8_Url'					value='<?php echo $p8_Url; ?>'>
<input type='hidden' name='p9_SAF'					value='<?php echo $p9_SAF; ?>'>
<input type='hidden' name='pa_MP'						value='<?php echo $pa_MP; ?>'>
<input type='hidden' name='pd_FrpId'				value='<?php echo $pd_FrpId; ?>'>
<input type='hidden' name='pr_NeedResponse'	value='<?php echo $pr_NeedResponse; ?>'>
<input type='hidden' name='hmac'						value='<?php echo $hmac; ?>'>
</form>
</body>
</html>