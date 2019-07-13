<?
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
$type=$_REQUEST['type'];
switch ($type)
{
	case 'Get_trust':
		include_once("./Lib/curl_http.php");
		$page=$_GET['page'];
		$PageSize=$_GET['PageSize'];
		$limitPage=($page==1)?0:($page*$PageSize)-$PageSize;
		$quiry="";
		$app=($_GET['get_app'])?$_GET['get_app']:'';
		if($app)$quiry .="and app='$app'";
		$trust=$xltm->SQL->GetRows("SELECT id,type,code FROM buy_trust where stock_trust_date='{$xltm->sys_date}' $quiry ORDER BY `stock_trust_time` DESC LIMIT $limitPage,$PageSize");
		$list='';
		foreach ($trust as $tr)
		{
			$list .=$tr['type'].$tr['code'].',';
		}
		$list=substr($list,0,-1);
		$curl=&new Curl_HTTP_Client();
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$list,"",10);
		$html_data=iconv("GB2312","UTF-8",$html_data);
		preg_match_all("/str_(.+?)\";/is",$html_data,$data);
		$messages=str_replace('="',",",$data[1]);
		$list="0,0|*|";
		$iv=0;
		foreach($messages as $l)
		{
			$N=split(',',$l,12);
			$N[0]=substr($N[0],2);
			$temp=$trust[$iv]['id'].",";
			for($i=0;$i<8;$i++)
			{
				$temp .=$N[$i].",";
			}
			$list .=substr($temp,0,-1)."|*|";
			$iv++;
		}
		echo $list;
		break;
	case 'Get_bull':
		include_once("./Lib/curl_http.php");
		$page=$_GET['page'];
		$PageSize=$_GET['PageSize'];
		$demo = $_GET['demo'];
		$where ='';
		if($demo!='-1')
		{
			$where = " and b.demo='{$demo}'";
		}
		$limitPage=($page==1)?0:($page*$PageSize)-$PageSize;
		$bull=$xltm->SQL->GetRows("SELECT a.id,a.stock_code,a.stock_type FROM buy_deal a left join user_users b on a.user=b.username where a.sell='0' {$where} ORDER BY a.bull_trust_time DESC LIMIT $limitPage,$PageSize");
		$list='';
		foreach ($bull as $tr)
		{
			$list .=$tr['stock_type'].$tr['stock_code'].',';
		}
		$list=substr($list,0,-1);
		$curl=&new Curl_HTTP_Client();
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$list,"",10);
		$html_data=iconv("GB2312","UTF-8",$html_data);
		preg_match_all("/str_(.+?)\";/is",$html_data,$data);
		$messages=str_replace('="',",",$data[1]);
		$list="0,0|*|";
		$iv=0;
		foreach($messages as $l)
		{
			$N=split(',',$l,12);
			$N[0]=substr($N[0],2);
			$temp=$bull[$iv]['id'].",";
			for($i=0;$i<8;$i++)
			{
				$temp .=$N[$i].",";
			}
			
			$temp .=$N[5]." a1,";
			$list .=substr($temp,0,-1)."|*|";
			$iv++;
		}
		echo $list;
		break;
	case 'Get_save':
		include_once("./Lib/curl_http.php");
		$page=$_GET['page'];
		$PageSize=$_GET['PageSize'];
		$demo = $_GET['demo'];
		$where = '';
		if($demo !='-1')
		{
			$where = " and b.demo='{$demo}'";
		}
		$limitPage=($page==1)?0:($page*$PageSize)-$PageSize;
		if($xltm->startTIME()) //开市
		{
			$query = "SELECT a.id,a.stock_code,a.stock_type FROM buy_deal a left join user_users b on a.user=b.username where a.sell='0' {$where} and a.bull_trust_date<'{$xltm->sys_date}' ORDER BY a.bull_trust_time DESC LIMIT $limitPage,$PageSize";
		}
		else
		{
			$query = "SELECT a.id,a.stock_code,a.stock_type FROM  buy_deal a left join user_users b on a.user=b.username where a.sell='0' {$where} and a.bull_trust_date<='{$xltm->sys_date}' ORDER BY a.bull_trust_time DESC LIMIT $limitPage,$PageSize";
		}
		$bull=$xltm->SQL->GetRows($query);
		$list='';
		foreach ($bull as $tr)
		{
			$list .=$tr['stock_type'].$tr['stock_code'].',';
		}
		$list=substr($list,0,-1);
		$curl=&new Curl_HTTP_Client();
		$html_data=$curl->fetch_url("http://hq.sinajs.cn/list=".$list,"",10);
		$html_data=iconv("GB2312","UTF-8",$html_data);
		preg_match_all("/str_(.+?)\";/is",$html_data,$data);
		$messages=str_replace('="',",",$data[1]);
		$list="0,0|*|";
		$iv=0;
		foreach($messages as $l)
		{
			$N=split(',',$l,12);
			$N[0]=substr($N[0],2);
			$temp=$bull[$iv]['id'].",";
			for($i=0;$i<8;$i++)
			{
				$temp .=$N[$i].",";
			}
			$list .=substr($temp,0,-1)."|*|";
			$iv++;
		}
		echo $list;
		break;
	case 'trust':
		if($_GET['act']=='CancelForm')
		{
			if($buyRow=$xltm->SQL->GetRow("SELECT * FROM buy_trust WHERE id='{$_GET['id']}' and app='1'"))
			{
				$xltm->SQL->Execute("UPDATE buy_trust set app='3' where id='{$_GET['id']}'");
				$xltm->SQL->Execute("UPDATE user_users set money=money+'{$buyRow['money']}' where username='{$buyRow['user']}'");
				$xltm->ShowPrompt('撤销成功!!',"",1);
			}else {
				$xltm->ShowPrompt('该委托交易已撤销或不存在!!',"",1);
			}
		}
		if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
		$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
		$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
		$PageSize = 25;
		$quiry="";
		$app = isset($_GET['get_app'])?$_GET['get_app']:'';
		$demo = isset($_GET['demo'])?$_GET['demo']:'0';
		if($app) $quiry .="and a.app='$app'";
		if($demo!='-1') $quiry .="and b.demo='$demo'";
		$CancelBut=($app==1)?1:'';
		$trust=$xltm->SQL->GetRows("SELECT a.* FROM buy_trust a left join user_users b on a.user=b.username where a.stock_trust_date='{$xltm->sys_date}' $quiry ORDER BY a.stock_trust_time DESC ");
		$xltm->tpls->LoadTemplate("./tmpfiles/account_trust.html");
		$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
		$RecCnt =$xltm->tpls->MergeBlock('tr','array',$trust);
		$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
		$showPage=($RecCnt>0)?1:'';
		$xltm->tpls->Show();
		break;
	case 'deal_bull':
		if($_GET['act']=='sell')
		{
			$saleID=$_GET['id'];
			$xltm->stock_sell($saleID);
		}
		if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
		$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
		$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
		$PageSize = 30;
		$demo = isset($_GET['demo'])?$_GET['demo']:'0';
		if($demo !='-1') $inquery=" and b.demo='$demo'";
		$bull=$xltm->SQL->GetRows("SELECT a.* FROM buy_deal a left join user_users b on a.user=b.username where a.sell='0' $inquery ORDER BY a.bull_trust_time DESC ");
		$xltm->tpls->LoadTemplate("./tmpfiles/account_deal_bull.html");
		$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
		$RecCnt =$xltm->tpls->MergeBlock('tr','array',$bull);
		$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
		$showPage=($RecCnt>0)?1:'';
		$xltm->tpls->Show();
		break;
	case 'bull_view':
		$v=$xltm->SQL->GetRow("SELECT * FROM buy_deal WHERE id='{$_GET['id']}'");
		$cost=$xltm->User->user_cost($v['user']);
		$price=$_GET['price'];
		$sell_money=$price*$v['bull_num']*100*$v['sell_cost'];
		$save_money=$price*$v['bull_num']*100*$v['save_day']*$v['save_cost'];
		$all_coost_money=$v['bull_cost_money']+$sell_money+$save_money+$v['dc_money'];
		if($v['buy_type']==1)
		{
			$differ=$price-$v['bull_price']; //差价
			$fwin=$price*($v['bull_num']*100)-$v['bull_money']-$all_coost_money;
		}else {
			$differ=$v['bull_price']-$price;
			$fwin=$v['bull_money']-($price*($v['bull_num']*100))-$all_coost_money;
		}
		$extent=($differ/$v['bull_price']); //涨跌幅
		$xltm->tpls->LoadTemplate("./tmpfiles/account_bull_view.html");
		$xltm->tpls->Show();
		break;
	case 'sell_view':
		$v=$xltm->SQL->GetRow("SELECT * FROM buy_deal WHERE id='{$_GET['id']}'");
		$cost=$xltm->User->user_cost($v['user']);
		$price=$v['sell_price'];
		if($v['buy_type']==1)
		{
			$differ=$price-$v['bull_price']; //差价
		}else {
			$differ=$v['bull_price']-$price;
		}
		$extent=($differ/$v['bull_price']); //涨跌幅
		$xltm->tpls->LoadTemplate("./tmpfiles/account_sell_view.html");
		$xltm->tpls->Show();
		break;
	case 'deal_sell':
		if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
		$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
		$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
		$demo = isset($_GET['demo'])?$_GET['demo']:'0';
		if($demo !='-1') $inquery .=" and b.demo='$demo'";
		$PageSize = 25;
		$sell=$xltm->SQL->GetRows("SELECT a.* FROM buy_deal a left join user_users b on a.user=b.username where a.sell='1' $inquery ORDER BY a.bull_trust_time DESC ");
		$xltm->tpls->LoadTemplate("./tmpfiles/account_deal_sell.html");
		$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
		$RecCnt =$xltm->tpls->MergeBlock('tr','array',$sell);
		$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
		$showPage=($RecCnt>0)?1:'';
		$xltm->tpls->Show();
		break;
	case 'deal_save':
		if($_GET['act']=='sell')
		{
			$saleID=$_GET['id'];
			$xltm->stock_sell($saleID);
		}
		if (!isset($_GET)) $_GET=&$HTTP_GET_VARS ;
		$PageNum =(isset($_GET['PageNum']))?$_GET['PageNum']:1;
		$RecCnt =(isset($_GET['RecCnt']))?intval($_GET['RecCnt']):'-1';
		$demo = isset($_GET['demo'])?$_GET['demo']:'0';
		if($demo !='-1') $inquery .=" and b.demo='$demo'";
		$PageSize = 25;
		if($xltm->startTIME()) //开市
		{
			$query = "SELECT a.* FROM buy_deal a left join user_users b on a.user=b.username where a.sell='0' $inquery and a.bull_trust_date<'{$xltm->sys_date}' ORDER BY a.bull_trust_time DESC ";
		}
		else
		{
			$query = "SELECT a.* FROM buy_deal a left join user_users b on a.user=b.username where a.sell='0' $inquery and a.bull_trust_date<='{$xltm->sys_date}' ORDER BY a.bull_trust_time DESC ";
		}
		$bull=$xltm->SQL->GetRows($query);
		$xltm->tpls->LoadTemplate("./tmpfiles/account_deal_save.html");
		$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
		$RecCnt =$xltm->tpls->MergeBlock('tr','array',$bull);
		$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
		$showPage=($RecCnt>0)?1:'';
		$xltm->tpls->Show();
		break;
	default:
		$xltm->tpls->LoadTemplate("./tmpfiles/account.html");
		$xltm->tpls->Show();
		break;
}
function trust_event($BlockName,&$CurrRec,$RecNum){
	$CurrRec['CancelBut']=($CurrRec['app']==1)?1:'';
	if($CurrRec['app']>1)
	$CurrRec['Success']=($CurrRec['app']==2)?'已交易':'已撤销';
	else
	$CurrRec['Success']='';
}
function sell_event($BlockName,&$CurrRec,$RecNum){
	$CurrRec['cost']=$CurrRec['bull_cost_money']+$CurrRec['sell_cost_money']+$CurrRec['save_money'];
	$CurrRec['member_yk']=$CurrRec['gain']-$CurrRec['bull_cost_money']-$CurrRec['sell_cost_money']-$CurrRec['dc_money']-$CurrRec['save_money'];
	
	$CurrRec['agent_gain']=($CurrRec['agent_gain']>0)?0-$CurrRec['agent_gain']:-$CurrRec['agent_gain'];
	
	$CurrRec['agent_yk']=$CurrRec['agent_gain']+$CurrRec['agent_bull_money']+$CurrRec['agent_sell_money']+$CurrRec['dc_money']+$CurrRec['agent_save_money'];
}
function bull_event($BlockName,&$CurrRec,$RecNum){
	global $xltm;
	$CurrRec['save_day']=$xltm->save_day($xltm->sys_date,$CurrRec['bull_trust_date']);
}
?>
