<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(dirname(__FILE__)."/Lib/xltm.class.php");
require_once(dirname(__FILE__)."/Lib/curl_http.php");

if($xltm->user_info['username']=='')
{
	exit('false|请先登录！');
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
			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  开始 ====================

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

			// $chajia  这个就是涨跌百分比。
			for( $d = 5; $d <=9; $d++){
				if ($chajia >= $d ){		//涨跌率大于 设置的百分比，就读取相应的点差率值。
					$dc_num1 = $xltm->config['dc'.$d];
				}
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//成交额大于五千万 的点差率。
			if ( $Net[9] > 50000000){
				$dc_num1 = $xltm->config['dc_wan'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于8千万 的点差率。
			if ( $Net[9] > 80000000){
				$dc_num1 = $xltm->config['dc_wan2'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于1.2亿 的点差率。
			if ( $Net[9] > 120000000){
				$dc_num1 = $xltm->config['dc_wan3'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//002开头300开头的点差率。
			if ( substr($row['code'], 0, 3) == "002" or substr($row['code'], 0, 3) == "300" ){
				$dc_num1 = $xltm->config['dc_tou'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//股票股价低于5元的股票点差率
			if ( $Net[3] < 5 ){
				$dc_num1 = $xltm->config['dc_di'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  结束 ====================


			//0:类型 1:代码 2:名称 3:停牌 4:关盘 5:可买 6:可卖 7:可买升 8:可买跌 9:点差
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
	$total = 0; //总记录数

	//统计总记录数
	$query = "select count(id) as totalrecord from `stock_code` where 1=1 {$where} order by code asc";
	//exit($query);
	if($row=$xltm->SQL->GetRow($query))
	{
		if(is_numeric($row['totalrecord']) && $row['totalrecord']>0)
		{
			$total = $row['totalrecord'];
		}
	}
	//分页设置
	$res = $total.'^';
	$query = "select * from `stock_code` where 1=1 {$where} order by code asc limit ".($page-1)*$pagesize.",".$pagesize;
	if($rows=$xltm->SQL->GetRows($query))
	{
		$total1 = count($rows); //本次共有记录数
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
				//0代码 1名称 2现价 3现涨跌幅
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
			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  开始 ====================

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

			// $chajia  这个就是涨跌百分比。
			for( $d = 5; $d <=9; $d++){
				if ($chajia >= $d ){		//涨跌率大于 设置的百分比，就读取相应的点差率值。
					$dc_num1 = $xltm->config['dc'.$d];
				}
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//成交额大于五千万 的点差率。
			if ( $Net[9] > 50000000){
				$dc_num1 = $xltm->config['dc_wan'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于8千万 的点差率。
			if ( $Net[9] > 80000000){
				$dc_num1 = $xltm->config['dc_wan2'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;
			//成交额大于1.2亿 的点差率。
			if ( $Net[9] > 120000000){
				$dc_num1 = $xltm->config['dc_wan3'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//002开头300开头的点差率。
			if ( substr($row['code'], 0, 3) == "002" or substr($row['code'], 0, 3) == "300" ){
				$dc_num1 = $xltm->config['dc_tou'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;


			//股票股价低于5元的股票点差率
			if ( $Net[3] < 5 ){
				$dc_num1 = $xltm->config['dc_di'];
			}
			if ( $dc_num1 > $dc_num) $dc_num = $dc_num1;

			//=======================================2012-4-20 李兴，增加涨跌 自动计算 点差率  结束 ====================


			//0:类型 1:代码 2:名称 3:停牌 4:关盘 5:可买 6:可卖 7:可买升 8:可买跌 9:点差
			//10 卖5-- 卖1--  买5 -- 买1
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
    $html .= '<tr align="center"><th width="40">选择</th><th>代码</th><th>名称</th><th>最新价</th><th>成交量(手)</th><th>涨跌</th><th>漲跌%</th><th>买价</th><th>卖价</th><th>开盘价</th><th>最高价</th><th>最低价</th><th>昨收价</th><th>时间</th><th width="70">操作</th></tr>';
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
			//获取行情
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
					$cjl = ceil($N[9]/100); //成交量(手)
					$zdf = round(($N[4]-$N[3])*100/$N[4],2); //涨跌幅
					$color = $cj>=0 ? '#FF0000' : '#008000';
					$bgcolor = (($i+1)%2==0) ? '#F5F5F5' : '#ffffff';
					if($N[4]==0) //停牌
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
          			$html .= "<td align=\"center\"><a href=\"javascript:ShowKline('".$N[0]."')\">".$N[0]."</a></td>"; //代码
          			$html .= "<td align=\"center\"><a href=\"javascript:ShowKline('".$N[0]."')\">".$N[1]."</a></td>"; //名称
          			$html .= '<td style="color:'.$color.'">'.$N[4].'</td> '; //当前价
          			$html .= '<td>'.$cjl.'</td> '; //成交量
          			$html .= '<td style="color:'.$color.'">'.($cj>0 ? '+'.$cj : $cj).'</td> '; //涨跌
          			$html .= '<td style="color:'.$color.'">'.($cj>0 ? '+' : '').$zdf.'</td> '; //涨跌幅
          			$html .= '<td>'.$N[7].'</td>'; //买价
          			$html .= '<td>'.$N[8].'</td>'; //卖价
          			$html .= '<td>'.$N[2].'</td> '; //开盘价
          			$html .= '<td style="color:#FF0000">'.$N[5].'</td> '; //最高价
         			$html .= '<td style="color:#008000">'.$N[6].'</td> '; //最低价
          			$html .= '<td style="color:#000000">'.$N[3].'</td> '; //昨收价
          			$html .= '<td align="center">'.$N[32].'</td> '; //时间
         			$html .= ' <td align="center"><input type="button" name="btn_order" class="button3" value="下单"';
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
			$html .= '<tr align="center" bgcolor="#ffffff"><td height="50" align="center" colspan="15">暂无收藏的股票</td></tr>';
		}
	}
	else
	{
		$html .= '<tr align="center" bgcolor="#ffffff"><td height="50" align="center" colspan="15">暂无收藏的股票</td></tr>';
	}
	$html .='</table>';
	exit($html);
}


?>