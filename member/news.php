<?
define('Load_Info', 1);
session_start();
setcookie("stock_list", '');
setcookie("buy1", '');
setcookie("buy2", '');
setcookie("buy3", '');
setcookie("buy4", '');
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

$xltm->tpls->LoadTemplate("./tmpfiles/news.html");
$news=$xltm->SQL->GetRows("SELECT * FROM common where type='1' ORDER BY `add_time` DESC ");
$xltm->tpls->MergeBlock('tr','array',$news);
$xltm->tpls->Show();
?>