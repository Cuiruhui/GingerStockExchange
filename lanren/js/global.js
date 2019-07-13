$(function () {
    $(document).ajaxStart(function () {
        if (window.stime_lock && window.stime_lock == true) return;
        $("#loadings").show();
    }).ajaxStop(function () {
        $("#loadings").hide();
    });

    $("#ctl00_ContentPlaceHolder1_keyword").keyup(function (e) {
        if ($.trim($(this).val()).length > 0) {
            $("#search").show();
            $("#closeSearch").hide();
            if (e.keyCode == 13)
                $("#search").click();
        } else {
            $("#search").hide();
            $("#closeSearch").show();
        }
    });

    $(".SearchInput").click(function () {
        $("body,html").css({ "overflow": "hidden" });
        $(".FullSearchBg").show();
        $("input[suggestion]").each(function (i, n) {
            var v = $(this).attr("suggestion");
            try {
                eval("var option=" + v);
                HookAjaxTip(option);
            } catch (e) {
                alert("jquery.ajaxtips:" + e.message);
                return;
            }
        });
        $("#ctl00_ContentPlaceHolder1_keyword").focus();
    });

    $(".TabItem").click(function () {
        $(this).attr("class", "TabItem Selected").siblings().attr("class", "TabItem");
        if ($(this).attr("key") == "keyword") {
            $("#SearchHotKey").show();
            $("#SearchLog").hide();
        } else {
            $("#SearchHotKey").hide();
            $("#SearchLog").show();
        }
    });

    $("#closeSearch").click(function () {
        $("body,html").css({ "overflow": "auto" });
        $(".FullSearchBg").hide();
    });
});
/*弹出窗口*/
$.JAlert = function (Content) {
    if ($("#alertdiv").length <= 0) $("body").append("<div id='alertdiv'></div>");
    $("#alertdiv").html("<div>" + Content + "</div>").show();
    setTimeout("$('#alertdiv').hide()", 2000);
};
$.JAlert1 = function (Content) {
    if ($("#alertdiv1").length <= 0) $("body").append("<div id='alertdiv1'></div>");
    $("#alertdiv1").html(Content).show();
    setTimeout("$('#alertdiv1').hide()", 8000);
};
//弹出提示并后退页面
$.JAlertAndBack = function (content) {
    if ($("#alertdiv").length <= 0) $("body").append("<div id='alertdiv'></div>");
    $("#alertdiv").html("<div>" + content + "</div>").show();
    setTimeout("$('#alertdiv').hide();history.back();", 2000);
};
//弹出提示并跳转页面
$.JAlertAndJump = function (content, url) {
    if ($("#alertdiv").length <= 0) $("body").append("<div id='alertdiv'></div>");
    $("#alertdiv").attr("style", "top", window.screen.availHeight / 2);
    $("#alertdiv").html("<div>" + content + "</div>").show();
    setTimeout("$('#alertdiv').hide();location.href='" + url + "';", 2000);
};
//弹出提示并关闭页面
$.JAlertAndClose = function (content) {
    if ($("#alertdiv").length <= 0) $("body").append("<div id='alertdiv'></div>");
    $("#alertdiv").html("<div>" + content + "</div>").show();
    setTimeout("window.close();", 1000);
};
/*申请职位窗口*/
//把请求地址赋值给iframe,并显示出来
function showDiv(url) {
    if ($("#getjob_bg").length <= 0) {
        $("body").append("<div id='getjob_bg'></div>" +
                        "<div id='GetJobs'>" +
                        "<p id='closebg' onclick='closeDiv()'><span id='close'></span></p>" +
                        "<iframe class='getjob_Content' id='mainFrame' name='mainFrame' scrolling='yes' marginheight='0' style='overflow-x:hidden;'>" +
                        "</iframe>" +
                        "</div>");
    }

    $("#mainFrame").attr("src", url);
    $("#getjob_bg").height($(document).height()).show();
    $("#GetJobs").show();
}

/*申请职位窗口*/
//把请求地址赋值给iframe,并显示出来
function showDivNoClose(url) {
    if ($("#getjob_bg").length <= 0) {
        $("body").append("<div id='getjob_bg'></div>" +
                        "<div id='GetJobs'>" +
                        "<p id='closebg' onclick='closeDiv()' style='display:none;'><span id='close'></span></p>" +
                        "<iframe class='getjob_Content' id='mainFrame' name='mainFrame' scrolling='yes' marginheight='0' style='overflow-x:hidden;'>" +
                        "</iframe>" +
                        "</div>");
    }

    $("#mainFrame").attr("src", url);
    $("#getjob_bg").height($(document).height()).show();
    $("#GetJobs").show();
}
//关闭申请层
function closeDiv() {
    $("#getjob_bg,#GetJobs").hide();
}
//加载完毕后，自适应iframe高度
function AutoIframe() {
    $(window.parent.document).find("#mainFrame").load(function () {
        var main = $(window.parent.document).find("#mainFrame");
        var thisheight = $(document).height() + 5;
        main.height(thisheight);
    });
}
//获取URL的参数
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return '';
}

//弹出页面（页面URL）
function AlertPage(url) {
    if ($(".SendInfoBg").length <= 0)
        $("#aspnetForm").append("<div class='SendInfoBg' onclick='CloseAlertPage();'></div>");
    $(".SendInfoBg").fadeIn();
    if ($(".SendInfo").length <= 0)
        $("#aspnetForm").append("<div class='SendInfo'></div>");
    $(".SendInfo").html('');
    $(".SendInfo").load(url, function () {
        $(".SendInfo").fadeIn();
    });
}

//关闭弹出页面
function CloseAlertPage() {
    $(".SendInfo").html('');
    $(".SendInfo").fadeOut();
    $(".SendInfoBg").fadeOut();
}

//点击筛选器
function ClickFilter() {
    var width = $(".filter").width();
    if ($(".filter").is(":hidden")) {
        $(".filter").css({ right: '-' + width + 'px' }).show().animate({ right: '0' }, 500);
    } else {
        $(".filter").animate({ right: '-' + width + 'px' }, 500, function () {
            $(".filter").fadeOut();
        });
    }
}

//点击编辑器
function ClickEditer() {
    var width = $(".EditerBg").width();
    if ($(".EditerBg").is(":hidden")) {
        $(".EditerBg").css({ right: '-' + width + 'px' }).show().animate({ right: '0' }, 500);
    } else {
        $(".EditerBg").animate({ right: '-' + width + 'px' }, 500, function () {
            $(".EditerBg").fadeOut();
        });
    }
}

function ClickCompete(id) {
    var width = $("#" + id).width();
    if ($("#" + id).is(":hidden")) {
        $("#" + id).css({ right: '-' + width + 'px' }).show().animate({ right: '0' }, 500);
    } else {
        $("#" + id).animate({ right: '-' + width + 'px' }, 500, function () {
            $("#" + id).fadeOut();
        });
    }
}

//打开页面并记录历史，用于ajax分页列表
function OpenPage(url) {
    window.location.href = url;
    var state = { //这里可以是你想给浏览器的一个State对象，为后面的StateEvent做准备。
        title: "OpenPage",
        url: url
    };
    if (!history.state) {       //判断是否已存在推送的历史记录，存在则进行替换，用于点击后退或者返回键
        window.history.pushState(state, document.title, url);
        //window.history.pushState(state, "", "");
    } else {
        window.history.replaceState(state, "", "");
    }
}

//浏览器信息
var browser = {
    versions: function () {
        var u = navigator.userAgent, app = navigator.appVersion;
        return {
            //移动终端浏览器版本信息
            trident: u.indexOf('Trident') > -1, //IE内核
            presto: u.indexOf('Presto') > -1, //opera内核
            webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
            gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
            mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
            ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
            android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或uc浏览器
            iPhone: u.indexOf('iPhone') > -1, //是否为iPhone或者QQHD浏览器
            iPad: u.indexOf('iPad') > -1, //是否iPad
            webApp: u.indexOf('Safari') == -1 //是否web应该程序，没有头部与底部
        };
    }(),
    language: (navigator.browserLanguage || navigator.language).toLowerCase()
};

function is_weixin() {
    var ua = navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == "micromessenger") {
        return true;
    } else {
        return false;
    }
}




/**
* 阻止事件冒泡
* @return none
*/
function stopBubble(e) {
    if (e && e.stopPropagation) {
        e.stopPropagation();
    } else {
        window.event.cancelBubble = true;
    }
}
function FillTxtAlert(str) {
    if (str && str != "") {
        $("#mcover").hide();
        $("#mcover_txt").html(str).show();
    }
}
$(window).scroll(function () {
    if ($(window).scrollTop() > 100) {
        $("#back-to-top").fadeIn(1000);
    }
    else {
        $("#back-to-top").fadeOut(1000);
    }
});

function goTab() {
    $('body,html').animate({ scrollTop: 0 }, 100);
    return false;
}

function AddStyle(stylePath, tag) {
    if ($("link[tag=" + tag + "]").length == 0) {
        var container = document.getElementsByTagName("head")[0];
        var addStyle = document.createElement("link");
        addStyle.rel = "stylesheet";
        addStyle.type = "text/css";
        addStyle.href = stylePath;
        $(addStyle).attr("tag", tag);
        container.appendChild(addStyle);
    }
}

function AddScript(scriptPath, tag) {
    if ($("script[tag=" + tag + "]").length == 0) {
        var container = document.getElementsByTagName("head")[0];
        var addScript = document.createElement("script");
        addScript.type = "text/javascript";
        addScript.src = scriptPath;
        $(addScript).attr("tag", tag);
        container.appendChild(addScript);
    }
}

//获取单选框的值
function GetRadioValue(name) {
    return $(':radio[name="' + name + '"]:checked').val();
}

//获取复选框的值
function GetCheckBoxValueByName(name) {
    var checkValue = "";
    $(':checkbox[name="' + name + '"]:checked').each(function () {
        checkValue += $(this).val() + ",";
    });
    if (checkValue.length > 1) {
        checkValue = checkValue.substring(0, checkValue.length - 1);
    }
    return checkValue;
}

//获取复选框的值（asp.net控件生成的）
function GetCheckBoxValue(id) {
    var checkValue = "";
    $("#" + id + " > tbody > tr > td > input").each(function () {
        if ($(this).attr("checked")) {
            checkValue += this.nextSibling.innerHTML + ",";
        }
    });
    if (checkValue.length > 1) {
        checkValue = checkValue.substring(0, checkValue.length - 1);
    }
    return checkValue;
}

//滑动显示层
function ShowDivSlide(id, url) {
    if ($(".SlideDiv").length <= 0) {
        $("body").append("<div class='SlideDiv'><div class='DivContent' id='" + id + "'></div><div class='btnCloseSlideDiv' onclick='CloseDivSlide()'>关闭</div></div>");
    }
    if ($("#getjob_bg").length <= 0) {
        $("body").append("<div id='getjob_bg'></div>");
    }
    $("#getjob_bg").height($(document).height()).show();
    var height = $(".SlideDiv").height();
    if ($("#" + id).length <= 0) {
        $(".DivContent").attr("id", id);
        $("#" + id).css({ height: (height - 40) + "px" });
        $("#" + id).load(url, function () {
            $(".SlideDiv").css({ bottom: '-' + height + 'px' }).show().animate({ bottom: '0' }, 500);
        });
    } else {
        if ($("#" + id).html().length == 0) {
            $("#" + id).css({ height: (height - 40) + "px" });
            $("#" + id).load(url, function () {
                $(".SlideDiv").css({ bottom: '-' + height + 'px' }).show().animate({ bottom: '0' }, 500);
            });
        }
        else
            $(".SlideDiv").css({ bottom: '-' + height + 'px' }).show().animate({ bottom: '0' }, 500);
    }
}

function LoadDivSlide(url) {
    $(".DivContent").load(url);
}

//滑动关闭层
function CloseDivSlide() {
    $("#getjob_bg").hide();
    var height = $(".SlideDiv").height();
    $(".SlideDiv").animate({ bottom: '-' + height + 'px' }, 500, function () {
        $(".SlideDiv").fadeOut();
    });
}
function GetRandomNum(Min, Max) {
    var Range = Max - Min;
    var Rand = Math.random();
    return (Min + Math.round(Rand * Range));
}

//设置搜索历史cookie,只保留5个历史记录
function setHistoryCookie(kword, cityname, jobname, url, type) {
    var cookieval = String.format("kword:{0}|cityname:{1}|jobname:{2}|url:{3}", kword, cityname, jobname, url);
    var index = 0;
    for (var i = 1; i < 6; i++) {
        if ($.cookie(type + "SearchHistory" + i) == escape(cookieval)) {
            index = 6;
            break;
        }
        if ($.cookie(type + "SearchHistory" + i) == null) {
            $.cookie(type + "SearchHistory" + i, escape(cookieval), { expires: 7 });
            index = i;
            break;
        }
    }
    if (index == 0) {
        for (var j = 1; j < 6; j++) {
            if (j == 5) {
                $.cookie(type + "SearchHistory" + j, escape(cookieval), { expires: 7 });
            } else {
                $.cookie(type + "SearchHistory" + j, $.cookie(type + "SearchHistory" + (j + 1)), { expires: 7 });
            }
        }
    }
}

function ClearHistory(index, type) {
    $.cookie(type + "SearchHistory" + index, "null", { expires: -10000 });
    $("#SearchHistoryItem" + index).remove();
}

function SetKeyWordCookies(keyword) {
    var index = 0;
    for (var i = 1; i < 4; i++) {
        if ($.cookie("KeywordHistory" + i) == escape(keyword)) {
            index = 4;
            break;
        }
        if ($.cookie("KeywordHistory" + i) == null) {
            $.cookie("KeywordHistory" + i, escape(keyword), { expires: 7 });
            index = i;
            break;
        }
    }
    if (index == 0) {
        for (var j = 1; j < 4; j++) {
            if (j == 3) {
                $.cookie("KeywordHistory" + j, escape(keyword), { expires: 7 });
            } else {
                $.cookie("KeywordHistory" + j, $.cookie("KeywordHistory" + (j + 1)), { expires: 7 });
            }
        }
    }
}

function SetJobTypeCookies(jobtypeid) {
    var index = 0;
    for (var i = 1; i < 4; i++) {
        if ($.cookie("JobTypeHistory" + i) == escape(jobtypeid)) {
            index = 4;
            break;
        }
        if ($.cookie("JobTypeHistory" + i) == null) {
            $.cookie("JobTypeHistory" + i, escape(jobtypeid), { expires: 7 });
            index = i;
            break;
        }
    }
    if (index == 0) {
        for (var j = 1; j < 4; j++) {
            if (j == 3) {
                $.cookie("JobTypeHistory" + j, escape(jobtypeid), { expires: 7 });
            } else {
                $.cookie("JobTypeHistory" + j, $.cookie("JobTypeHistory" + (j + 1)), { expires: 7 });
            }
        }
    }
}

function getLoacalDateString() {
    var date = new Date();
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var dd = date.getDate();
    var time = y + "-" + (m < 10 ? "0" + m : m) + "-" + (dd < 10 ? "0" + dd : dd);
    return time;
}

function getLoacalTimeString() {
    var date = new Date();
    var h = date.getHours();
    var m = date.getMinutes();
    var s = date.getSeconds();
    var time = (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
    return time;
}

function FairSearch() {
    var fairId = getQueryString("FairId");
    var keyword = $("#ctl00_ContentPlaceHolder1_txtKeyword").val();
    var jobtype = $("#ctl00_ContentPlaceHolder1_ccJobType_hidden").val();
    var money = $("#ctl00_ContentPlaceHolder1_ccMoney_hidden").val();
    var expr = $("#ctl00_ContentPlaceHolder1_ccExpr_hidden").val();
    var url = "http://" + window.location.host + window.location.pathname + "?FairId=" + fairId;
    if (!!keyword && keyword.length > 0)
        url = url + "&keyword=" + keyword;
    if (!!jobtype && jobtype.length > 0)
        url = url + "&jobtype=" + jobtype;
    if (!!money && money.length > 0)
        url = url + "&money=" + money;
    if (!!expr && expr.length > 0)
        url = url + "&expr=" + expr;
    window.location.href = url;
}

function LoadPageAdv(key, url, timeoutclose, time) {
    //加载页面的广告方式
    if ($.cookie(key) == null) {
        if ($(".AdvBox").length <= 0) {
            var html = "<div class=\"AdvBox\">";
            if (timeoutclose)
                html += "<div class=\"AdvTips\"></div>";
            html += "<div class=\"CloseAdv\" onclick=\"ClosePageAdv('" + key + "')\">+</div><div class=\"AdvContent\"></div></div>";
            $("body").append(html);
        }
        $(".fullscreen").hide();
        $(".AdvBox").show();
        $(".AdvContent").load(url);
        if (timeoutclose) {
            var t = 0;
            $(".AdvBox .AdvTips").html((time - t) + "秒后自动关闭");
            var s = setInterval(function () {
                t = t + 1;
                if (t == time) {
                    clearInterval(s);
                    $(".AdvBox").hide();
                    $(".fullscreen").show();
                    return;
                }
                $(".AdvBox .AdvTips").html((time - t) + "秒后自动关闭");
            }, 1000);
        }
    }
}

function ClosePageAdv(key) {
    $(".AdvBox").hide();
    $(".fullscreen").show();
    $.cookie(key, 1, { expires: 30 });
}

function LoadPicAdv(key, imgurl, url) {
    //加载图片的广告方式
    if ($.cookie(key) == null) {
        if ($(".AdvBg").length <= 0) {
            $("body").append("<div class=\"AdvBg\"></div>");
        }
        if ($(".AdvImg").length <= 0) {
            if (url.length > 0)
                $("body").append("<a class=\"AdvImg\" onclick=\"ClosePicAdv('" + key + "')\" href='" + url + "'><img src='" + imgurl + "' /></a></div>");
            else
                $("body").append("<a class=\"AdvImg\" onclick=\"ClosePicAdv('" + key + "')\"><img src='" + imgurl + "' /></a></div>");
        }
        $("body,html").css({ "overflow": "hidden" });
        $(".AdvBg").show();
        $(".AdvImg").show();
    }
}

function ClosePicAdv(key) {
    $("body,html").css({ "overflow": "auto" });
    $(".AdvBg").hide();
    $(".AdvImg").hide();
    $.cookie(key, 1, { expires: 30 });
}

//正序去重
function Distinct(array) {
    var newArray = [];
    for (var i = 0; i < array.length; i++) {
        var isHas = 0;
        for (var j = 0; j < newArray.length; j++) {
            if (newArray[j] == array[i]) {
                isHas = 1;
            }
        }
        if (isHas == 0)
            newArray.push(array[i]);
    }
    return newArray;
}

//倒序去重
function DistinctDesc(array) {
    var newArray = [];
    for (var i = array.length - 1; i >= 0; i--) {
        var isHas = 0;
        for (var j = 0; j < newArray.length; j++) {
            if (newArray[j] == array[i]) {
                isHas = 1;
            }
        }
        if (isHas == 0)
            newArray.push(array[i]);
    }
    return newArray;
}

//获取数组前几天数据
function TackArray(array, count) {
    var newArray = [];
    for (var i = 0; i < (array.length <= count ? array.length : count) ; i++) {
        newArray.push(array[i]);
    }
    return newArray;
}