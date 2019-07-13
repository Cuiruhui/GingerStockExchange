// JScript 文件
//两端去空格函数
function Trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

function FilterNum(str) {
    return str.replace(/,/g, "");
}


function obj(element) {
    if (typeof element == 'string')
        element = document.getElementById(element);
    return element;
}

function CheckHasObject(o)//控件對象的確定
{
    try {
        obj(o).value;
        return true;
    }
    catch (e) {
        return false;
    }
}

function IsPassword(str) {
    //    var patrn=/^(\w){6,30}$/;  
    //    var patrn = /^(([0-9]+[a-zA-Z]+)|([a-zA-Z]+[0-9]+))*\w{5,19}$/;//必须包含字母和数字混合
    //    return patrn.test(str);  
    if (/^[a-zA-z0-9]{3,20}$/g.test(str)) {
        if (/^\d+$/g.test(str) || /^[a-zA-Z]+$/g.test(str)) {
            return false;
        }
        else {
            return true;
        }
    }
    else {
        return false;
    }
}

function CheckDate(date1, date2) {
    var d1 = new Date(date1);
    var d2 = new Date(date2);
    if (Date.parse(d1) - Date.parse(d2) == 0) {
        return "=";
    }
    if (Date.parse(d1) - Date.parse(d2) < 0) {
        return "<";
    }
    if (Date.parse(d1) - Date.parse(d2) > 0) {
        return ">";
    }
}

function diffDate(date1, date2) {
    d1Arr = date1.split('-');
    d2Arr = date2.split('-');

    v1 = new Date(d1Arr[0], d1Arr[1], d1Arr[2]);
    v2 = new Date(d2Arr[0], d2Arr[1], d2Arr[2]);
    if (v1 > v2) {
        return ">";
    }
    if (v1 == v2) {
        return "=";
    }
    if (v1 < v2) {
        return "<";
    }

}

function GetTimeTo24(date) {
    var h = date.getHours().toString();
    var m = date.getMinutes().toString();
    var s = date.getSeconds().toString();
    return (h.length == 1 ? ("0" + h) : h) + ":" + (m.length == 1 ? ("0" + m) : m) + ":" + (s.length == 1 ? ("0" + s) : s);
}

function GetPattern(spara)//格式化的小数位数
{
    var s = spara.toString();
    var arr = s.split('.');
    var len = 0;
    if (arr.length == 2)
        len = arr[1].length;
    var pattern = "#,##0";
    for (var i = 0; i < len; i++) {
        if (i == 0)
            pattern += ".0";
        else
            pattern += "0";
    }
    return pattern;
}

/*
formatNumber('','')=0
formatNumber(123456789012.129,null)=123456789012
formatNumber(null,null)=0
formatNumber(123456789012.129,'#,##0.00')=123,456,789,012.12
formatNumber(123456789012.129,'#,##0.##')=123,456,789,012.12
formatNumber(123456789012.129,'#0.00')=123,456,789,012.12
formatNumber(123456789012.129,'#0.##')=123,456,789,012.12
formatNumber(12.129,'0.00')=12.12
formatNumber(12.129,'0.##')=12.12
formatNumber(12,'00000')=00012
formatNumber(12,'#.##')=12
formatNumber(12,'#.00')=12.00
formatNumber(0,'#.##')=0
*/
function formatNumber(num, pattern) {
    var strarr = num ? num.toString().split('.') : ['0'];
    var fmtarr = pattern ? pattern.split('.') : [''];
    var retstr = '';

    // 整数部分   
    var str = strarr[0];
    var fmt = fmtarr[0];
    var i = str.length - 1;
    var comma = false;
    for (var f = fmt.length - 1; f >= 0; f--) {
        switch (fmt.substr(f, 1)) {
            case '#':
                if (i >= 0) retstr = str.substr(i--, 1) + retstr;
                break;
            case '0':
                if (i >= 0) retstr = str.substr(i--, 1) + retstr;
                else retstr = '0' + retstr;
                break;
            case ',':
                comma = true;
                retstr = ',' + retstr;
                break;
        }
    }
    if (i >= 0) {
        if (comma) {
            var l = str.length;
            for (; i >= 0; i--) {
                retstr = str.substr(i, 1) + retstr;
                if (i > 0 && ((l - i) % 3) == 0) retstr = ',' + retstr;
            }
        }
        else retstr = str.substr(0, i + 1) + retstr;
    }

    retstr = retstr + '.';
    // 处理小数部分   
    str = strarr.length > 1 ? strarr[1] : '';
    fmt = fmtarr.length > 1 ? fmtarr[1] : '';
    i = 0;
    for (var f = 0; f < fmt.length; f++) {
        switch (fmt.substr(f, 1)) {
            case '#':
                if (i < str.length) retstr += str.substr(i++, 1);
                break;
            case '0':
                if (i < str.length) retstr += str.substr(i++, 1);
                else retstr += '0';
                break;
        }
    }
    return retstr.replace(/^,+/, '').replace(/\.$/, '');
}

function GetPointLen(num)//获取小数位数
{
    var strnum = num.toString();
    if (strnum.split('.').length == 1)
        return "#,##0";
    else {
        var len = strnum.split('.')[1].length;
        switch (len) {
            case 1:
                return "#,##0.0";
            case 2:
                return "#,##0.00";
            case 3:
                return "#,##0.000";
            case 4:
                return "#,##0.0000";
            case 5:
                return "#,##0.00000";
        }
    }
}

//時間格式化
/*
obj1 = new Date().format("yyyy-MM-dd hh:mm:ss");
obj2 = new Date().format("yyyy-MM-dd");
obj3 = new Date().format("yyyy/MM/dd");
obj4 = new Date().format("MM/dd/yyyy");
*/
Date.prototype.format = function (format) {
    var o = {
        "M+": this.getMonth() + 1, //month   
        "d+": this.getDate(),    //day   
        "h+": this.getHours(),   //hour   
        "m+": this.getMinutes(), //minute   
        "s+": this.getSeconds(), //second   
        "q+": Math.floor((this.getMonth() + 3) / 3), //quarter   
        "S": this.getMilliseconds() //millisecond   
    }
    if (/(y+)/.test(format)) format = format.replace(RegExp.$1,
     (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o) if (new RegExp("(" + k + ")").test(format))
        format = format.replace(RegExp.$1,
       RegExp.$1.length == 1 ? o[k] :
         ("00" + o[k]).substr(("" + o[k]).length));
    return format;
}

//驗證是否為大於0的整數
function IsPositiveInt(str) {
    return /^\d+$/.test(str);
}

function IsInteger(str) {
    return /^\d+$/.test(str);
}

function IsDyZero(str) {
    return /^[0-9]*[1-9][0-9]*$/.test(str);
}

function IsNumber(str) {
    return /^(-?\\d+)(\\.\\d+)?$/.test(str);
}
function IsFloat(str) {
    return /^(\+|-)?\d+($|\.\d+$)/.test(str);
}

function IsTime(str) {
    return (new RegExp("^([0-1]\\d|2[0-3]):[0-5]\\d$")).test(str);
}

function IsMail(str) {
    return (new RegExp(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/).test(str));
}

function Isdate(str) {
    return (new RegExp(/^(\d{4})\-(\d{2})\-(\d{2})$/).test(str));
}

function IsRegisterUserNameII(str) {
    var validationExpression = "^[a-z0-9\\u4e00-\\u9fa5]{4,30}$";
    var objRegExp = new RegExp(validationExpression, "g");
    if (!objRegExp.test(str)) return false;
    return true;
}

function GetRdValue(rdname) {
    var rds = document.getElementsByName(rdname);
    var svalue = "";
    for (var _i = 0; _i < rds.length; _i++) {
        if (rds[_i].checked == true) {
            svalue = rds[_i].value;
            break;
        }
    }
    return svalue;
}

function GetDDLText(objDDL, svalue) {
    for (j = 0; j < objDDL.options.length; j++) {
        if (objDDL.options[j].value == svalue) {
            return objDDL.options[j].innerText;
        }
    }
}

function MakeDDLSelected(objDDL, svalue, stype) {
    for (j = 0; j < objDDL.options.length; j++) {
        if (stype == "value") {
            if (objDDL.options[j].value == svalue) {
                objDDL.options[j].selected = true;
                break;
            }
        }
        else if (stype == "text") {
            if (!Prototype.Browser.IE) {
                if (objDDL.options[j].textContent == svalue) {
                    objDDL.options[j].selected = true;
                    break;
                }
            }
            else {
                if (objDDL.options[j].innerText == svalue) {
                    objDDL.options[j].selected = true;
                    break;
                }
            }
        }
    }
}

/**event事件**/
function getEvent() {     //同时兼容ie和ff的写法
    if (document.all) return window.event;
    func = getEvent.caller;
    while (func != null) {
        var arg0 = func.arguments[0];
        if (arg0) {
            if ((arg0.constructor == Event || arg0.constructor == MouseEvent)
                     || (typeof (arg0) == "object" && arg0.preventDefault &&

arg0.stopPropagation)) {
                return arg0;
            }
        }
        func = func.caller;
    }
    return null;
}

//系统自带焦点在否上
function confirmfalse(str) {
    execScript("n   =   msgbox('" + str + "',257,'信息提示！')", "vbscript");
    return n;
}

function adv_format(value, num) //四舍五入
{
    var a_str = formatnumber(value, num);
    var a_int = parseFloat(a_str);
    if (value.toString().length > a_str.length) {
        var b_str = value.toString().substring(a_str.length, a_str.length + 1)
        var b_int = parseFloat(b_str);
        if (b_int < 5) {
            return a_str
        }
        else {
            var bonus_str, bonus_int;
            if (num == 0) {
                bonus_int = 1;
            }
            else {
                bonus_str = "0."
                for (var i = 1; i < num; i++)
                    bonus_str += "0";
                bonus_str += "1";
                bonus_int = parseFloat(bonus_str);
            }
            a_str = formatnumber(a_int + bonus_int, num)
        }
    }
    return a_str
}
function formatnumber(value, num) //直接去尾
{
    var a, b, c, i
    a = value.toString();
    b = a.indexOf('.');
    c = a.length;
    if (num == 0) {
        if (b != -1)
            a = a.substring(0, b);
    }
    else {
        if (b == -1) {
            a = a + ".";
            for (i = 1; i <= num; i++)
                a = a + "0";
        }
        else {
            a = a.substring(0, b + num + 1);
            for (i = c; i <= b + num; i++)
                a = a + "0";
        }
    }
    return a
}

function formatnumberbyzero(value) //过滤尾数0
{
    var Strvalue = value.toString();
    var IndexZero = Strvalue.indexOf('.');

    if (IndexZero == -1)
        return value;
    else {
        var arr = value.split('.');
        for (flag = arr[1].length; flag > 0; flag--) {
            if (arr[1].substring(flag - 1, 1) == "0" && arr[1].length == flag)
                arr[1] = arr[1].substring(0, flag - 1);
        }
    }
    if (arr[1] == "")
        return arr[0];
    else
        return arr[0] + "." + arr[1];
}


function switchBar(obj) {
    var stitle = obj.title;
    if (stitle == "关闭") {
        obj("tduser").style.display = "none";
        obj.src = '../../images/open.jpg';
        obj.title = '打开';
        //根據 打開/關閉 事件，名mainbody的 <frame> 裏div 寬度也跟著變化 (ie下可以實現)
        var f_div = window.frames["mainbody"].contentWindow.document.getElementById("div_main");
        if (f_div != null && f_div != "undefined") {
            obj("mainbody").width = "990px";
            obj("tdbody").style.width = "990px";
            window.frames["mainbody"].contentWindow.showdiv(980);
        }
    } else {
        obj("tduser").style.display = "block";
        obj.src = '../../images/close.jpg';
        obj.title = '关闭';
        //根據 打開/關閉 事件，名mainbody的 <frame> 裏div 寬度也跟著變化 (ie下可以實現)
        var f_div = window.frames["mainbody"].contentWindow.document.getElementById("div_main");
        if (f_div != null && f_div != "undefined") {
            obj("mainbody").width = "845px";
            obj("tdbody").style.width = "845px";
            window.frames["mainbody"].contentWindow.showdiv(835);
        }
    }
}
function AutoWin() {
    var iseoot = document.compatMode == "CSS1Compat" ? document.documentElement : document.body;
    var h = iseoot.scrollHeight;
    if (parseInt(h, 10) < 600)
        h = 600;
    parent.document.getElementById("mainbody").height = h;
}

function GetWeekofDay(mydate) {
    myweekday = mydate.getDay();
    if (myweekday == 0)
        weekday = " 星期日";
    else if (myweekday == 1)
        weekday = " 星期一";
    else if (myweekday == 2)
        weekday = " 星期二";
    else if (myweekday == 3)
        weekday = " 星期三";
    else if (myweekday == 4)
        weekday = " 星期四";
    else if (myweekday == 5)
        weekday = " 星期五";
    else if (myweekday == 6)
        weekday = " 星期六";
    return weekday;
}

function showTheHours(theHour) {
    if (theHour > 0 && theHour < 12) {
        if (theHour == "0") theHour = 0;
        return (theHour);
    }
    if (theHour == 0) {
        return (0);
    }
    return (theHour - 12);
}
function showZeroFilled(inValue) {
    if (inValue > 9) {
        return "" + inValue;
    }
    return "0" + inValue;
}
function showAmPm(Hours) {

    if (Hours < 12) {
        return ("上午");
    }
    return ("下午");
}
function showTheTime(Hours) {
    if (Hours == 24) {
        Hours = Hours - 24;
    }
    if (Hours < 0)
        Hours = Hours + 24;

    return showAmPm(Hours) + "&nbsp;" + showTheHours(Hours);
}

function Infoscroll(scrollinfo, scrollinfo1, scrollinfo2) {
    var speed = 50
    scrollinfo2.innerHTML = scrollinfo1.innerHTML
    function Marquee() {
        if (scrollinfo2.offsetHeight - scrollinfo.scrollTop <= 0)
            scrollinfo.scrollTop -= scrollinfo1.offsetHeight
        else {
            scrollinfo.scrollTop++
        } 
    }
    var MyMar = setInterval(Marquee, speed)
    scrollinfo.onmouseover = function () { clearInterval(MyMar) }
    scrollinfo.onmouseout = function () {
        MyMar = setInterval(Marquee, speed)
    }
}

//if (window.Event) 
//document.captureEvents(Event.MOUSEUP); 

function nocontextmenu() {
    event.cancelBubble = true
    event.returnValue = false;

    return false;
}

function norightclick(e) {
    if (window.Event) {
        if (e.which == 2 || e.which == 3)
            return false;
    }
    else
        if (event.button == 2 || event.button == 3) {
            event.cancelBubble = true
            event.returnValue = false;
            return false;
        }
}
    function CheckAllBox(language) {
        if (Trim(obj("txt_U_name").value) == "") {
            alert("帳號不能為空，請輸入");
            obj("txt_U_Name").focus();
            obj("txt_U_Name").select();
            return false;
        }
        if (Trim(obj("txt_U_Password").value) == "") {
            alert("密碼不能為空，請輸入");
            obj("txt_U_Password").focus();
            obj("txt_U_Password").select();
            return false;
        }
        if (Trim(obj("txt_validate").value) == "") {
            alert("校驗碼不能為空，請正確輸入");
            obj("txt_validate").focus();
            obj("txt_validate").select();
            return false;
        }
		document.loginfrm.submit();
		//检验校验码
		/*
		$.ajax({
			type:'GET',
			url:'login_from.php?type=chkvalidate&validate=' + obj("txt_validate").value,
			cache:false,
			success:function(msg){
				if(msg.indexOf('true')!=-1)
				{
					document.loginfrm.submit();
				}
				else
				{
					alert('校驗碼不正確，請重新輸入');
					ChangeValidate();
					return false;
				}
			}
			});
	   */
    }

    function ChangeValidate() {
        $("#imgCheckNum").attr('src','/validate.php?ts=' + Math.random());
    }
	
    function ClearBox() {
        ChangeValidate();
        obj("txt_validate").value = "";
        obj("txt_U_name").value = userno != "" ? userno : "";
        obj("txt_U_Password").value = "";
    }

    function GetPublicNews() {
        $.ajax({
			type:'GET',
			url:'stocknews.php',
			cache:false,
			async:false,
			success:function(html){
				if(html!='')
				{
					if(html.substring(0,1)!='<')
					{
						pos = html.indexOf('<');
						html = html.substring(pos,(html.length-pos+1));
					}
					obj("divPublicNews").innerHTML = html;
				}
			}
		 });		
    }
    function GetCurrprices() {
        $.ajax({
			type:'GET',
			url:'quote.php',
			cache:false,
			async:false,
			success:function(html){
				if(html!='')
				{
					if(html.substring(0,1)!='<')
					{
						pos = html.indexOf('<');
						html = html.substring(pos);
						
					}
					obj("LMEscroll1").innerHTML = html;
					Infoscroll(LMEscroll, LMEscroll1, LMEscroll2);
				}
			}
		 });
    }

    function TriggerBtnSubmit(btn) {
        var e = event.srcElement;
        if (event.keyCode == 13) {
            event.keyCode = 9;
            document.getElementById(btn).click();
            return false;
        }
    }
