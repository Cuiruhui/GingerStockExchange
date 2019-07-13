<? header("content-Type: text/html; charset=utf-8");?> 
<?php

require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
require_once("dinpay_key.php");

  // 公共函数定义
  function HexToStr($hex)
  {
     $string="";
     for ($i=0;$i<strlen($hex)-1;$i+=2)
         $string.=chr(hexdec($hex[$i].$hex[$i+1]));
     return $string;
  } 

//===========================   =======================
					
	$m_id		= 	'';			 // 	
	$m_orderid	= 	'';			// 
	$m_oamount	= 	'';			// 
	$m_ocurrency= 	'';			// 		
	$m_language	= 	'';			// 
	$s_name		= 	'';			// 
	$s_addr		= 	'';			// 
	$s_postcode	= 	''; 		// 
	$s_tel		= 	'';			// 
	$s_eml		= 	'';			// 
	$r_name		= 	'';			// 
	$r_addr		= 	'';			// 
	$r_postcode	= 	''; 		// 
	$r_tel		= 	'';			// 
	$r_eml		= 	'';			// 
	$m_ocomment	= 	''; 		// 
	$modate		=	'';			// 
	$State		=	'';			// 
	
	// 
	$OrderInfo	=	$_POST['OrderMessage'];			 

	$signMsg 	=	$_POST['Digest'];				 
	// 
  
	$digest = strtoupper(md5($OrderInfo.$key));

 
	if ($digest == $signMsg)
	{
		// 
		//$decode = $DES->Descrypt($OrderInfo, $key);
		$OrderInfo = HexToStr($OrderInfo);
		//===========================   ====================================
		$parm=explode("|", $OrderInfo);

		$m_id		= 	$parm[0];				
		$m_orderid	= 	$parm[1];		
		$m_oamount	= 	$parm[2];			
		$m_ocurrency= 	$parm[3];				
		$m_language	= 	$parm[4];			
		$s_name		= 	$parm[5];				
		$s_addr		= 	$parm[6];				
		$s_postcode	= 	$parm[7];		
		$s_tel		= 	$parm[8];			
		$s_eml		= 	$parm[9];			
		$r_name		= 	$parm[10];			
		$r_addr		= 	$parm[11];				
		$r_postcode	= 	$parm[12];			
		$r_tel		= 	$parm[13];			
		$r_eml		= 	$parm[14];			
		$m_ocomment	= 	$parm[15];
		$modate		=	$parm[16];
		$State		=	$parm[17];
 
			
			
			
		if ($State == 2)
			{
			
			
$r6_Order=m_orderid;
$pay_config = $xltm->SQL->GetRow("Select * from `pay_config` where active='1' and id='{$s_name}'");
			
			
$row = $xltm->SQL->GetRow("Select * from `payment` where orderno='{$m_orderid}'");			
			
			
			
			
		if($row['result']=='0')
		{	
			$username = $row['username']; 
			$urow = $xltm->SQL->GetRow("Select agent from `user_users` where username='{$username}'");
			$agent = $urow['agent'];
			//更新支付订单信息
			$xltm->SQL->Execute("update `payment` set result='1',trxid='{$m_orderid}',uid='{$r7_Uid}',return_time='{$xltm->sys_time}',params='{$params}' where orderno='{$m_orderid}'");
			//更新用户的可用余额和保证金
			$money = ceil($xltm->config['cost_exchange_rate'] * $m_oamount);
			$xltm->SQL->Execute("update `user_users` set money=money + {$m_oamount},cash=cash+{$m_oamount} where username='{$username}'");
			//添加用户帐单信息
			$xltm->SQL->Execute("insert into `bill_log` set bill_type='充值',agent='{$agent}',username='{$username}',money='{$m_oamount}',remark='在线支付入款',add_time='{$xltm->sys_time}',add_date='{$xltm->sys_date}'");
			exit("true");
		}
			
			
			
 echo "<Script language=javascript>alert('支付成功');location.href='/trade.php?client=false';</script>";
exit;	
			
			
			//
//				echo "支付成功".'<br>';
//				echo "商家号=".$m_id.'<br>';
//				echo "订单号=".$m_orderid.'<br>';
//				echo "金额=".$m_oamount.'<br>';
//				echo "币种=".$m_ocurrency.'<br>';
//						echo ".................";
			}
		else 
			{
				echo "支付失败";
			}
?>
<?php
	}else{
	
 	
	
?>
	md5 error
<?php
	}
?>
 
