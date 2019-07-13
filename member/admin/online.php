<?
define('Load_Info', 1);
session_start();
include_once("./Lib/xltm.class.php");
print $xltm->startTIME();
sleep(1);
echo time()."/test";
?>