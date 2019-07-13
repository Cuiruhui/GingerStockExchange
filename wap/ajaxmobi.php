<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
require_once(ROOT."/Lib/curl_http.php");

if($xltm->user_info['username']=='')
{
	exit('false|���ȵ�¼��');
}

$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
switch(strtolower($type))
{
	case 'stocklist':
		GetStockList();
		break;
	case 'getstockname':
		GetStockName();
		break;
	case 'quote':
		GetQuote();
		break;
}

function GetQuote()
{
	global $xltm;
	$type = $_GET['stocktype'];
	$stockcode = $_GET['stockcode'];

	$curl=&new Curl_HTTP_Client();
	$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$type.$stockcode,"",5);
	$html_data=iconv("GB2312","UTF-8",$html_data);
	if($html_data)
	{
		preg_match("/str_(.+?)\";/is",$html_data,$data);
		$quote=str_replace('="',",",$data[1]);
		print($quote);
	}
}


function GetStockName()
{
	global $xltm;
	$stockcode = $_GET['stockcode'];
	if($stockcode && is_numeric($stockcode) && strlen($stockcode)==6)
	{
		if($row=$xltm->SQL->GetRow("select * from `stock_code` where code='{$stockcode}'"))
		{

			$dc_num		= $row["dc"];
			$dc_num1	= 0;
			//=======================================2012-4-20 ���ˣ������ǵ� �Զ����� �����  ��ʼ ====================

			include_once("./Lib/curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$row['type'].$row['code'],"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);

			preg_match("/\"(.+?)\";/is",$html_data,$data);
			$Net=split(',',$data[1]);


			$chajia=($Net[4]-$Net[2])/$Net[2]*100;
			$chajia1 = ($Net[5]-$Net[2])/$Net[2]*100;
			$chajia = abs($chajia);
			$chajia1 = abs($chajia1);
			if ($chajia1 > $chajia ) $chajia = $chajia1;

			// $chajia  ��������ǵ��ٷֱȡ�
			for( $d = 5; $d <=9; $d++){
				if ($chajia >= $d ){		//�ǵ��ʴ��� ���õİٷֱȣ��Ͷ�ȡ��Ӧ�ĵ����ֵ��
					$dc_num1 = $xltm->config['dc'.$d];
				}
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//�ɽ��������ǧ�� �ĵ���ʡ�
			if ( $Net[9] > 50000000){
				$dc_num1 = $xltm->config['dc_wan'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//�ɽ������8ǧ�� �ĵ���ʡ�
			if ( $Net[9] > 80000000){
				$dc_num1 = $xltm->config['dc_wan2'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//�ɽ������1.2�� �ĵ���ʡ�
			if ( $Net[9] > 120000000){
				$dc_num1 = $xltm->config['dc_wan3'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//002��ͷ300��ͷ�ĵ���ʡ�
			if ( substr($row['code'], 0, 3) == "002" or substr($row['code'], 0, 3) == "300" ){
				$dc_num1 = $xltm->config['dc_tou'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//��Ʊ�ɼ۵���5Ԫ�Ĺ�Ʊ�����
			if ( $Net[3] < 5 ){
				$dc_num1 = $xltm->config['dc_di'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			//=======================================2012-4-20 ���ˣ������ǵ� �Զ����� �����  ���� ====================


			//0:���� 1:���� 2:���� 3:ͣ�� 4:���� 5:���� 6:���� 7:������ 8:����� 9:���
			exit($row['type'].','.$row['code'].','.$row['name'].','.$row['stop_pai'].','.$row['inf'].','.$row['can_bull'].','.$row['can_sell'].','.$row['can_bull_up'].','.$row['can_bull_down'].','.$dc_num);
		}
	}
	else
	{
		exit('--');
	}
}


function GetStockList()
{
	global $xltm;
	
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 15;
	$where ="";
	$total = 0; //�ܼ�¼��

	//ͳ���ܼ�¼��
	$query = "select count(id) as totalrecord from `stock_code` where 1=1 {$where} order by code asc";
	//exit($query);
	if($row=$xltm->SQL->GetRow($query))
	{
		if(is_numeric($row['totalrecord']) && $row['totalrecord']>0)
		{
			$total = $row['totalrecord'];
		}
	}
	//��ҳ����
	$res = $total.'^';
	$query = "select * from `stock_code` where 1=1 {$where} order by code asc limit ".($page-1)*$pagesize.",".$pagesize;
	if($rows=$xltm->SQL->GetRows($query))
	{
		$total1 = count($rows); //���ι��м�¼��
		$res.=$total1;
		$r = '';
		
		
		foreach($rows as $row)
		{	
			$curl='';
			$html_data='';
			$Net='';
			$data='';
			$StockList='';
			$StockList = $row['type'].$row['code'];
			require_once("./Lib/curl_http.php");
			$curl=&new Curl_HTTP_Client();
			$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$StockList,"",10);
			$html_data=iconv("GB2312","UTF-8",$html_data);
			
			if($html_data)
			{
				preg_match_all("/str_(.+?)\";/is",$html_data,$data);
				$messages=str_replace('="',",",$data[1]);
			}
			
			$i=0;
			foreach($messages as $l)
			{
				$N=split(',',$l);
				$xianjia=$N[4]>0?$N[4]:0;
				$xianjia1=round($xianjia,2);
				$zdf = round(($N[4]-$N[3])*100/$N[4],2);
				//0���� 1���� 2�ּ� 3���ǵ���
				$r .= ($r==''?'':'$').$row['code'].'|'.$row['name'].'|'.$xianjia1.'|'.$zdf;
			}
		}
		$res .= '^' . $r;
	}
	else
	{
		$res.='0^';
	}
	print $res;
}

?>