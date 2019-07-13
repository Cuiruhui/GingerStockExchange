<?php
session_start();
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
require_once(ROOT."/Lib/curl_http.php");

if($xltm->user_info['username']=='')
{
	exit('false|请先登录！');
}

$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
switch(strtolower($type))
{
	case 'cancel_trust':
		cancel_trust();
		break;
	case 'checkstocksishas':
		CheckStocksIsHas();
		break;
	case 'cancash':
		GetUserCanCash();
		break;
	case 'quote':
		GetQuote();
		break;
	case 'price':
		GetPrice();
		break;
	case 'getstockname':
		GetStockName();
		break;
	case 'getfav':
		GetUserFavStock();
		break;
	case 'getbk':
		GetBkList();
		break;
	case 'getbkstock':
		GetBkStockList();
		break;
	case 'delfav':
		DelUserFav();
		break;
	case 'getdeal':
		DealList();
		break;
	case 'stocklist':
		GetStockList();
		break;
	case 'addstock':
		AddStock();
		break;

	case 'message':
		$msgnum = 0;
		if($row=$xltm->SQL->GetRow("select count(id) as msgnum from `message` where tousername='".$xltm->user_info['username']."' and isread='0'"))
		{
			if(is_numeric($row['msgnum']) && $row['msgnum']>0)
			{
				$msgnum = $row['msgnum'];
			}
		}
		echo $msgnum;
		break;
		
	case 'money':
		$cancash = round($xltm->AvailableCash(),2);
		echo $cancash;
		break;
		
	case 'baocang':
		$open = $xltm->startTIME();
		$num = 0;
		if($open)
		{
			$num = $xltm->baocang();
		}
		echo $num;
		break;
}	

function cancel_trust() //撤销委托单
{
	global $xltm;
	$id=isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
	if($row=$xltm->SQL->GetRow("SELECT * FROM `buy_trust` WHERE user='{$xltm->user_info['username']}' and id='{$id}' and app='1'"))
	{
		$xltm->SQL->Execute("UPDATE `buy_trust` set app='3',stock_deal_time='{$xltm->sys_time}' where id='{$row['id']}'");
		//判断是买入还是卖出
		if($row['trust_type']== 1) //买入
		{
			$xltm->SQL->Execute("UPDATE `user_users` set money=money+'{$row['money']}' where username='{$row['user']}'");
		}
		else
		{
			$xltm->SQL->Execute("UPDATE `buy_deal` set can_sell_num= can_sell_num+{$row['num']} where id='{$row['deal_id']}'");
		}
		exit("true|撤单成功！");
	}else {
		exit("false|该委托交易已经撤销过了！");
	}
}

function AddStock()
{
	global $xltm;
	$stockcode = $xltm->Security->is_stockcode($_GET['stockcode']) ? $_GET['stockcode'] : '600000';
	//判断是否已经存在相同的收藏
	if($row = $xltm->SQL->GetRow("select * from `user_stock` where stock_code='{$stockcode}' and user='{$xltm->user_info['username']}'"))
	{
		exit("已经收藏了股票代码：".$stockcode);
	}
	if($row = $xltm->SQL->GetRow("Select * from `stock_code` where code='{$stockcode}'"))
	{
		$qary = array(
			'user'=>$xltm->user_info['username'],
			'freetype'=>'1',
			'type'=>$row['type'],
			'stock_code'=>$row['code'],
			'stock_name'=>$row['name'],
			'add_time'=>$xltm->sys_time
		);
		$query = $xltm->array2str($qary);
		$xltm->SQL->Execute("insert into `user_stock` set {$query}");
		exit('收藏自选股成功！');
	}
	else
	{
		exit("股票代码不存在！");
	}
}

function CheckStocksIsHas()
{	
	global $xltm;
	$stockcode = $xltm->Security->is_stockcode($_GET['stockcode']) ? $_GET['stockcode'] : '600000';
	if($row = $xltm->SQL->GetRow("Select * from `stock_code` where code='{$stockcode}'"))
	{
		exit("true");
	}
	else
	{
		exit("系统数据中不存在您输入的股票代码".$stockcode."，请通知我们的客服补全数据！");
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

function getBkList()
{
	global $xltm;
	$html = '<select id="ddlstocksbktypename" onchange="ChangeStocks(this.value)">';
	$html .= '<option value="">请选择</option>';
	$class = $_GET['class'];
	if($class)
	{
		$class = iconv('GB2312','UTF-8',$class);
		if($rows=$xltm->SQL->GetRows("select * from `stock_bk` where class='{$class}' order by id asc"))
		{
			foreach ($rows as $row)
			{
				$html .= '<option value="'.$row['bk_name'].'">'.$row['bk_name'].'</option>';
			}
		}
	}
	$html .= '</select>';
	exit($html);
}

function GetBkStockList()
{
	global $xltm;
	$html = '<select id="ddlstocksno" onchange="SetStocksno(this.value)">';
	$html .= '<option value="">请选择</option>';
	$bk_name = $_GET['bk_name'];
	if($bk_name)
	{
		$bk_name = iconv('GB2312','UTF-8',$bk_name);
		if($rows=$xltm->SQL->GetRows("select * from `stock_code` where FIND_IN_SET('{$bk_name}',bk_list)>0 order by code asc"))
		{
			foreach ($rows as $row)
			{
				$html .= '<option value="'.$row['code'].'">'.$row['code'].' / '.$row['name'].'</option>';
			}
		}
	}
	$html .= '</select>';
	exit($html);
}

function DelUserFav()
{
	global $xltm;
	$user = $xltm->user_info['username'];
	if($user)
	{
		$stockcode=$_GET['stockcode'];
		if(!$stockcode)
		{
			exit("请指定要删除的自定义收藏股票代码！");
		}
		$xltm->SQL->Execute("delete from `user_stock` where user='{$user}' and stock_code in ({$stockcode})");
		exit('true');
	}
	else
	{
		exit("请先登录系统！");
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

function GetPrice()
{
	global $xltm;
	$code = $_REQUEST['stockcode'];
	if(!$code) return '';
	$curl=&new Curl_HTTP_Client();
	$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$code,"",5);
	$html_data=iconv("GB2312","UTF-8",$html_data);
	$price = "";
	if($html_data)
	{
		preg_match_all("/str_(.+?)\";/is",$html_data,$data);
		$quotes=str_replace('="',",",$data[1]);
		foreach($quotes as $quote)
		{
			$q = explode(',',$quote);
			$price .= (empty($price)?'':',').$q[4];
		}
	}
	echo $price;
	exit();
}

function DealList()
{
	global $xltm;
	if($_COOKIE['deal_list'])
	{
		$curl=&new Curl_HTTP_Client();
		$codelist=$_COOKIE['deal_list'];
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$codelist,"",10);
		$html_data=iconv("GB2312","UTF-8",$html_data);
		if($html_data)
		{
			preg_match_all("/str_(.+?)\";/is",$html_data,$data);
			$messages=str_replace('="',",",$data[1]);
		}
		$list=time()."-,-|";
		foreach($messages as $l)
		{
			$N=split(',',$l,12);
			$N[0]=substr($N[0],2);
			$list .=$N[0].','.$N[4].','.$N[7];
			$list .="|";
		}
		$list=substr($list,0,-1);
		print $list;
	}
}

function GetStockList()
{
	global $xltm;
	$stype = isset($_GET['stype']) ? $_GET['stype'] : '';
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$pagesize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 30;
	$stockcode = $_GET['stockcode'];
	$bkname = $_GET['bkname'];
	if($bkname) $bkname = iconv("GB2312","UTF-8",$bkname);
	
	$where ="";
	$total = 0; //总记录数
	if($stype=='code')
	{
		$where .=" and code like '%{$stockcode}%'";
	}
	else if($stype=='stop') //停牌股
	{
		$where .=" and stop_pai='1'";
	}
	else if($stype=='full') //满仓股
	{
		$where .=" and (inf='1' or can_bull='0' or can_sell='0' or can_bull_up='0' or can_bull_down='0')";
	}
	else if($stype=='bk') //板块股
	{
		$where .=" and FIND_IN_SET('{$bkname}',bk_list)>0";
	}
	else if($stype=='empty')
	{
		$where .=" and inf='0' and can_bull='1'";
	}
	
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
			//0代码 1名称 2拼音 3关盘 4停牌 5买入 6卖出 7点差 8买升 9买跌 10板块
			$r .= ($r==''?'':'$').$row['code'].'|'.$row['name'].'|'.$row['pinyin'].'|'.$row['inf'].'|'.$row['stop_pai'].'|'.$row['can_bull'].'|'.$row['can_sell'].'|'.$row['dc'].'|'.$row['can_bull_up'].'|'.$row['can_bull_down'].'|'.$row['bk_list'];
		}
		$res .= '^' . $r;
	}
	else
	{
		$res.='0^';
	}
	print $res;
}

function GetUserCanCash()
{
	global $xltm;
	$cancash = round($xltm->AvailableCash(),2);
	if($cancash<$xltm->config['lower_cash'] && $xltm->user_info['demo']=='0') //只对正式会员提示
	{
		print 'lower您当前可用保证金：'.$cancash.' 低于系统设定的 <font color=red><b>￥'.$xltm->config['lower_cash'].'</b></font>！为了保证您的交易能正常进行，请及时充值！<br /><br />是否现在去充值？';
	}
	else
	{
		print 'ok';
	}
	exit();
}

?>