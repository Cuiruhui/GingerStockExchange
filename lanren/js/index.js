$(function () {
    var mySwiper = new Swiper('.swiper-container', {
        pagination: '.pagination',
        paginationClickable: true,
        calculateHeight: true,
        autoplay: 3000,
        grabCursor: true,
        loop: true
    });
    $("img.lazy").lazyload({
        threshold: 200,
        effect: "fadeIn"
    });
    $(".fullscreen").attr("style", "background-color:#eeeeee;");
    $("#search").click(function () {
        var kword = $.trim($("#ctl00_ContentPlaceHolder1_keyword").val());
        var cityval = $.trim($("#ctl00_ContentPlaceHolder1_cityHidden").val());
        if (kword.length == 0) {
            $.JAlert("请填写关键字");
            return false;
        }
        if (kword.length == 1) {
            $.JAlert("搜索关键字不能少于2个字");
            return false;
        }
        var querys = [];
        if (kword.length > 0)
            querys.push("keyword=" + escape(kword));
        if (cityval.length > 0)
            querys.push("city=" + cityval);
        var url = "/ent/resume_search_result.aspx";
        var type = "ent";
        if ($(".SearchType").html() == "找工作") {
            url = "/search/offer_search_result.aspx";
            type = "job";
        }
        url = url + "?" + querys.join("&");
        if (kword.length > 1) {
            setHistoryCookie(kword, "", "", url, type);
            if (kword.indexOf(" ") == -1)
                SetKeyWordCookies(kword);
        }
        location.href = url;
        return false;
    });

    
});

function AppTipsClose() {
    $(".DownLoadTips").hide();
    $.cookie("AppTipsClose", 1, { expires: 7 });
}

//选项卡左右切换
function change(e) {
    var animateArgu = new Object();
    var index = $("#ARTTab").children().index($(e));
    $(e).addClass("active")
        .siblings().removeClass("active");
    animateArgu["left"] = -mainWidth * index + "px";
    $("#tabContent").animate(animateArgu, "fast", "swing");
}

function change1(e) {
    var animateArgu = new Object();
    var index = $("#jobTab").children().index($(e));
    $(e).addClass("active")
        .siblings().removeClass("active");
    animateArgu["left"] = -mainWidth * index + "px";
    $("#jobContent").animate(animateArgu, "fast", "swing");
}
function change2(e) {
    var animateArgu = new Object();
    var index = $("#hotOTab").children().index($(e));
    $(e).addClass("active")
        .siblings().removeClass("active");
    animateArgu["left"] = -mainWidth * index + "px";
    $("#hotOContent").animate(animateArgu, "fast", "swing");
}
function change3(e) {
    var animateArgu = new Object();
    var index = $("#hotRTab").children().index($(e));
    $(e).addClass("active")
        .siblings().removeClass("active");
    animateArgu["left"] = -mainWidth * index + "px";
    $("#hotRContent").animate(animateArgu, "fast", "swing");
}

function ClickDrop(e) {
    if ($(e).text() == "找工作") {
        $(".SearchInput").html("请输入关键字");
        $("#ctl00_ContentPlaceHolder1_keyword").attr("placeholder", "请输入关键字");
        $("#hotresume,#hotresume1,#ResLog").show();
        $("#hotjob,#hotjob1,#JobLog").hide();
        $(e).html("找人才");
    }
    else {
        $(".SearchInput").html("请输入职位/公司名");
        $("#ctl00_ContentPlaceHolder1_keyword").attr("placeholder", "请输入职位/公司名");
        $("#hotresume,#hotresume1,#ResLog").hide();
        $("#hotjob,#hotjob1,#JobLog").show();
        $(e).html("找工作");
    }
}
