<?php
header('P3P: CP="ALL ADM DEV PSAi COM OUR OTRo STP IND ONL"');
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");

$referer = $_SERVER['HTTP_REFERER'];
if(empty($referer)) $referer = "http://98.126.4.131/";
$referer = str_replace("http://","",$referer);
$ra = explode('/',$referer);
$ref_domain = $ra[0];
$register_url = "http://{$ref_domain}/reg.php";
$register_url1 = "http://{$ref_domain}/reg.php?utype=1";
$help_url = "http://{$ref_domain}/help.php";
$xltm->tpls->LoadTemplate("./tmpfiles/frame_login.html",false);
$xltm->tpls->Show();
?>