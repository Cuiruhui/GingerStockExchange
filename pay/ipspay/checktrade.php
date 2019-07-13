<?php
 //公司名称：迅付信息科技有限公司
 //系统:新系统接口模拟
 //功能:新系统返回报文处理
 //创建者:IPS
 //日期：2015-01-29
 
 $paymentResult = $_POST["paymentResult"];//获取信息
 file_put_contents(PATH_LOG_FILE,date('y-m-d h:i:s').'主动对账接收到的报文:'.$paymentResult."\r\n",FILE_APPEND);
 echo "ipscheckok";
?>