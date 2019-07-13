<?php
define('Load_Info', 1);
session_start();

include_once("./Lib/xltm.class.php");
$type=$_REQUEST['type'];
switch ($type)
{
	case 'add':
		$stock_type=$xltm->SQL->GetRows("SELECT * FROM `stock_bk`");
		$xltm->tpls->LoadTemplate("./tmpfiles/stock_add.html");
		$xltm->tpls->MergeBlock('bk','array',$stock_type);
		$xltm->tpls->Show();
		break;
	case 'edit':
		$Edit=$xltm->SQL->GetRow("SELECT * FROM `stock_code` WHERE id='{$_GET['id']}'");
		$bk_list=$xltm->SQL->GetRows("SELECT * FROM `stock_bk`");
		$bks = $Edit['bk_list'];
		$bkary = array();
		if($bks)
		{
			$bks = substr($bks,1);
			$bks = explode(',',$bks);
			foreach($bks as $k)
			{
				$bkary[] = array('name'=>$k);
			}
		}
		$xltm->tpls->LoadTemplate("./tmpfiles/stock_edit.html");
		$xltm->tpls->MergeBlock('bk','array',$bk_list);
		$xltm->tpls->MergeBlock('bk1','array',$bkary);
		$xltm->tpls->Show();
		break;
	case 'up':
		$stock=$xltm->SQL->GetRow("SELECT can_bull_up FROM stock_code WHERE id='{$_GET['id']}'");
		if($stock['can_bull_up']=='1')
		{
			$up='0';
			$out='<b>禁买升</b>';
		}else {
			$up='1';
			$out='可买升';
		}
		$xltm->SQL->Execute("UPDATE stock_code set can_bull_up='$up' where id='{$_GET['id']}'");
		echo $out;
		break;
	case 'down':
		$stock=$xltm->SQL->GetRow("SELECT can_bull_down FROM stock_code WHERE id='{$_GET['id']}'");
		if($stock['can_bull_down']=='1')
		{
			$up='0';
			$out='<b>禁买跌</b>';
		}else {
			$up='1';
			$out='可买跌';
		}
		$xltm->SQL->Execute("UPDATE stock_code set can_bull_down='$up' where id='{$_GET['id']}'");
		echo $out;
		break;
	case 'bull':
		$stock=$xltm->SQL->GetRow("SELECT can_bull FROM stock_code WHERE id='{$_GET['id']}'");
		if($stock['can_bull']=='1')
		{
			$up='0';
			$out='<b>禁买</b>';
		}else {
			$up='1';
			$out='可买';
		}
		$xltm->SQL->Execute("UPDATE stock_code set can_bull='$up' where id='{$_GET['id']}'");
		echo $out;
		break;
	case 'sell':
		$stock=$xltm->SQL->GetRow("SELECT can_sell FROM stock_code WHERE id='{$_GET['id']}'");
		if($stock['can_sell']=='1')
		{
			$up='0';
			$out='<b>禁卖</b>';
		}else {
			$up='1';
			$out='可卖';
		}
		$xltm->SQL->Execute("UPDATE stock_code set can_sell='$up' where id='{$_GET['id']}'");
		echo $out;
		break;
	case 'stop':
		$stock=$xltm->SQL->GetRow("SELECT stop_pai FROM stock_code WHERE id='{$_GET['id']}'");
		$stop_date=$xltm->sys_date;
		if($stock['stop_pai']=='1')
		{
			$up='0';
			$out='无停';
		}else {
			$up='1';
			$out='<b>停牌</b>';
		}
		$xltm->SQL->Execute("UPDATE stock_code set stop_pai='$up',stop_date='$stop_date' where id='{$_GET['id']}'");
		echo $out;
		break;
	case 'off':
		$stock=$xltm->SQL->GetRow("SELECT inf FROM stock_code WHERE id='{$_GET['id']}'");
		if($stock['inf']=='1')
		{
			$up='0';
			$out='关盘';
		}else {
			$up='1';
			$out='<b>开盘</b>';
		}
		$xltm->SQL->Execute("UPDATE stock_code set inf='$up' where id='{$_GET['id']}'");
		echo $out;
		break;
	default:
		//print_r($_POST['dcID']);
		if($_POST['dcID'])
		{
			$in="";
			foreach ($_POST['dcID'] as $d)
			{
				$in .="'".$d."',";
			}
			$in .=substr($in,0,-1);
			$dc_num=isset($_REQUEST['dc_num']) ? intval($_REQUEST['dc_num']) : 0;
			$xltm->SQL->Execute("UPDATE `stock_code` set dc='{$dc_num}' where `id` IN ($in)");
		}
		if($type=='all')
		{
			$xltm->SQL->Execute("UPDATE `stock_code` set dc='0' where `id`>0");
		}//if()
		if($type=='AddFrom')
		{
			if($row=$xltm->SQL->GetRow("select * from `stock_code` where code='{$_POST['code']}'"))
			{
				$xltm->showMsg('股票代码管理',"已存在相同的股票代码：{$_POST['code']}！",false,'back');
			}
			else
			{
				$xltm->SQL->Execute("insert into `stock_code` set code='{$_POST['code']}',name='{$_POST['name']}',type='{$_POST['types']}',bk_list='{$_POST['bk_list']}',pinyin='{$_POST['pinyin']}',stop_date='0000-00-00'");
				$xltm->showMsg('股票代码管理','新增股票代码成功！',true,'stock.php');
			}
		}
		if($type=='del')
		{
			$xltm->SQL->Execute("DELETE FROM stock_code where id='{$_GET['id']}'");
		}
		if($type=='EditFrom')
		{
			$qary = array(
				'code'=>$_POST['code'],
				'name'=>$_POST['name'],
				'type'=>$_POST['types'],
				'pinyin'=>$_POST['pinyin'],
				'can_bull'=>$_POST['can_bull'],
				'can_bull_up'=>$_POST['can_bull_up'],
				'can_bull_down'=>$_POST['can_bull_down'],
				'can_sell'=>$_POST['can_sell'],
				'stop_pai'=>$_POST['stop_pai'],
				'stop_date'=>$_POST['stop_date'],
				'dc'=>$_POST['dc'],
				'bk_list'=>$_POST['bk_list']
			);
			$query = $xltm->array2str($qary);
			$xltm->SQL->Execute("UPDATE `stock_code` set {$query} where id='{$_POST['id']}'");
			$xltm->showMsg('股票代码管理','修改股票信息成功！',true,'stock.php');
		}
		$inquiry="";
		$search=isset($_REQUEST['search'])?$_REQUEST['search']:'';
		if($search)
		$inquiry .=" and (code='$search' or name='$search' or pinyin='$search')";
		$Order=isset($_GET['Order'])?$_GET['Order']:'code';
		$By=isset($_GET['By'])?$_GET['By']:'asc';
		$hy=isset($_REQUEST['hy'])?$_REQUEST['hy']:'';
		$updown = isset($_REQUEST['updown']) ? $_REQUEST['updown'] : 'all';
		$stop_pai=isset($_REQUEST['stop_pai'])?$_REQUEST['stop_pai']:0;
		if($stop_pai==1)
		$inquiry .=" and stop_pai=1";
		if($stop_pai==2)
		$inquiry .=" and stop_pai=0";
		$se_bu=isset($_REQUEST['se_bu'])?$_REQUEST['se_bu']:0;
		if($se_bu==1)
		$inquiry .=" and can_bull=0";
		if($se_bu==2)
		$inquiry .=" and can_sell=0";
		if($se_bu==3)
		$inquiry .=" and can_bull=0 and can_sell=0";
		if($updown!='all')
		{
			if($updown == 'canup')
			{
				$inquiry .=" and can_bull_up='1'";
			}
			else if ($updown == 'candown')
			{
				$inquiry .= " and can_bull_down='1'";
			}
			else if ($updown == 'notup')
			{
				$inquiry .= " and can_bull_up='0'";
			}
			else if ($updown == 'notdown')
			{
				$inquiry .= " and can_bull_down='0'";
			}
		}
		//分页设置
		if (!isset($_REQUEST)) $_REQUEST=&$HTTP_GET_VARS ;
		$PageNum =(isset($_REQUEST['PageNum']))?$_REQUEST['PageNum']:1;
		$RecCnt =(isset($_REQUEST['RecCnt']))?intval($_REQUEST['RecCnt']):'-1';
		$PageSize = 100;
		//End page
		$stock_type=$xltm->SQL->GetRows("SELECT * FROM stock_hy");
		$stock=$xltm->SQL->GetRows("SELECT * FROM stock_code where id>0 $inquiry order by $Order $By");
		$xltm->tpls->LoadTemplate("./tmpfiles/stock.html",false);
		$xltm->tpls->PlugIn(TBS_BYPAGE,$PageSize,$PageNum,$RecCnt);
		$RecCnt =$xltm->tpls->MergeBlock('tr','array',$stock);
		$xltm->tpls->PlugIn(TBS_NAVBAR,'nv','',$PageNum,$RecCnt,$PageSize);
		$xltm->tpls->MergeBlock('tp','array',$stock_type);
		$showPage=($RecCnt>0)?1:'';
		$xltm->tpls->Show();
		break;
}
function stock_event($BlockName,&$CurrRec,$RecNum){
	//板块
	$bk_list = $CurrRec['bk_list'];
	$CurrRec['bk_lists'] = "<select size='1' style='width:70px;'>";
	if($bk_list)
	{
		$bk_list = substr($bk_list,1);
		$ary = explode(',',$bk_list);
		foreach($ary as $k)
		{
			$CurrRec['bk_lists'] .= "<option value='{$k}'>{$k}</option>";
		}
	}
	else
	{
		$CurrRec['bk_lists'] .= "<option>未知</option>";
	}
	$CurrRec['bk_lists'] .= "</select>";
	$CurrRec['off']=($CurrRec['inf']==1)?'<b>开盘</b>':'关盘';
	$CurrRec['stop']=($CurrRec['stop_pai']==1)?'<b>停牌</b>':'无停';
	$CurrRec['sell']=($CurrRec['can_sell']==0)?'<b>禁卖</b>':'可卖';
	$CurrRec['bull']=($CurrRec['can_bull']==0)?'<b>禁买</b>':'可买';
	$CurrRec['up']=($CurrRec['can_bull_up']==0)?'<b>禁买升</b>':'可买升';
	$CurrRec['down']=($CurrRec['can_bull_down']==0)?'<b>禁买跌</b>':'可买跌';
	$CurrRec['s_type']=$s_type[$CurrRec['hy']];
}
?>