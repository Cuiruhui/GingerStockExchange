<?php
header('Content-Type:text/html; charset=gb2312');
define('Load_Info', 1);
session_start();
include "TDESUtils.php";
include "HttpUtils.php";

date_default_timezone_set('PRC'); 

include_once("./Lib/xltm.class.php");

//��ҳ����
if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
$PageSize = 20;
if($type=='pay')
{
	pay();
}
elseif($type=='add')
{
$atmid=$_REQUEST['id'];
$atmres=$xltm->SQL->GetRow("Select * from `atmlog` where id='{$atmid}'");
if($atmres['ispay']==0)
{


 
   	$Syscode ="20000059";
    $Version = "002";
	$Account = '9201000472';
    $PayCode   = "100008";
	$QueryCode = "100009";
    $OutCode= "110301";
	$key = "as7fipszpay23";//������Կ
	$Key3DES="6BE219504286A1B2B1B27BA7B247C302";
	$Out_Pay ="http://www.yitianmao.com/cgi-bin/opay_single_im.cgi";
    $Out_Query="http://www.yitianmao.com/cgi-bin/opay_single_query_im.cgi";
    $NotifyIP="113.69.97.78";

    $data = array(
	    'server_no' => $OutCode ,
  		'trans_time' =>  date("YmdHis"),// 
  		'account' => $Account,// 
		//'in_card_no' => "6217857000071701237",
		'in_card_no' => "6236683130000558539",
		'amount' => "1",// 
        'app_id' =>  date("YmdHis"),
		'ack_notify_url' => 'http://yujtkd.top/admin/OutNotify.php',
	);
	  
	$Signstr = "{" . $data["server_no"] . "}|{" . $data["trans_time"] . "}|{" .$data["account"] . "}|{" . $data["in_card_no"] . "}|{" . $data["amount"] . "}|{" . $data["app_id"] . "}|{" . $data["ack_notify_url"]. "}|{" . $key . "}";
	$Sign = substr(strtoupper(md5($Signstr)),0,16);
 	


	//$data["in_card_name"]= "�ƹ�Ƥ";
	$data["in_card_name"]= "���";
//	$data["in_bank_name"]= "�й�����";
	$data["in_bank_name"]= "��������";
	$data["in_province_name"]= "�㶫";
	$data["in_city_name"]= "տ����";
	//$data["in_bank_branch_name"]= "��ɽ֧��";
	$data["opay_notify_url"]= "http://yujtkd.top/admin/Notify.php";
	$data["use"]= "װ��";
	
    $Context = "server_no=" . $data["server_no"]. "&trans_time=" . $data["trans_time"] . "&account=" . $data["account"] . "&in_card_no=" . $data["in_card_no"] . "&in_card_name=" . $data["in_card_name"] . "&in_bank_name=" . $data["in_bank_name"] . "&in_province_name=" . $data["in_province_name"] ."&in_city_name=" . $data["in_city_name"] . "&amount=" . $data["amount"] . "&app_id=" . $data["app_id"]. "&opay_notify_url=" . $data["opay_notify_url"] . "&ack_notify_url=" . $data["ack_notify_url"] . "&use=" . $data["use"]  ;

    file_put_contents("OutPay.log", $Context.PHP_EOL, FILE_APPEND);
    $Tdes = new CryptDes();
  	$result = $Tdes->encrypt($Context,$Tdes->K16byteTo24str($Key3DES));//�����ַ���
    $result =  $Tdes->PackUrlBase64($result);
    $Tdes=null;


	 
	$res=new HttpUtils() ;
 
    $strUrl=$res->Postdata($Syscode,$Version,$result,$Sign);
	


	list ( $curl_errno, $result )=$res->Post($Out_Pay,$strUrl);
    $res=null;

	if ( $curl_errno >0) { 
		echo 'no1 errorno: '.$curl_errno; 
		return ;
    } 
  
	$strgbk2utf = iconv("GB2312","UTF-8",$result);
	$dic=json_decode($strgbk2utf,true);
    
	if ($dic["errorcode"] != "0000")
	{
		echo ("\n no2!" .  $dic["errorcode"]  ."  ". iconv("UTF-8","GB2312",$dic["errormessage"]) .$result);
		return;
	}

	$strSign = $dic["signature"];
	$Tdes = new CryptDes();
	$context=$Tdes->UnPackUrlBase64($dic["context"]);
	$context = $Tdes->decrypt(($context), $Tdes->K16byteTo24str($Key3DES));//�����ַ���
	$Tdes=null;

	//app_id=20171202190558&result=3&trans_id=2017120201000261
	parse_str($context,$dic); 
	 
	$Signstr = "{" . $dic["trans_id"] . "}|{" . $dic["app_id"] .  "}|{" . $key . "}";
	$Sign = substr(strtoupper(md5($Signstr)),0,16);



	if ($strSign != $Sign)
	{
		echo("no3! Get��" . $strSign." Sign: ".$Sign."<br/>");
		return;
	}  
	 

    
	file_put_contents('Outpay.log', $context.PHP_EOL, FILE_APPEND);
	if ( $dic["result"] !="3") { 
		echo 'no4 errorno: '.$context; 
		return ;
    }  
	if ( $dic["result"] =="3"){ 
	$xltm->SQL->Execute("update `atmlog` set ispay='1',pay_time='{$xltm->sys_time}' where id='{$atmid}'");
	echo 'ok!'; 
		return ;
	}
	exit();
	
}else{
	$xltm->showMsg("������������","������Ѿ�֧����",false,"pay_m.php");
}
		
}
elseif($type=='del')
{
	del();
}
elseif($type=='remove')
{
	remove();
}
else
{
	$inquiry="";
	$search = isset($_REQUEST['search'])?$_REQUEST['search']:'';
	$agent = isset($_REQUEST['agent']) ? $_REQUEST['agent'] : '';
	$pay_type = isset($_REQUEST['pay_type']) ? $_REQUEST['pay_type'] : '999';
	if($search)
	{
		$inquiry .=" and username like '%{$search}%'";
	}
	if($agent)
	{
		$inquiry .=" and agent='{$agent}'";
	}
	if($pay_type!='999')
	{
		$inquiry .=" and ispay='{$pay_type}'";
	}
	$atmlog=$xltm->SQL->GetRows("SELECT * FROM `atmlog` where id>0 $inquiry order by add_time desc");
	$agent_list=$xltm->SQL->GetRows("SELECT username FROM `user_agent` where active='yes' order by id");
	$xltm->tpls->LoadTemplate("./tmpfiles/pay_m.html",false);
	$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
	$xltm->tpls->MergeBlock('ag','array',$agent_list);
	$RecCnt =$xltm->tpls->MergeBlock('tr','array',$atmlog);
	$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
	$showPage=($RecCnt>0)?1:'';
	$xltm->tpls->show();
}

//����ɾ��
function remove()
{	
	global $xltm;
	$ids = $_REQUEST['ids'];
	//exit("delete from `atmlog` where id in ({$ids})");
	$xltm->SQL->Execute("delete from `atmlog` where id in ({$ids})");
	
	$xltm->showMsg("������","ɾ���ɹ���",true,"atm_log.php");
}

//��������¼
function del()
{	
	global $xltm;
	$id = $_REQUEST['id'];
	if(!isset($id) || !is_numeric($id))
	{
		$xltm->showMsg("������","��ָ����ȷ��IDֵ��",false,"back");
	}
	else
	{
		//�жϹɶ��Ƿ���Ȩ��ɾ��
		$atmlog = $xltm->SQL->GetRow("Select * from `atmlog` where id='{$id}'");
		if(!is_array($atmlog))
		{
			$xltm->showMsg("������","�Ҳ���Ҫ���˵�����¼��",false,"back");
		}
		else if($atmlog['ispay']=='1') //�����ô��
		{
			$xltm->showMsg("������","�ñ���������Ѿ����޷����ˣ�",false,"back");
		}
		else if($atmlog['ispay']=='-1') //�ѻ���
		{
			$xltm->showMsg("������","�ñ���������Ѿ����ˣ�����ظ�������",false,"back");
		}
		else
		{
			//�޸��û��Ŀ�������Լ������ʽ�
			$xltm->SQL->Execute("Update `user_users` set cash=cash+{$atmlog['money']},frozencash=frozencash-{$atmlog['money']} where username='".$atmlog['username']."'");
			//����״̬
			$xltm->SQL->Execute("update `atmlog` set ispay='-1',pay_time='{$xltm->sys_time}' where id='{$id}'");
			$xltm->showMsg("������","�������ɹ���",true,"atm_log.php");
		}
	}
}

//�Ѵ��
function pay()
{	
	global $xltm;
	$id = $_REQUEST['id'];
	if(!isset($id) || !is_numeric($id))
	{
		$xltm->showMsg("������","��ָ����ȷ��IDֵ��",false,"back");
	}
	else
	{
		$atmlog = $xltm->SQL->GetRow("Select * from `atmlog` where id='{$id}'");
		if(!is_array($atmlog))
		{
			$xltm->showMsg("������","�Ҳ���Ҫ��������¼��",false,"back");
		}
		else if($atmlog['ispay']=='1')
		{
			$xltm->showMsg("������","�ñ���������Ѿ���",false,"back");
		}
		else
		{
			$xltm->SQL->Execute("update `atmlog` set ispay='1',pay_time='{$xltm->sys_time}' where id='{$id}'");
			$xltm->SQL->Execute("Update `user_users` set frozencash=frozencash-{$atmlog['money']} where username='".$atmlog['username']."'");
			$xltm->showMsg("������","�������״̬�ɹ���",true,"atm_log.php");
		}
	}
}

function event_row($BlockName,&$CurrRec,$RecNum)
{
	if($CurrRec['ispay']=='1')
	{
		$CurrRec['status'] = '<font color=red>ok</font>';
		$CurrRec['disabled'] = ' disabled';
	}
	else if($CurrRec['ispay']=='-1')
	{
		$CurrRec['status'] = '<font color=gray>no</font>';
		$CurrRec['disabled'] = ' disabled';
	}
	else
	{
		$CurrRec['status'] = 'no';
		$CurrRec['disabled'] = '';
	}
}
?>