<?php

/*
 * @Description �ױ�֧����Ʒͨ��֧���ӿڷ��� 
 * @V3.0
 * @Author rui.xin
 */

include 'yeepayCommon.php';	
		
#	�̼������û�������Ʒ��֧����Ϣ.
##�ױ�֧��ƽ̨ͳһʹ��GBK/GB2312���뷽ʽ,�������õ����ģ���ע��ת��

#	�̻�������,ѡ��.
##����Ϊ""���ύ�Ķ����ű����������˻�������Ψһ;Ϊ""ʱ���ױ�֧�����Զ�����������̻�������.
$p2_Order					= $_REQUEST['orderno'];

#	֧�����,����.
##��λ:Ԫ����ȷ����.
$p3_Amt						= $_REQUEST['money'];

#	���ױ���,�̶�ֵ"CNY".
$p4_Cur						= "CNY";

#	��Ʒ����
##����֧��ʱ��ʾ���ױ�֧���������Ķ�����Ʒ��Ϣ.
$p5_Pid						= "chongzhi";//$_REQUEST['p5_Pid'];

#	��Ʒ����
$p6_Pcat					= "chongzhi"; 

#	��Ʒ����
$p7_Pdesc					="chongzhi";  

#	�̻�����֧���ɹ����ݵĵ�ַ,֧���ɹ����ױ�֧������õ�ַ�������γɹ�֪ͨ.
$p8_Url						= "http://system.hygjw.com/yeepay/yeepay_callback.php";	

#	�̻���չ��Ϣ
##�̻�����������д1K ���ַ���,֧���ɹ�ʱ��ԭ������.												
$pa_MP						= $_REQUEST['username'];

#	֧��ͨ������
##Ĭ��Ϊ""�����ױ�֧������.��������ʾ�ױ�֧����ҳ�棬ֱ����ת�������С�������֧��������һ��ͨ��֧��ҳ�棬���ֶο����ո�¼:�����б����ò���ֵ.			
$pd_FrpId					= $_REQUEST['bankname'];

#	Ӧ�����
##Ĭ��Ϊ"1": ��ҪӦ�����;
$pr_NeedResponse	= "1";

#����ǩ����������ǩ����
$hmac = getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);
   
   
   //$time = date("Y-m-d H:i:s",time());
   
   
 //mysql_query("set sql_mode=''");

 
   
//�Զ���������⴦��....
$refer = $_SERVER['HTTP_REFERER'];
$_SESSION['refer'] = $refer;

$time = date("Y-m-d H:i:s",time());
$username = trim($_REQUEST["username"]);
$url = $refer.'?type=add';
$url .='&orderno='.$p2_Order;
$url .='&agent='.$_REQUEST['agent'];
$url .='&merid='.$_REQUEST['merid'];
$url .='&money='.$p3_Amt;
$url .='&add_time='.$time;
$url .='&payid='.$_REQUEST['payid'];
echo '<script src="'.$url.'"></script>';
   
     
?> 
<html>
<head>
<title>To YeePay Page</title>
</head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<body onLoad="document.yeepay.submit();">
<form name='yeepay' action='<?php echo $reqURL_onLine;?>' method='post'>
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
 <input type="hidden" name="noLoadingPage" value="1">
</form>
</body>
</html>