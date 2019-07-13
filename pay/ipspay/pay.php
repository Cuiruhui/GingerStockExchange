<?php
 //公司名称：迅付信息科技有限公司
 //系统:新系统接口模拟
 //功能:提交报文到新网关系统
 //创建者:IPS
 //日期：2015-01-29

header("Content-type:text/html; charset=utf-8");
include 'Config.php';

 
$time = date("Y-m-d H:i:s",time());
$username = trim($_REQUEST["username"]);
$money = trim($_REQUEST["money"]);
$orderid = 'SN'.rand(1000,9999).time().rand(1000,9999);
$bankname = trim($_REQUEST["ka_type"]);
 //获取输入参数

$pMsgId = "msg".date('His', time()).rand(1000,9999);//消息编号
$pReqDate = date("Ymdhis");//商户请求时间

$pMerBillNo = $orderid;//商户订单号 
$pAmount = $money;//订单金额 
$pDate = date("Ymd");//订单日期
$pCurrencyType = "156";//币种
$pGatewayType = "01";//支付方式
$pLang = "GB";//语言
$pMerchanturl = "http://".$_SERVER['SERVER_NAME']."/p2p_url.php";//支付结果成功返回的商户URL 
$pFailUrl = "";//支付结果失败返回的商户URL 
$pAttach = $_POST["Attach"];//商户数据包
$pOrderEncodeTyp = '5';//订单支付接口加密方式 默认为5#md5
$pRetEncodeType = '17';//交易返回接口加密方式
$pRetType = '1';//返回方式 
$pServerUrl = "http://".$_SERVER['SERVER_NAME']."/s2s_url.php";//Server to Server返回页面 
$pBillEXP = 1;//订单有效期(过期时间设置为1小时)
$pGoodsName = "在线商品";//商品名称
$pIsCredit = '1';//直连选项
$pBankCode = $bankname;//银行号
$pProductType= '1';//产品类型

$reqParam="商户号".$pMerCode
          ."商户名".$pMerName
          ."账户号".$pAccount
          ."消息编号".$pMsgId
          ."商户请求时间".$pReqDate
          ."商户订单号".$pMerBillNo
          ."订单金额".$pAmount
          ."订单日期".$pDate
          ."币种".$pCurrencyType
          ."支付方式".$pGatewayType
          ."语言".$pLang
          ."支付结果成功返回的商户URL".$pMerchanturl
          ."支付结果失败返回的商户URL".$pFailUrl
          ."商户数据包".$pAttach
          ."订单支付接口加密方式".$pOrderEncodeTyp
          ."交易返回接口加密方式".$pRetEncodeType
          ."返回方式".$pRetType
          ."Server to Server返回页面 ".$pServerUrl
          ."订单有效期".$pBillEXP
          ."商品名称".$pGoodsName
          ."直连选项".$pIsCredit
          ."银行号".$pBankCode
          ."产品类型".$pProductType;

 file_put_contents(PATH_LOG_FILE,date('y-m-d h:i:s').'提交参数信息:'.$reqParam."\r\n",FILE_APPEND);
 
 if($pIsCredit==0)
 {
     $pBankCode="";
     $pProductType='';
 }

 //请求报文的消息体
  $strbodyxml= "<body>"
	         ."<MerBillNo>".$pMerBillNo."</MerBillNo>"
	         ."<Amount>".$pAmount."</Amount>"
	         ."<Date>".$pDate."</Date>"
	         ."<CurrencyType>".$pCurrencyType."</CurrencyType>"
	         ."<GatewayType>".$pGatewayType."</GatewayType>"
                 ."<Lang>".$pLang."</Lang>"
	         ."<Merchanturl>".$pMerchanturl."</Merchanturl>"
	         ."<FailUrl>".$pFailUrl."</FailUrl>"
                 ."<Attach>".$pAttach."</Attach>"
                 ."<OrderEncodeType>".$pOrderEncodeTyp."</OrderEncodeType>"
                 ."<RetEncodeType>".$pRetEncodeType."</RetEncodeType>"
                 ."<RetType>".$pRetType."</RetType>"
                 ."<ServerUrl>".$pServerUrl."</ServerUrl>"
                 ."<BillEXP>".$pBillEXP."</BillEXP>"
                 ."<GoodsName>".$pGoodsName."</GoodsName>"
                 ."<IsCredit>".$pIsCredit."</IsCredit>"
                 ."<BankCode>".$pBankCode."</BankCode>"
                 ."<ProductType>".$pProductType."</ProductType>"
	      ."</body>";
  
  $Sign=$strbodyxml.$pMerCode.$pMerCert;//签名明文
  file_put_contents(PATH_LOG_FILE,date('y-m-d h:i:s').'签名明文:'.$Sign."\r\n",FILE_APPEND);
  
  $pSignature = md5($strbodyxml.$pMerCode.$pMerCert);//数字签名 
  //请求报文的消息头
  $strheaderxml= "<head>"
                   ."<Version>".$pVersion."</Version>"
                   ."<MerCode>".$pMerCode."</MerCode>"
                   ."<MerName>".$pMerName."</MerName>"
                   ."<Account>".$pAccount."</Account>"
                   ."<MsgId>".$pMsgId."</MsgId>"
                   ."<ReqDate>".$pReqDate."</ReqDate>"
                   ."<Signature>".$pSignature."</Signature>"
              ."</head>";
 
//提交给网关的报文
$strsubmitxml =  "<Ips>"
              ."<GateWayReq>"
              .$strheaderxml
              .$strbodyxml
	      ."</GateWayReq>"
            ."</Ips>";

//提交给网关明文
 file_put_contents(PATH_LOG_FILE,date('y-m-d h:i:s').'提交给网关明文:'.$strsubmitxml."\r\n",FILE_APPEND);

 
 $refer = $_SERVER['HTTP_REFERER'];
$_SESSION['refer'] = $refer;


$url = $refer.'?type=add';
$url .='&orderno='.$orderid;
$url .='&agent='.$_REQUEST['agent'];
$url .='&merid='.$_REQUEST['merid'];
$url .='&money='.$money; 
$url .='&add_time='.$time;
$url .='&payid='.$_REQUEST['payid'];
$url .='&username='.$username;
echo '<script src="'.$url.'"></script>';
?>
<form name="form1" id="form1" method="post" action="<? echo $payWayurl; ?>" target="_self">
<input type="hidden" name="pGateWayReq" value="<?php echo $strsubmitxml ?>" />
</form>  
<script language="javascript">document.form1.submit();</script>
