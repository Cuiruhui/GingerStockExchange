function addFav() {
    var _url = "/ajax/jw/favor.ashx?r=" + Math.random() + "";
    var jobsn = $.trim($("#divJobSn").html());
    if (jobsn.length == 0) {
        $.JAlert("请先勾选需要操作的数据");
        return;
    }
    $.ajax({
        url: _url,
        type: 'POST',
        dataType: 'text',
        timeout: 30000,
        data: "action=add&jobsn=" + jobsn + "&favTypeId=" + $("#favlist").val(),
        error: function () {
            $.JAlert("服务器响应失败，请稍候重新再试!");
        },
        success: function (data) {
            $("#divFavNormal").hide();
            $("#divFavError").show();
            $("#divMsg").html(data);
        }
    });
}

//申请
function SqRecinv() {
    var checkboxs = $("#list").find(":checkbox:checked");
    if (checkboxs.size() == 0) {
        $.JAlert("请先勾选需要操作的数据");
        return;
    }
    if (checkboxs.size() > 5) {
        $.JAlert("最多只能同时应聘5个职位");
        return;
    }
    var jobsn = [];
    checkboxs.each(function () {
        jobsn.push($(this).parent().attr("jobsn"));
    });
    var url = "/jw/GetJob.aspx?r=" + Math.random() + "&id=" + jobsn.join(",") + "";
    ShowDivSlide("GetJob", url);
}

function DelRecinv() {
    var _url = "/ajax/jw/recinv.ashx?r=" + Math.random() + "";
    var checkboxs = $("#list").find(":checkbox:checked");
    if (checkboxs.size() == 0) {
        $.JAlert("请先勾选需要操作的数据");
        return;
    }
    var ids = [];
    checkboxs.each(function () {
        ids.push($(this).attr("value"));
    });
    $.ajax({
        url: _url,
        type: 'POST',
        dataType: 'text',
        timeout: 30000,
        data: "action=del&ids=" + ids.join(',') + "",
        error: function () {
            $.JAlert("服务器响应失败，请稍候重新再试!");
        },
        success: function (data) {
            if (data != "true") {
                $.JAlert(data);
            } else {
                $.JAlert("操作成功");
                checkboxs.parent().animate({ right: -$(window).width() }, 400, function () {
                    checkboxs.parent().remove();
                });
            }
        }
    });
}

function SearchRecinv() {
    location.href = "?dateselect=" + $("#ctl00_ContentPlaceHolder1_dropDateRange").val() + "";
}

function JujueRecinv() {
    var checkboxs = $("#list").find(":checkbox:checked");
    if (checkboxs.size() == 0) {
        $.JAlert("请先勾选需要操作的数据");
        return;
    }
    if (checkboxs.size() > 5) {
        $.JAlert("最多只能同时拒绝5个职位");
        return;
    }
    var ids = [];
    checkboxs.each(function () {
        ids.push($(this).attr("value"));
    });
    var url = "/jw/jujue.aspx?idx=" + ids.join(",") + "";
    ShowDivSlide("jujue", url);
}

function SubmitJujueRecinv() {
    if ($("#Content").val().length <= 0) {
        $.JAlert("请填写拒绝理由");
        return false;
    }
    if ($("#Content").val().length > 100) {
        $.JAlert("拒绝理由请保留在100个字内");
        return false;
    }
    var _url = "/ajax/jw/jujue.ashx?r=" + Math.random() + "";
    var params = {
        idx: $("#hfids").val(),
        Content: $("#Content").val(),
    };
    $.ajax({
        url: _url,
        type: 'POST',
        dataType: 'text',
        timeout: 30000,
        data: params,
        error: function () {
            $.JAlert("服务器响应失败，请稍候重新再试!");
        },
        success: function (data) {
            if (data != "true") {
                $.JAlert(data);
            } else {
                window.parent.$.JAlert("操作成功");
                window.parent.closeDiv();
            }
        }
    });
    return false;
}

function NoMatchGetJob() {
    var checkboxs = $("#panNotExactlyMatch").find(":checkbox:checked");
    if (checkboxs.size() == 0) {
        $.JAlert("请先勾选需要操作的数据");
        return;
    }
    var ids = [];
    checkboxs.each(function () {
        ids.push($(this).attr("value"));
    });

    var url = "/jw/getjob.aspx?id=" + ids.join(",") + "&pipei=1&ressn=" + getQueryString("ressn");
    LoadDivSlide(url);
}