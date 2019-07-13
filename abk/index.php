

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<title>在线入金</title>
<link rel="stylesheet" type="text/css" href="rj_files/ui.css">
<link rel="stylesheet" type="text/css" href="rj_files/layout.css"/>
<style type="text/css">
div#common-body{border-color:rgba(111,93,1,1);box-shadow:1px 1px 8px rgba(111,93,11,1),-1px -1px 8px rgba(111,93,11,1);}
div#common-header{background-color:rgba(111,93,1,1);}
#common-form div.fielddiv:hover{border-color:rgba(111,93,1,1)}
#common-form div.step-title,
#common-form div.steps ul li.active{background:rgba(111,93,0,1);}
div#common-footer div.webems-tab-button{background:rgba(111,93,0,1);}


div#common-body{border-color:rgb(111,93,1);box-shadow:1px 1px 8px rgb(111,93,11),-1px -1px 8px rgb(111,93,11);}
div#common-header{background-color:rgb(111,93,1);}
#common-form div.fielddiv:hover{border-color:rgb(111,93,1)}
#common-form div.step-title,
#common-form div.steps ul li.active{background:rgb(111,93,0);}
div#common-footer div.webems-tab-button{background:rgb(111,93,0);}
</style><link rel="stylesheet" type="text/css" href="rj_files/banks.css"/>
<link rel="stylesheet" type="text/css" href="rj_files/zh-cn.css" />
</head>
<body>
<div  style="width:800px;margin:0 auto;">
	<div id="webems-body">
  <div id="webems-main" class="clearfix">
			<div id="webems-form">
				<form name="common-form" method="post" action="req.php" target="_blank"><fieldset>
				
<div class="fielddiv">
	<h2>账户信息</h2>
	<dl><dt>账户信息:</dt><dd>
		<div class=" item onethird"><input name="username" type="text" class="input empty"  allowBlank="false"   value=""/><p>交易账号</p></div>
		<div class=" item onethird"><input name="contact" type="text" class="input cn empty"  allowBlank="false"   value=""/><p>客户名称</p></div>
	</dd></dl>
	<dl><dt>联络方式:</dt><dd>
		<div class="item onethird"><input name="mobile"  type="text" vtype="mobile" allowBlank="false"   value=""/><p>手机(<span style='color:#C03'>(非常重要)</span>)</p></div>
		<div class="item onethird"><input name="email"  type="text" vtype="email"  allowBlank="false"   value=""/><p>电邮(<span style='color:#C03'>(非常重要)</span>)</p></div>
	</dd></dl>
</div>

<div class="fielddiv">
	<h2>入金信息</h2>
         

		<dl><dt>入金金额</dt><dd>
		<input name="exchangerate_switch" type="hidden" value="1"/>
		<input name="exchangerate_tip" type="hidden" value="1"/>
		<input name="exchangerate" type="hidden" value="6.8400"/>
		
			<div class="item  two-thirds">
            	<input name="amount_min" type="hidden" value="100"/>
				<input name="amount" type="hidden" value="684"/>
				<input type="number"  allowBlank="false"  minValue="100" name="amount_usd" value="100" onKeyUp="this.value=this.value.replace(/[\D]/g,'');" placeholder="USD"/>
				<p>入金金额 </p>
			</div>
			
				<div class="item">
				<p>兑换汇率:<strong class="red u">USD 1.00 : CNY 6.8400</strong></p>
				<p class="notbg">引导支付:<strong class="red bold u">CNY <span id="amount_show">684.00</span></strong></p>
				</div>
			
		</dd></dl>
		<dl><dt>入金银行:</dt><dd>
		<p>入金银行  <strong class="red u bank_checked"></strong> 您已选择通过</p>
		<div class="bank_liast">       <table width="90%" border="0" cellpadding="1" cellspacing="2" id="RadioBankList"  >
   
     <!--   <tr>
      <td></td>
      <td><input type="radio" name="Bankco"  value="WX"/></td>
      <td><img src="imagesc/wx.jpg"  alt="wx" /></td>
   <td><input type="radio" name="Bankco"  value="QQ"  /></td>
      <td><img src="imagesc/qq.gif"  alt="qq" /></td>
    </tr>-->

 <tr>
      <td width="5"></td>
      <td width="30" ><input type="radio" name="Bankco"  value="ICBC"   checked="checked"  /></td>
      <td width="210" ><img src="imagesc/bankicbc.gif"  alt="中国工商银行" /></td>
      <td width="30"><input type="radio" name="Bankco"  value="PINGANBANK"  /></td>
      <td><img src="imagesc/bandpingan.gif"  alt="中国平安银行" /></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="radio" name="Bankco"  value="CMBCHINA"  /></td>
      <td><img src="imagesc/bankcmb.gif"  alt="中国招商银行" /></td>
      <td><input type="radio" name="Bankco"  value="CMBC"  /></td>
      <td><img src="imagesc/minsheng.gif"  alt="中国渤海银行" width="154" height="33" /></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="radio" name="Bankco"  value="ABC"  /></td>
      <td><img src="imagesc/bankabc.gif"  alt="中国农业银行" /></td>
      <td><input type="radio" name="Bankco"  value="HXB"  /></td>
      <td><img src="imagesc/bankhx.gif"  alt="中国华夏银行" /></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="radio" name="Bankco"  value="CCB"  /></td>
      <td><img src="imagesc/bankccb.gif"  alt="中国建设银行" /></td>
      <td><input type="radio" name="Bankco"  value="BOC"  /></td>
      <td><img src="imagesc/zhongguo.gif"  alt="中国东亚银行" width="154" height="33" /></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="radio" name="Bankco"  value="BCCB"  /></td>
      <td><img src="imagesc/bankbj.gif"  alt="中国北京银行" /></td>
      <td><input type="radio" name="Bankco"  value="ECITIC"  /></td>
      <td><img src="imagesc/bankzx.gif"  alt="中国中信银行" /></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="radio" name="Bankco"  value="BOCO"  /></td>
      <td><img src="imagesc/bankbcc.gif"  alt="中国交通银行" /></td>
      <td><input type="radio" name="Bankco"  value="CEB"  /></td>
      <td><img src="imagesc/guangda.gif"  alt="中国深圳发展银行" width="154" height="33" /></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="radio" name="Bankco"  value="CIB"  /></td>
      <td><img src="imagesc/bankcib.gif"  alt="中国兴业银行" /></td>
      <td><input type="radio" name="Bankco"  value="SPDB"  /></td>
      <td><img src="imagesc/bankshpd.gif"  alt="中国上海浦东发展银行" /></td>
    </tr>
    <tr>
      <td></td>
      <td><input type="radio" name="Bankco"  value="GDB"  /></td>
      <td><img src="imagesc/bankgdb.gif"  alt="广东发展银行" /></td>
      <td><input type="radio" name="Bankco"  value="POST"  /></td>
      <td><img src="imagesc/bankpost.gif"  alt="中国邮政银行" /></td>
    </tr>

  </table>
					 
				        </div>  
		</dd></dl>
        
		<!--<dl><dt>入金银行:</dt><dd>
			<p>入金银行  <strong class="red u bank_checked"></strong> 您已选择通过</p>
			
                <div class="bank_list">              </div>   
		</dd></dl>-->
</div>


	<textarea name="formdata" class="none"></textarea>
	<input type="hidden" name="lang" value="zh-cn"/>
	<input type="hidden" name="version" value="v2"/>
	<input type="hidden" name="attach"/>
	<input type="hidden" name="docredit" value="1"/>
	<input type="hidden" name="bankco"/>
	<input type="hidden" name="bank"/>
        <input type="hidden" name="pay_id"/>
	<input type="hidden" name="payment"/>
        <input type="hidden" name="payUrl"/>

    
    
	<div class="submit"><button type="button" name="onlinepay_submit" class="btn  btn-black"><em class="icon_save"></em>确认无误 现在支付</button>
	</div>

				</fieldset></form>

			</div>
		</div>
	</div></div>
</body>
</html>
<script type="text/javascript" src="rj_files/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="rj_files/jquery-ui.js"></script>
<script type="text/javascript" src="rj_files/webems-system.js"></script>
<script type="text/javascript" src="rj_files/uploadify.min.js"></script> 
<script type="text/javascript" src="rj_files/init.js"></script>
<script type="text/javascript" src="rj_files/onlinepay.js"></script>
<script type="text/javascript" src="rj_files/form.js"></script>


