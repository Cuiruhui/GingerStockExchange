<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>杉德支付</title>
	<link rel="stylesheet" type="text/css" href="css/sand.css">
</head>
<body>
<div>
    <div id="main">
        <div id="head">
            <dl class="sandpay_link">
                <a target="_blank" href="https://www.sandpay.com.cn/"><span>杉德首页</span></a>
            </dl>
            <span class="title">杉德 网关支付2.0 统一下单接口</span>
        </div>
        <div class="cashier-nav"></div>
        <div class="center">
	        <form action="orderPay.php" method="post" name="sandpay">
	            <div id="body" style="clear:left">
	                <dl class="content">
	                    <dt>[mid]商户号:</dt>
	                    <dd>
	                      <span class="null-star">*</span>
	                      <input type="text" name="mid" value="填写商户号"/>
	                    </dd>
	                    <dt>[orderCode]商户订单号:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                        <input type="text" name="orderCode" value="<?php echo "2017090000".time();?>"/>
	                    </dd>
	                    <dt>[totalAmount]订单金额:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                        <input type="text" name="totalAmount" value="000000000012" />
	                    </dd>
	                    <dt>[subject]订单标题:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                       <input type="text" name="subject" value='话费充值'/>
	                    </dd>
	                    <dt>[body]订单描述:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                        <input type="text" name="body"  value='用户购买话费0.12'/>
	                    </dd>
	                    <dt>[txnTimeOut]订单超时时间:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                        <input type="text" name="txnTimeOut"  value='20171230000000'/>
	                    </dd>
	                    <dt>[payMode]支付模式:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                        <select id="payMode" name="payMode" >
								  <option value="bank_pc" selected="selected">银行网关支付</option>
								</select>
	                    </dd>
	                    <dt>[bankCode]银行编码:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                        <select name="bankCode">
								  <option value="01020000" >工商银行</option>
								  <option value="01050000" selected="selected">建设银行</option>
								  <option value="01030000">农业银行</option>
								  <option value="03080000">招商银行</option>
								  <option value="03010000">交通银行</option>
								  <option value="01040000">中国银行</option>
								  <option value="03030000">光大银行</option>
								  <option value="03050000">民生银行</option>
								  <option value="03090000">兴业银行</option>
								  <option value="03020000">中信银行</option>
								  <option value="03060000">广发银行</option>
								  <option value="03100000">浦发银行</option>
								  <option value="03070000">平安银行</option>
								  <option value="03040000">华夏银行</option>
								  <option value="04083320">宁波银行</option>
								  <option value="03200000">东亚银行</option>
								  <option value="04012900">上海银行</option>
								  <option value="01000000">中国邮储银行</option>
								  <option value="04243010">南京银行</option>
								  <option value="65012900">上海农商行</option>
								  <option value="03170000">渤海银行</option>
								  <option value="64296510">成都银行</option>
								  <option value="04031000">北京银行</option>
								  <option value="64296511">徽商银行</option>
								  <option value="04341101">天津银行</option>
								</select>
	                    </dd>
	                    <dt>[payType]支付类型:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                        <select name="payType">
								  <option value="1">1-网银支付（借记卡)</option>
								  <option value="3" selected="selected" >3-混合通道（借/贷记卡均可使用）</option>
								</select><br/>
	                    </dd>
	                    <dt>[clientIp]客户端IP:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                        <input type="text" name="clientIp" value="127.0.0.1"/>
	                    </dd>
	                    <dt>[notifyUrl]异步通知地址:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                        <input type="text" name="notifyUrl" value='http://192.168.22.171/sandpay-qr-phpdemo.bak/test/dist/notifyUrl.php'>
	                    </dd>
	                    <dt>[frontUrl]前台通知地址:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                       	<input type="text" name="frontUrl" value='http://61.129.71.103:8003/jspsandpay/payReturn.jsp'>
	                    </dd>
	                    <dt>[extend]扩展域:</dt>
	                    <dd>
	                        <span class="null-star">*</span>
	                        <input type="text" name="extend" value=""/>
	                    </dd>
	                    <dt></dt>
	                    <dd>
	                        <span class="new-btn-login-sp">
	                            <button class="new-btn-login" type="submit" style="text-align:center;" >确 认</button>
	                        </span>
	                    </dd>
	                </dl>
	            </div>
	        </form>
        </div>
    </div>
</div>
</body>
</html>