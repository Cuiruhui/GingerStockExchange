$(function () {
    var refurl = document.referrer;
    refurl = refurl.toLowerCase();
    if (document.referrer.indexOf(document.domain) == -1) {
        $("#btn_back").attr("onclick", "location.href='/index.aspx'");
        $.cookie("enthits", "0");
    } else {
        if ($.cookie("url") == null || window.location.href != $.cookie("url")) {
            if (refurl.indexOf("showjob") > -1 || refurl.indexOf("showent") > -1 || refurl.indexOf("showalljob") > -1 || refurl.indexOf("baidumapshow") > -1) {
                $.cookie("enthits", parseInt($.cookie("enthits")) + 1);
            } else { //第一次进入
                $.cookie("enthits", "1");
            }
        }
        $.cookie("url", window.location.href);
        var hisCount = $.cookie("enthits");
        $("#btn_back").attr("onclick", "history.go(-" + hisCount + ");");
    }
    //setTimeout(function () {
    //    $(".DiyTips").fadeOut();
    //}, 3000);
});