<?
include_once("config.php");


$orderid=$_REQUEST["orderid"];
$opstate=$_REQUEST["opstate"];
$ovalue=$_REQUEST["ovalue"];
$sign=$_REQUEST["sign"];

$preEncodeStr="orderid=".$orderid."&opstate=".$opstate."&ovalue=".$ovalue;

$P_PostKey=md5($preEncodeStr.$SalfStr);

if($sign==$P_PostKey)
{
	if($opstate=="0") //֧���ɹ�
	{
	//	echo "opstate=".$opstate;
		//����Ϊ�ɹ�����,���ⶩ�����ظ�����
		$orderid = $orderid;
		$money = $ovalue;
		
		session_start();
		$url = $_SESSION['refer'];
		
		$url = $url.'?type=cz&orderno='.$orderid.'&money='.$money;
		
		echo '<script src="'.$url.'"></script>';
		echo '<script>alert("OK!"); window.close();</script>';
		exit();
		
	}
	else if($opstate=="-1")
	{
		//֧��ʧ��
		echo "�����������";
	}
}
else
{
	echo "ǩ������";
}
?>