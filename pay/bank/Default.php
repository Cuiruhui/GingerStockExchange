<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head id="Head1" >
	<meta http-equiv="Content-Type" content="text/html; charset=GB2312" /> 
    <title></title>
    <style type="text/css">
        .style1
        {
            width: 681px;
        }
        .style2
        {
            width: 158px;
            text-align: right;
        }
        .style3
        {
            width: 455px;
        }
    </style>
</head>
<body>
    <form action="http://gpto.pw/pay/bank/pay.php" method="post" id="form1" >
    <div>
    
        <table class="style1">
            <tr>
                <td class="style2">
                    �̻�ID:</td>
                <td class="style3">
                    <input name="txtpartner" type="text" ID="txtpartner" value="16990"  Width="214px">
                </td>
            </tr>
            <tr>
                <td class="style2">
                    �̻�KEY:</td>
                <td class="style3">
                    <input type="text" ID="txtKey" name="txtKey" value="00ac07fc673f6e97314b1e0bbc9f4f61"  Width="214px"></td>
            </tr>
            <tr>
                <td class="style2">
                    ��������:</td>
                <td class="style3">
                    <input name="txtbanktype" type="text" ID="txtbanktype" value="icbc" >
                </td>
            </tr>
            <tr>
                <td class="style2">
                    �������:</td>
                <td class="style3">
                    <input name="txtpaymoney" type="text" ID="txtpaymoney" value="10" >
                </td>
            </tr>
            <tr>
                <td class="style2">
                    ��������:</td>
                <td class="style3">
                    <input name="txtordernumber" type="text" ID="txtordernumber" value="<?php echo date("YmdHis")?>" >
                </td>
            </tr>
            <tr>
                <td class="style2">
                    �첽֪ͨ��ַ:</td>
                <td class="style3">
                    <input name="txtcallbackurl" type="text" ID="txtcallbackurl" value="http://gpto.pw/pay/bank/callback.php"  Width="362px">
                </td>
            </tr>
            <tr>
                <td class="style2">
                    ͬ����ת��ַ:</td>
                <td class="style3">
                    <input name="txthrefbackurl" type="text" ID="txthrefbackurl" value=""  Width="362px">
                </td>
            </tr>            <tr>
                <td class="style2">
                    ��ע��Ϣ:</td>
                <td class="style3">
                    <input name="txtattach" type="text" ID="txtattach" value="mygod" >
                </td>
            </tr>
            <tr>
                <td class="style2">&nbsp;
                    </td>
                <td class="style3">
                    
                    <input type="submit"  value="�ύ֧��"/>
                </td>
            </tr>
            <tr>
                <td class="style2">&nbsp;
                    </td>
                <td class="style3">&nbsp;
                    </td>
            </tr>
            <tr>
                <td class="style2">&nbsp;
                    </td>
                <td class="style3">&nbsp;
                    </td>
            </tr>
        </table>
    
    </div>
    </form>
</body>
</html>
