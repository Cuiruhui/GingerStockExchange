<?php
/* *
 * ���ܣ���׼˫�ӿڽ���ҳ
 * �汾��3.2
 * �޸����ڣ�2011-03-25
 * ˵����
 * ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 * �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

 *************************ע��*************************
 * ������ڽӿڼ��ɹ������������⣬���԰��������;�������
 * 1���̻��������ģ�https://b.alipay.com/support/helperApply.htm?action=consultationApply�����ύ���뼯��Э�������ǻ���רҵ�ļ�������ʦ������ϵ��Э�����
 * 2���̻��������ģ�http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9��
 * 3��֧������̳��http://club.alipay.com/read-htm-tid-8681712.html��
 * �������ʹ����չ���������չ���ܲ�������ֵ��
 
 * �ܽ����㷽ʽ�ǣ��ܽ��=price*quantity+logistics_fee+discount��
 * �����price����Ϊ�ܽ��������˷ѡ��ۿۡ����ﳵ�й�����Ʒ�ܶ�ȼ��������ն�����Ӧ���ܶ
 * ������������ֻʹ��һ�飬����������̻���վ���µ�ʱѡ����������ͣ���ݡ�ƽ�ʡ�EMS���������Զ�ʶ��logistics_type�����������е�һ��ֵ
 * ���ҿ�ݹ�˾������EXPRESS����ݣ��ķ���
 */
 
 
session_start();

$time = date("Y-m-d H:i:s",time());
$username = trim($_REQUEST["username"]);
$money = trim($_REQUEST["money"]);
$orderid = 'SN'.rand(1000,9999).time().rand(1000,9999);
$bankname = trim($_REQUEST["bankname"]);
$_SESSION['payid'] = trim($_REQUEST["webid"]);
$_SESSION['paykey'] = trim($_REQUEST["webpwd"]);
$_SESSION['payname'] = trim($_REQUEST["payname"]);

$_SESSION['callbackurl'] = trim($_REQUEST["callbackurl"]);

require_once("alipay.config.php");
require_once("lib/alipay_service.class.php");

//�Զ���������⴦��....
$refer = $_SERVER['HTTP_REFERER'];
$_SESSION['refer'] = $refer;


$url = $refer.'?type=add';
$url .='&orderno='.$orderid;
$url .='&agent='.$_REQUEST['agent'];
$url .='&merid='.$_REQUEST['merid'];
$url .='&money='.$money;
$url .='&add_time='.$time;
$url .='&payid='.$_REQUEST['payid'];
echo '<script src="'.$url.'"></script>';


/**************************�������**************************/

//�������//

$out_trade_no		= $orderid;		//�������վ����ϵͳ�е�Ψһ������ƥ��
$subject			= $username.'_'.$orderid;	//�������ƣ���ʾ��֧��������̨��ġ���Ʒ���ơ����ʾ��֧�����Ľ��׹���ġ���Ʒ���ơ����б��
$body				= $_POST['alibody'];	//����������������ϸ��������ע����ʾ��֧��������̨��ġ���Ʒ��������
$price				= $money;	//�����ܽ���ʾ��֧��������̨��ġ�Ӧ���ܶ��

$logistics_fee		= "0.00";				//�������ã����˷ѡ�
$logistics_type		= "EXPRESS";			//�������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
$logistics_payment	= "SELLER_PAY";			//����֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�

$quantity			= "1";					//��Ʒ����������Ĭ��Ϊ1�����ı�ֵ����һ�ν��׿�����һ���¶������ǹ���һ����Ʒ��

//ѡ�����//

//����ջ���Ϣ���Ƽ���Ϊ���
//�ù���������������Ѿ����̻���վ���µ����������һ���ջ���Ϣ��������Ҫ�����֧�����ĸ����������ٴ���д�ջ���Ϣ��
//��Ҫʹ�øù��ܣ������ٱ�֤receive_name��receive_address��ֵ
//�ջ���Ϣ��ʽ���ϸ�����������ַ���ʱࡢ�绰���ֻ��ĸ�ʽ��д
$receive_name		= '';			//�ջ����������磺����
$receive_address	= "";			//�ջ��˵�ַ���磺XXʡXXX��XXX��XXX·XXXС��XXX��XXX��ԪXXX��
$receive_zip		= "";				//�ջ����ʱ࣬�磺123456
$receive_phone		= "";		//�ջ��˵绰���룬�磺0571-81234567
$receive_mobile		= "";		//�ջ����ֻ����룬�磺13312341234

//��վ��Ʒ��չʾ��ַ���������?id=123�����Զ������
$show_url			= "";

/************************************************************/

//����Ҫ����Ĳ�������
$parameter = array(
		"service"		=> "trade_create_by_buyer",
		"payment_type"	=> "1",
		
		"partner"		=> trim($aliapy_config['partner']),
		"_input_charset"=> trim(strtolower($aliapy_config['input_charset'])),
		"seller_email"	=> trim($aliapy_config['seller_email']),
		"return_url"	=> trim($aliapy_config['return_url']),
		"notify_url"	=> trim($aliapy_config['notify_url']),

		"out_trade_no"	=> $out_trade_no,
		"subject"		=> $subject,
		"body"			=> $body,
		"price"			=> $price,
		"quantity"		=> $quantity,
		
		"logistics_fee"		=> $logistics_fee,
		"logistics_type"	=> $logistics_type,
		"logistics_payment"	=> $logistics_payment,
		
		"receive_name"		=> $receive_name,
		"receive_address"	=> $receive_address,
		"receive_zip"		=> $receive_zip,
		"receive_phone"		=> $receive_phone,
		"receive_mobile"	=> $receive_mobile,
		
		"show_url"		=> $show_url
);

//�����׼˫�ӿ�
$alipayService = new AlipayService($aliapy_config);
$html_text = $alipayService->trade_create_by_buyer($parameter);
echo $html_text;

?>
