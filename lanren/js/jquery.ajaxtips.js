/*需要搭载jquery.js*/
$(document).ready(function () {
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
});
function HookAjaxTip(option) {
    var option = $.extend({ autowidth: true, delay: 300 }, option);
    var keycaches = {};
    var container = $("#" + option.containerid);
    if (container.length == 0) {
        $("body").append("<div id='suggest'></div>");
        container = $("#" + option.containerid);
    }
    var ipt = $("#" + option.inputid);
    var pos = ipt.offset();
    if (option.autowidth) container.width(ipt.outerWidth() - ipt.css("padding-left").replace("px",""));
    var _t = pos.top + (ipt.height()+5);
    var _l = pos.left;
    container.css({ top: _t, left: _l });
    var doHookKeyPress = function (event) {
        var key = $.trim(ipt.val());
        if (key == '') {
            container.hide();
            return;
        }

        //从缓存中获取
        if (typeof (keycaches["c_" + key]) != 'undefined') AppenTips(option, ipt, container, key, keycaches["c_" + key]);
        else {
            $("img.suggestloading").show();
            var paras = { 'keyword': escape(key) };
            if (typeof (option.paracallback) == 'function') { //make sure has keycallback 
                paras = $.extend(paras, option.paracallback());
            }
            $.post(option.url, paras, function (html) {
                if (html != "") {
                    keycaches["c_" + key] = html;
                    AppenTips(option, ipt, container, key, html);
                } else container.hide();
                $("img.suggestloading").hide();
            });
        }
    };
    var timer = null;


    var keyProc = function (event) {
        if (timer) window.clearTimeout(timer);
        timer = setTimeout(function () { doHookKeyPress(event); }, option.delay);
    };
    if (("undefined" != typeof $.browser) && $.browser.mozilla)
        ipt.keypress(keyProc);
    else
        ipt.keydown(keyProc);
    ipt.blur(function () {
        timer = setTimeout(function () { container.hide(); }, 200);
    });

}

function AppenTips(option, ipt, container, key, html) {
    container.html(html).show().find("li:last").click(function () {
        container.hide();
    });
    try {
        container.bgiframe();
    } catch (e) {

    }
    var items = container.find("li").slice(0, container.find("li").length - 1);
    items.unbind().click(function () {
        ipt.val($.trim($(this).text()));
        container.hide();
        if (typeof (option.entercallback) == 'function') {
            option.entercallback();
        }
    });
}
