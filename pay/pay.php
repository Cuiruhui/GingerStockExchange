<?php

session_start();

$time = date("Y-m-d H:i:s",time());
$username = trim($_REQUEST["username"]);
$money = trim($_REQUEST["money"]);
$orderid = 'SN'.rand(1000,9999).time().rand(1000,9999);
$bankname = trim($_REQUEST["bankname"]);
$_SESSION['callbackurl'] = trim($_REQUEST["callbackurl"]);
$_SESSION['webid'] = trim($_REQUEST["webid"]);
$_SESSION['webpwd'] = trim($_REQUEST["webpwd"]);



?>