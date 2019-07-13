     <?php
 //公司名称：迅付信息科技有限公司
 //系统:新系统接口S2S返回
 //功能:新系统返回报文处理
 //创建者:IPS
 //日期：2015-01-29
header("Content-type:text/html; charset=utf-8");
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
include 'Config.php';
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

$paymentResult = $_POST["paymentResult"];//获取信息

$xml=simplexml_load_string($paymentResult,'SimpleXMLElement', LIBXML_NOCDATA); 

  //读取相关xml中信息
   $ReferenceIDs = $xml->xpath("GateWayRsp/head/ReferenceID");//关联号
   //var_dump($ReferenceIDs); 
   $ReferenceID = $ReferenceIDs[0];//关联号
   $RspCodes = $xml->xpath("GateWayRsp/head/RspCode");//响应编码
   $RspCode=$RspCodes[0];
   $RspMsgs = $xml->xpath("GateWayRsp/head/RspMsg"); //响应说明
   $RspMsg=$RspMsgs[0];
   $ReqDates = $xml->xpath("GateWayRsp/head/ReqDate"); // 接受时间
    $ReqDate=$ReqDates[0];
   $RspDates = $xml->xpath("GateWayRsp/head/RspDate");// 响应时间
    $RspDate=$RspDates[0];
   $Signatures = $xml->xpath("GateWayRsp/head/Signature"); //数字签名
    $Signature=$Signatures[0];
   $MerBillNos = $xml->xpath("GateWayRsp/body/MerBillNo"); // 商户订单号
    $MerBillNo=$MerBillNos[0];
   $CurrencyTypes = $xml->xpath("GateWayRsp/body/CurrencyType");//币种
    $CurrencyType=$CurrencyTypes[0];
   $Amounts = $xml->xpath("GateWayRsp/body/Amount"); //订单金额
    $Amount=$Amounts[0];
   $Dates = $xml->xpath("GateWayRsp/body/Date");    //订单日期
    $Date=$Dates[0];
   $Statuss = $xml->xpath("GateWayRsp/body/Status");  //交易状态
    $Status=$Statuss[0];
   $Msgs = $xml->xpath("GateWayRsp/body/Msg");    //发卡行返回信息
    $Msg=$Msgs[0];
   $Attachs = $xml->xpath("GateWayRsp/body/Attach");    //数据包
    $Attach=$Attachs[0];
   $IpsBillNos = $xml->xpath("GateWayRsp/body/IpsBillNo"); //IPS订单号
    $IpsBillNo=$IpsBillNos[0];
   $IpsTradeNos = $xml->xpath("GateWayRsp/body/IpsTradeNo"); //IPS交易流水号
    $IpsTradeNo=$IpsTradeNos[0];
   $RetEncodeTypes = $xml->xpath("GateWayRsp/body/RetEncodeType");    //交易返回方式
    $RetEncodeType=$RetEncodeTypes[0];
   $BankBillNos = $xml->xpath("GateWayRsp/body/BankBillNo"); //银行订单号
    $BankBillNo=$BankBillNos[0];
   $ResultTypes = $xml->xpath("GateWayRsp/body/ResultType"); //支付返回方式
    $ResultType=$ResultTypes[0];
   $IpsBillTimes = $xml->xpath("GateWayRsp/body/IpsBillTime"); //IPS处理时间
    $IpsBillTime=$IpsBillTimes[0];
	
$resParam="关联号:"
          .$ReferenceID
          ."响应编码:"
          .$RspCode
          ."响应说明:"
          .$RspMsg
          ."接受时间:"
          .$ReqDate
          ."响应时间:"
          .$RspDate
          ."数字签名:"
          .$Signature
          ."商户订单号:"
          .$MerBillNo
          ."币种:"
          .$CurrencyType
          ."订单金额:"
          .$Amount
          ."订单日期:"
          .$Date
          ."交易状态:"
          .$Status
          ."发卡行返回信息:"
          .$Msg
          ."数据包:"
          .$Attach
		   ."IPS订单号:"
		     .$IpsBillNo
		   ."交易返回方式:"
          .$RetEncodeType
		   ."银行订单号:"
		     .$BankBillNo
			  ."支付返回方式:"
		     .$ResultType
			   ."IPS处理时间:"
		     .$IpsBillTime;


//验签明文
//billno+【订单编号】+currencytype+【币种】+amount+【订单金额】+date+【订单日期】+succ+【成功标志】+ipsbillno+【IPS订单编号】+retencodetype +【交易返回签名方式】+【商户内部证书】

 $sbReq = "<body>"
                          . "<MerBillNo>" . $MerBillNo . "</MerBillNo>"
                          . "<CurrencyType>" . $CurrencyType . "</CurrencyType>"
                          . "<Amount>" . $Amount . "</Amount>"
                          . "<Date>" . $Date . "</Date>"
                          . "<Status>" . $Status . "</Status>"
                          . "<Msg><![CDATA[" . $Msg . "]]></Msg>"
                          . "<Attach><![CDATA[" . $Attach . "]]></Attach>"
                          . "<IpsBillNo>" . $IpsBillNo . "</IpsBillNo>"
                          . "<IpsTradeNo>" . $IpsTradeNo . "</IpsTradeNo>"
                          . "<RetEncodeType>" . $RetEncodeType . "</RetEncodeType>"
                          . "<BankBillNo>" . $BankBillNo . "</BankBillNo>"
                          . "<ResultType>" . $ResultType . "</ResultType>"
                          . "<IpsBillTime>" . $IpsBillTime . "</IpsBillTime>"
                       . "</body>";
$sign=$sbReq.$pMerCode.$pMerCert;

   
      if($RspCode=='000000')
    {
		$r6_Order = $MerBillNo;
		$r3_Amt = $Amount;
		$row = $xltm->SQL->GetRow("Select * from `payment` where orderno='{$r6_Order}'");
		
		if(is_array($row))
		{
			if($row['result']=='0')
			{	
				$username = $row['username']; 
				$urow = $xltm->SQL->GetRow("Select agent from `user_users` where username='{$username}'");
				$agent = $urow['agent'];
				//更新支付订单信息
				$xltm->SQL->Execute("update `payment` set result='1',trxid='{$IpsBillNo}',uid='177873',return_time='{$xltm->sys_time}',params='{$md5sign}' where orderno='{$r6_Order}'");
				//更新用户的可用余额和保证金
				$money = ceil($xltm->config['cost_exchange_rate'] * $r3_Amt);
				$xltm->SQL->Execute("update `user_users` set money=money + {$money},cash=cash+{$r3_Amt} where username='{$username}'");
				//添加用户帐单信息
				$xltm->SQL->Execute("insert into `bill_log` set bill_type='充值',agent='{$agent}',username='{$username}',money='{$r3_Amt}',remark='在线支付入款',add_time='{$xltm->sys_time}',add_date='{$xltm->sys_date}'");
				exit("true");
			}
			else
			{
				exit("false:002");
			}
		}
		
    }


?>