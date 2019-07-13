<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");
require_once(dirname(__FILE__)."/Lib/curl_http.php");

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
	case 'getstockxx':
		GetStockxx();
		break;
	case 'getfav':
		GetUserFavStock();
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
		exit($quote);
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
	$pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 20;
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
				$r .= ($r==''?'':'$').$row['code'].'|'.$row['name'].'|'.$xianjia1.'|'.$zdf.'|'.$N[2].'|'.$N[5];;
			}
		}
		$res .= '^' . $r;
	}
	else
	{
		$res.='0^';
	}
	exit($res);
}

function GetStockxx()
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
			//10 ��5-- ��1--  ��5 -- ��1
			exit($row['type'].','.$row['code'].','.$row['name'].','.$row['stop_pai'].','.$row['inf'].','.$row['can_bull'].','.$row['can_sell'].','.$row['can_bull_up'].','.$row['can_bull_down'].','.$dc_num.','.$Net[30].','.$Net[29].','.$Net[28].','.$Net[27].','.$Net[26].','.$Net[25].','.$Net[24].','.$Net[23].','.$Net[22].','.$Net[21].','.$Net[20].','.$Net[19].','.$Net[18].','.$Net[17].','.$Net[16].','.$Net[15].','.$Net[14].','.$Net[13].','.$Net[12].','.$Net[11].','.$Net[10].','.$Net[3]);
		}
	}
	else
	{
		exit('--');
	}
	
}

function GetUserFavStock()
{
	global $xltm;
	$IsOpen = $xltm->startTIME();
	$StockList = '';
	$FavIDList = '';
	$html = '<table width="99%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC" class="mybox">';
    $html .= '<tr align="center"><th width="40">ѡ��</th><th>����</th><th>����</th><th>���¼�</th><th>�ɽ���(��)</th><th>�ǵ�</th><th>�q��%</th><th>���</th><th>����</th><th>���̼�</th><th>��߼�</th><th>��ͼ�</th><th>���ռ�</th><th>ʱ��</th><th width="70">����</th></tr>';
	$user = $xltm->user_info['username'];
	if($user)
	{
		if($rows = $xltm->SQL->GetRows("Select * from `user_stock` where user='{$user}' order by stock_code asc"))
		{
			foreach($rows as $row)
			{

				$StockList .= $StockList=='' ? '' : ',';
				$FavIDList .= $FavIDList=='' ? '' : ',';
				$StockList .= $row['type'].$row['stock_code'];
				$FavIDList .= $row['id'];
			}
			//��ȡ����
			if($StockList)
			{
				$curl=&new Curl_HTTP_Client();
				$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$StockList,"",5);
				$html_data=iconv("GB2312","UTF-8",$html_data);
				$data='';
				if($html_data)
				{
					preg_match_all("/str_(.+?)\";/is",$html_data,$data);
					$messages=str_replace('="',",",$data[1]);
				}
				$list = '';
				$i=0;
				foreach($messages as $l)
				{
					$N=split(',',$l);
					$N[0]=substr($N[0],2);
					$cj = round($N[4]-$N[3],2);
					$cjl = ceil($N[9]/100); //�ɽ���(��)
					$zdf = round(($N[4]-$N[3])*100/$N[4],2); //�ǵ���
					$color = $cj>=0 ? '#FF0000' : '#008000';
					$bgcolor = (($i+1)%2==0) ? '#F5F5F5' : '#ffffff';
					if($N[4]==0) //ͣ��
					{
						$color = '#000000';
						$N[4]= '--';
						$cjl = '--';
						$cj = '--';
						$zdf = '--';
						$N[7]='--';
						$N[8]='--';
						$N[2]='--';
						$N[5]='--';
						$N[6]='--';
					}
					$html .= '<tr align="center" bgcolor="'.$bgcolor.'" onMouseOver="this.style.background=\'#FDF0D7\';" onMouseOut="this.style.background=\''.$bgcolor.'\';">';
          			$html .= '<td align="center"><input type="checkbox" id="check_id'.$FavIDList[$i].'" name="check_name" value="'.$N[0].'" style="cursor:hand"></td>';
          			$html .= "<td align=\"center\"><a href=\"javascript:ShowKline('".$N[0]."')\">".$N[0]."</a></td>"; //����
          			$html .= "<td align=\"center\"><a href=\"javascript:ShowKline('".$N[0]."')\">".$N[1]."</a></td>"; //����
          			$html .= '<td style="color:'.$color.'">'.$N[4].'</td> '; //��ǰ��
          			$html .= '<td>'.$cjl.'</td> '; //�ɽ���
          			$html .= '<td style="color:'.$color.'">'.($cj>0 ? '+'.$cj : $cj).'</td> '; //�ǵ�
          			$html .= '<td style="color:'.$color.'">'.($cj>0 ? '+' : '').$zdf.'</td> '; //�ǵ���
          			$html .= '<td>'.$N[7].'</td>'; //���
          			$html .= '<td>'.$N[8].'</td>'; //����
          			$html .= '<td>'.$N[2].'</td> '; //���̼�
          			$html .= '<td style="color:#FF0000">'.$N[5].'</td> '; //��߼�
         			$html .= '<td style="color:#008000">'.$N[6].'</td> '; //��ͼ�
          			$html .= '<td style="color:#000000">'.$N[3].'</td> '; //���ռ�
          			$html .= '<td align="center">'.$N[32].'</td> '; //ʱ��
         			$html .= ' <td align="center"><input type="button" name="btn_order" class="button3" value="�µ�"';
					if($IsOpen==0)
					{
						$html.=' disabled';
					}
					else
					{
						$html.=' onClick="location.href=\'order.php?do=order&stockcode='.$N[0].'\';"';
					}
					$html.=' /></td> ';
        			$html .= '</tr> ';
					$i++;
				}
			}
		}
		else
		{
			$html .= '<tr align="center" bgcolor="#ffffff"><td height="50" align="center" colspan="15">�����ղصĹ�Ʊ</td></tr>';
		}
	}
	else
	{
		$html .= '<tr align="center" bgcolor="#ffffff"><td height="50" align="center" colspan="15">�����ղصĹ�Ʊ</td></tr>';
	}
	$html .='</table>';
	exit($html);
}


?>