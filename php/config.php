<?
date_default_timezone_set(PRC);
//ƽ̨�̻�ID����Ҫ�������Լ����̻�ID
$UserId="70022";


//�ӿ���Կ����Ҫ���������Լ�����Կ��Ҫ����̨���õ�һ��
//��¼APIƽ̨���̻�����-->��ȫ����-->��Կ���ã������Լ�������Կ
$SalfStr="420892ecaecbaacaad75801cd213a284 ";


//���ص�ַ��Ҫ���³������ڵ�ƽ̨���ص�ַ
//���磺����www.abc.com�ϽӵĽӿڣ���ô���ص�ַ���ǣ�http://www.abc.com/pay/gateway.asp
$gateWary="../pay/gateway.asp";


//��ֵ�����̨֪ͨ��ַ
$result_url="http://".$_SERVER["HTTP_HOST"]."/php/result_url.php";


//��ֵ����û�����վ�ϵ�ת���ַ
$notify_url="http://".$_SERVER["HTTP_HOST"]."/php/notify_Url.php";
?>