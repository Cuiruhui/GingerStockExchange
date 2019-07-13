function isMobile() {
    try {
        if ((navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i))) {
            return true;
        }
    } catch (err) { return false; }
    return false;
}

function uaredirect(murl) {
    try {
        if ((navigator.userAgent.match(/(iPhone|iPod|Android|ios)/i))) {
            location.replace(murl + "#src_web");
        }
    } catch (err) { }
}

/*===========================加入收藏========================================*/

//兼容IE和Firefox的加入收藏效果（推荐使用）
var addFavorite = function (obj, url, title) {
    var B = {
        IE: /MSIE/.test(window.navigator.userAgent)
                , FF: /Firefox/.test(window.navigator.userAgent)
                , OP: /Opera/.test(window.navigator.userAgent)   //!!window.opera
                , Ch: /Chrome/.test(window.navigator.userAgent)
                , Sa: /Safari/.test(window.navigator.userAgent)
    };

    try {
        obj.onmousedown = null;

        if (B.FF) {
            obj.setAttribute("rel", "sidebar"), obj.title = title, obj.href = url;
        }
        else {
            //目前Opera、Chrome和Safari不能代码实现
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }
    catch (e) {
        alert("加入收藏失败，请使用Ctrl+D进行添加");
    }
};

/*===========================设置首页========================================*/
function setHomepage() {
    if (document.all) {
        document.body.style.behavior = 'url(#default#homepage)';
        document.body.setHomePage(window.location.href);
    } else if (window.sidebar) {
        if (window.netscape) {
            try {
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
            } catch (e) {
                alert("该操作被浏览器拒绝，如果想启用该功能，请在地址栏内输入 about:config,然后将项 signed.applets.codebase_principal_support 值该为true");
            }
        }
        var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
        prefs.setCharPref('browser.startup.homepage', window.location.href);
    } else {
        alert('您的浏览器不支持自动自动设置首页, 请使用浏览器菜单手动设置!');
    }
}

function showtime() {
    var today = new Date((new Date()).getTime());

    /*Daylight Saving Time Start*/
    var dst = 0;
    var lsm = new Date;
    var lso = new Date;
    lsm.setMonth(2); // March
    lsm.setDate(31);
    var day = lsm.getDay(); // day of week of 31st
    lsm.setDate(31 - day); // last Sunday
    lso.setMonth(9); // October
    lso.setDate(31);
    day = lso.getDay();
    lso.setDate(31 - day);
    if (today < lsm || today >= lso) dst = 1;
    /*Daylight Saving Time End*/

    var hour = today.getHours();
    var minute = today.getMinutes();
    var second = today.getSeconds();
    if (hour <= 9)
        hour = "0" + hour;
    if (minute <= 9)
        minute = "0" + minute;
    if (second <= 9)
        second = "0" + second;

    var utc = today.getTime() + (today.getTimezoneOffset() * 60000);
    var ldDate = new Date(utc + (3600000 * (1 - dst)));
    var nyDate = new Date(utc + (3600000 * (-4 - dst)));
    var tyDate = new Date(utc + (3600000 * (9)));
    var sxDate = new Date(utc + (3600000 * (2)));

    var ldhour = ldDate.getHours() < 9 ? ("0" + ldDate.getHours()) : ldDate.getHours();
    var nyhour = nyDate.getHours() < 9 ? ("0" + nyDate.getHours()) : nyDate.getHours();
    var tyhour = tyDate.getHours() < 9 ? ("0" + tyDate.getHours()) : tyDate.getHours();
    var sxhour = sxDate.getHours() < 9 ? ("0" + sxDate.getHours()) : sxDate.getHours();

    var strldtime = ldhour + ":" + minute + ":" + second + "&nbsp;";
    var strnytime = nyhour + ":" + minute + ":" + second + "&nbsp;";
    var strtytime = tyhour + ":" + minute + ":" + second + "&nbsp;";
    var strhktime = hour + ":" + minute + ":" + second + "&nbsp;";
    var strsxtime = sxhour + ":" + minute + ":" + second;

    $('#ldtime').html(strldtime);
    $('#nytime').html(strnytime);
    $('#tytime').html(strtytime);
    $('#hktime').html(strhktime);

    setTimeout("showtime();", 500);
}