<?php
session_start();
date_default_timezone_set(PRC);

//ƽ̨�̻�ID����Ҫ�������Լ����̻�ID
$UserId = $_SESSION['webid'];

//�ӿ���Կ����Ҫ���������Լ�����Կ��Ҫ����̨���õ�һ��
//��¼APIƽ̨���̻�����-->��ȫ����-->��Կ���ã������Լ�������Կ

$SalfStr = $_SESSION['webpwd'];


//���ص�ַ��Ҫ���³������ڵ�ƽ̨���ص�ַ

$gateWary="http://do.jftpay.net/chargebank.aspx";


//��ֵ�����̨֪ͨ��ַ

$result_url="http://gp.356158.com/pay/jftpay/result_url.php";


//��ֵ����û�����վ�ϵ�ת���ַ

$notify_url="http://gp.356158.com/pay/jftpay/notify_Url.php";
?>