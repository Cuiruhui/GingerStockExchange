<?php
include_once("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>银行充值</title>
<style type="text/css">
body{
	font-size:12px;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.STYLE1 {color: #2179DD}
</style>
</head>
<body bgcolor="#ffffff">
<table width="100%" height="34" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="34"><img src="img/pic_1.gif" width="69" height="60" /></td>
    <td width="100%" background="img/pic_3.gif" bgcolor="#2179DD"><img src="img/pic_4.gif" width="40" height="40" />充值第二步，银行选择</td>
    <td width="13" height="34"><img src="img/pic_2.gif" width="69" height="60" /></td>
  </tr>
</table>
<br />
<table width="864" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#5c9acf" class="mytable">
  <tr>
    <td width="100%" height="88" bgcolor="#FFFFFF"><br />
      <table width="500" border="0" align="center" cellpadding="1" cellspacing="1" class="table_main">
	  <form name="f1" action="bank.php" method="post">
      </tr>
      <tr>
        <td height="25" align="right" bgcolor="#FFFFFF">充值金额：</td>
        <td><input type="text" name="price" value="1" readyonly="true"></td>
      </tr>
      <tr>
        <td height="25" align="right" bgcolor="#FFFFFF"><span class="STYLE1">银行选择：</span></td>
        <td>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" width="30" height="35"><input  type="radio" checked="checked"  name="ka_type" value="967" />    </td>
    <td><img align="absmiddle" src="bank/ICBC-NET.png" width="154" height="33" alt="中国工商银行" border="0" /> </td>
    <td align="center"><input  type="radio" name="ka_type" value="964" />    </td>
    <td><img align="absmiddle" src="bank/ABC-NET.png" width="154" height="33" alt="中国农业银行" border="0" /> </td>
  </tr>
  <tr>
    <td align="center" width="30" height="35"><input  type="radio" name="ka_type" value="970" />    </td>
    <td><img align="absmiddle" src="bank/CMBCHINA-NET.png" width="154" height="33" alt="中国招商银行" border="0" /> </td>
    <td align="center" width="30" height="35"><input  type="radio" name="ka_type" value="963" />    </td>
    <td><img align="absmiddle" src="bank/BOC-NET.png" width="154" height="33" alt="中国银行" border="0" /> </td>
  </tr>
  <tr>
    <td align="center" width="30" height="35"><input  type="radio" name="ka_type" value="972" />    </td>
    <td><img align="absmiddle" src="bank/CIB-NET.png" width="154" height="33" alt="兴业银行" border="0" /> </td>
    <td align="center"><input  type="radio" name="ka_type" value="986" />    </td>
    <td><img align="absmiddle" src="bank/CEB-NET.jpg" width="154" height="33" alt="光大银行" border="0" /> </td>
  </tr>
  <tr>
    <td align="center" width="30" height="35"><input  type="radio" name="ka_type" value="974" />    </td>
    <td><img align="absmiddle" src="bank/SDB-NET.png" width="154" height="33" alt="深圳发展银行" border="0" /> </td>
    <td align="center"><input  type="radio" name="ka_type" value="985" />    </td>
    <td><img align="absmiddle" src="bank/GDB-NET.png" width="154" height="33" alt="广东发展银行" border="0" /> </td>
  </tr>
  <tr>
    <td align="center"><input  type="radio" name="ka_type" value="965" />    </td>
    <td><img align="absmiddle" src="bank/CCB-NET.png" width="154" height="33" alt="建设银行" border="0" /> </td>
    <td align="center" width="30" height="35"><input  type="radio" name="ka_type" value="980" />    </td>
    <td><img align="absmiddle" src="bank/1001009.png" width="154" height="33" alt="中国民生银行民生卡" border="0" /> </td>
  </tr>
  <tr>
    <td align="center" width="30" height="35"><input  type="radio" name="ka_type" value="962" />    </td>
    <td><img align="absmiddle" src="bank/ECITIC-NET.png" width="154" height="33" alt="中信银行" border="0" /> </td>
     <td align="center"><input  type="radio" name="ka_type" value="971" />    </td>
    <td><img align="absmiddle" src="bank/POST-NET.png" width="154" height="33" alt="中国邮政" border="0" /> </td>
  </tr>
  <tr>
    <td align="center"><input  type="radio" name="ka_type" value="989" />    </td>
    <td><img align="absmiddle" src="bank/BCCB-NET.png" width="154" height="33" alt="980" border="0" /> </td>
    <td align="center" width="30" height="35"><input  type="radio" name="ka_type" value="983" />    </td>
    <td><img align="absmiddle" src="bank/hangzhou.gif" width="154" height="33" alt="杭州银行" border="0" /> </td>
  </tr>
  <tr>
    <td align="center" width="30" height="35"><input  type="radio" name="ka_type" value="982" />    </td>
    <td><img align="absmiddle" src="bank/huaxia.gif" width="140" height="42" alt="华夏银行" border="0" /> </td> 
  </tr>
</table>

		</td>
      </tr>
      <tr>
        <td height="25" align="right" bgcolor="#FFFFFF">&nbsp;</td>
        <td><input type="submit" name="Submit" value="确定" style="height:30px;width:160px;" /></td>
      </tr>
	  </form>
    </table>
    <br /></td>
  </tr>
</table>
</body>
</html>