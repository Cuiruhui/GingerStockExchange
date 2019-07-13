window.un = "";

$(function () {
    if ($.cookie("IsClearLocalStorage") != 1) {
        window.localStorage.clear();
        $.cookie("IsClearLocalStorage", 1, { expires: 9999 });
    }
    InitHxConn();
    InitNewsBox();
    $(window).resize(function () {
        InitNewsBox();
    });
    $(".MyNews").click(function () {
        $(".MyNews").attr("class", "MyNews");
        $(this).hide();
        $(".fullscreen").hide();
        ShowNewsBox();
        var un = $(".MsgWindow:visible").attr("un");
        ClearUnReadCount(un);
        var lastchild = $(".MsgWindow[un=" + un + "] > div:last-child");
        if (lastchild.length > 0) {
            lastchild.get(0).scrollIntoView();
        }
    });

    $(".CloseNewsBox").click(function () {
        $(".MyNewsBox").hide();
    });

    $(".SendMsg").click(function () {
        if ($(".MsgWindow").length == 0) {
            $.JAlert("当前没有可用的会话窗口");
            return;
        }
        var username = $(".MsgWindow:visible").eq(0).attr("un");
        var msg = $("#inputMsg").val().replace(/<[^>]+>/g, "");
        if (msg.length == 0 || $.trim(msg).length == 0) {
            $.JAlert("消息内容不能为空");
            return;
        }
        sendText(msg, username, '');
        $("#inputMsg").val("");
    });

    $(".BoxSend u").click(function () {
        $(".QuickReply").slideToggle(function () {
            if ($(".QuickReply").is(":hidden")) {
                $(".MyNewsBox .BoxMsg").height(document.documentElement.clientHeight - 100);
            } else {
                $(".MyNewsBox .BoxMsg").height(document.documentElement.clientHeight - 100 - $(".QuickReply").outerHeight());
                var last = $(".MyNewsBox .BoxMsg .MsgWindow:visible > div:last-child");
                if (last.length > 0) {
                    last.get(0).scrollIntoView();
                }
            }
        });
    });

    $(".QuickReply li").click(function () {
        $("#inputMsg").val($(this).text());
        $(".QuickReply").slideToggle(function () {
            if ($(".QuickReply").is(":hidden")) {
                $(".MyNewsBox .BoxMsg").height(document.documentElement.clientHeight - 100);
            } else {
                $(".MyNewsBox .BoxMsg").height(document.documentElement.clientHeight - 100 - $(".QuickReply").outerHeight());
                var last = $(".MyNewsBox .BoxMsg .MsgWindow:visible > div:last-child");
                if (last.length > 0) {
                    last.get(0).scrollIntoView();
                }
            }
        });
    });

    $("#inputMsg").focus(function () {
        $(this).attr("class", "Focus");
    });

    $("#inputMsg").blur(function () {
        $(this).attr("class", "");
    });

    InitClick();
});

//初始化环信连接
function InitHxConn() {
    window.hxconn = new Easemob.im.Connection();
    window.hxconn.init({
        //连接打开时的回调方法
        onOpened: function () {
            window.hxconn.setPresence();
            handleOpen(window.hxconn);
        },
        //收到文本消息时的回调方法
        onTextMessage: function (message) {
            handleTextMessage(message);
        },
        //收到表情消息时的回调方法
        onEmotionMessage: function (message) {
            handleEmotion(message);
        },
        //收到图片消息时的回调方法
        onPictureMessage: function (message) {
            handlePictureMessage(message);
        },
        //收到音频消息的回调方法
        onAudioMessage: function (message) {
            handleAudioMessage(message);
        },
        //收到位置消息的回调方法
        onLocationMessage: function (message) {
            handleLocationMessage(message);
        },
        //收到文件消息的回调方法
        onFileMessage: function (message) {
            handleFileMessage(message);
        },
        //收到视频消息的回调方法
        onVideoMessage: function (message) {
            handleVideoMessage(message);
        },
        //收到联系人订阅请求的回调方法
        onPresence: function (message) {
            handlePresence(message);
        },
        //收到联系人信息的回调方法
        onRoster: function (message) {
            handleRoster(message);
        },
        //收到群组邀请时的回调方法
        onInviteMessage: function (message) {
            handleInviteMessage(message);
        },
        //异常时的回调方法
        onError: function (message) {
            handleError(message);
        },
        //关闭连接是的回调方法
        onClosed: function () {
            window.hxconn.clear();
            window.hxconn.onClosed();
        }
    });
}

var handleOpen = function (conn) {
    InitUserList();
};

var handleTextMessage = function (message) {
    var from = message.from; //消息的发送者
    var mestype = message.type;//消息发送的类型是群组消息还是个人消息
    var messageContent = message.data; //文本消息体
    //TODO  根据消息体的to值去定位那个群组的聊天记录
    //var room = message.to;
    if (mestype == 'groupchat') {
    } else {
        var log = {
            UserName: from,
            Msg: messageContent.replace(/\n/g, "<br />"),
            Time: getLoacalDateString() + " " + getLoacalTimeString(),
            Ext: message.ext
        };
        AddChatLog(from, log);
        AddMsgInfo(from, log);
        SetUnReadCount(from);
    }
};

var handleEmotion = function (message) {
    var mestype = message.type;//消息发送的类型是群组消息还是个人消息
    if (mestype == 'groupchat') {
    } else {
        RecNoSupportMsg(message, "表情");
    }
};

var handlePictureMessage = function (message) {
    var mestype = message.type;//消息发送的类型是群组消息还是个人消息
    if (mestype == 'groupchat') {
    } else {
        RecNoSupportMsg(message, "图片");
    }
};

var handlePresence = function (message) {
};

var handleAudioMessage = function (message) {
    var mestype = message.type;//消息发送的类型是群组消息还是个人消息
    if (mestype == 'groupchat') {
    } else {
        RecNoSupportMsg(message, "音频");
    }
};

var handleLocationMessage = function (message) {
    var mestype = message.type;//消息发送的类型是群组消息还是个人消息
    if (mestype == 'groupchat') {
    } else {
        RecNoSupportMsg(message, "位置");
    }
};

var handleFileMessage = function (message) {
    var mestype = message.type;//消息发送的类型是群组消息还是个人消息
    if (mestype == 'groupchat') {
    } else {
        RecNoSupportMsg(message, "文件");
    }
};

var handleVideoMessage = function (message) {
    var mestype = message.type;//消息发送的类型是群组消息还是个人消息
    if (mestype == 'groupchat') {
    } else {
        RecNoSupportMsg(message, "视频");
    }
};

var handleRoster = function (message) {
    var mestype = message.type;//消息发送的类型是群组消息还是个人消息
    if (mestype == 'groupchat') {
    } else {
        RecNoSupportMsg(message, "好友");
    }
};

var handleError = function () {
    window.hxconn.stopHeartBeat(window.hxconn);
};

//登录环信
function LoginIM(userName, password, picurl) {
    window.un = userName;
    if (!window.hxconn)
        InitHxConn();
    //启动心跳
    if (window.hxconn.isOpened()) {
        window.hxconn.heartBeat(window.hxconn);
        InitUserList();
    } else {
        window.hxconn.open({
            user: userName,
            pwd: password,
            appKey: Easemob.im.config.appkey
        });
    }
    var user = GetUserInfo(userName);
    if (user.UserName == null) {
        if (picurl.length > 0) {
            var userList = [];
            if (window.localStorage.getItem(window.un) != null) {
                userList = eval('(' + window.localStorage.getItem(window.un) + ')');
            }
            user.UserName = userName;
            user.PicUrl = picurl;
            userList.push(user);
            window.localStorage.setItem(window.un, JSON.stringify(userList));
        } else {
            $.ajax({
                url: "/ajax/News/GetUserInfo.ashx?r=" + Math.random(),
                type: 'POST',
                dataType: 'json',
                timeout: 30000,
                error: function () {
                    alert("服务器繁忙，请稍后重试");
                },
                success: function (data) {
                    if (data.Success) {
                        var userList1 = [];
                        if (window.localStorage.getItem(window.un) != null) {
                            userList1 = eval('(' + window.localStorage.getItem(window.un) + ')');
                        }
                        user.UserName = userName;
                        user.PicUrl = data.Data.PicUrl;
                        userList1.push(user);
                        window.localStorage.setItem(window.un, JSON.stringify(userList1));
                    }
                }
            });
        }
    }
}

//发送文本
var sendText = function (msg, to, ext) {
    var options = {
        to: to,
        msg: msg,
        type: "chat",
        ext: ext
    };
    window.hxconn.sendTextMessage(options);
    var log = {
        UserName: window.un,
        Msg: msg.replace(/\n/g, "<br />"),
        Time: getLoacalDateString() + " " + getLoacalTimeString(),
        Ext: ext
    };
    AddChatLog(to, log);
    AddMsg(to, log);
};

//添加聊天记录
//other：聊天对象账号，log：聊天内容，是一个json对象
function AddChatLog(other, log) {
    var chatUser = {
        UserName: other
    };
    AddUserList(chatUser);

    var chatLog = [];
    if (window.localStorage.getItem(window.un + "_" + other) != null) {
        chatLog = eval('(' + window.localStorage.getItem(window.un + "_" + other) + ')');
    }
    while (chatLog.length >= 500) {
        chatLog.shift();
    }
    chatLog.push(log);
    window.localStorage.setItem(window.un + "_" + other, JSON.stringify(chatLog));
}

function AddUserList(user) {
    var userList = [];
    if (window.localStorage.getItem(window.un) != null) {
        userList = eval('(' + window.localStorage.getItem(window.un) + ')');
    }
    while (userList.length >= 50) {
        userList.shift();
    }
    var ol = "";
    var nickname = "";
    var unreadcount = 0;
    for (var i = 0; i < userList.length; i++) {
        if (userList[i].UserName == user.UserName) {
            ol = userList[i].PicUrl;
            nickname = userList[i].NickName;
            unreadcount = userList[i].UnReadCount;
            userList.splice(i, 1);
            break;
        }
    }
    if (nickname.length <= 0) {
        $.ajax({
            url: "http://www.bczp.cn/3g/v2/ajax/News/GetInfo.ashx?r=" + Math.random() + "&sn=" + user.UserName,
            type: 'GET',
            dataType: 'json',
            timeout: 30000,
            async: false,
            success: function (data) {
                nickname = data.NickName;
                ol = data.PicUrl;
            },
            error: function () {
                alert("服务器繁忙，请稍后重试");
            }
        });
    }
    user.NickName = nickname;
    user.PicUrl = ol;
    user.UnReadCount = unreadcount;
    userList.splice(0, 0, user);
    window.localStorage.setItem(window.un, JSON.stringify(userList));
}

//初始化点击事件
function InitClick() {
    $(".BoxUserList ul li").click(function () {
        $(this).addClass("Current").siblings().removeClass("Current");
        var username = $(".BoxUserList ul li[class*=Current]").attr("un");
        var nickname = $(".BoxUserList ul li[class*=Current]").attr("title");
        $(".MsgWindow").hide();
        $(".MsgWindow[un=" + username + "]").show();
        var userlink = GetUserLink(username);
        if ($(".MyNewsBox .BoxTop .TopName").html().length == 0) {
            var topLink = "<a href=\"" + userlink + "\" target=\"_blank\">" + nickname + "</a>";
            $(".MyNewsBox .BoxTop .TopName").html(topLink);
        }
        ClearUnReadCount(username);
        var lastchild = $(".MsgWindow[un=" + username + "] > div:last-child");
        if (lastchild.length > 0) {
            lastchild.get(0).scrollIntoView();
        }
        CloseUserList();
    });
    $(".BoxUserList ul li i").click(function () {
        var cun = $(".BoxUserList ul li[class*=Current]").attr("un");
        var un = $(this).parent().attr("un");
        $(".MsgWindow[un=" + un + "]").remove();
        $(this).parent().remove();
        if (cun == un)
            $("#TalkUser").html("");
        if (cun == un && $(".BoxUserList ul li").length > 0) {
            var f = $(".BoxUserList ul li").eq(0);
            f.addClass("Current");
            $(".MsgWindow").hide();
            $(".MsgWindow[un=" + f.attr("un") + "]").show();
            var userlink = GetUserLink(un);
            if ($(".MyNewsBox .BoxTop .TopName").html().length == 0) {
                var topLink = "<a href=\"" + userlink + "\" target=\"_blank\">" + f.attr("title") + "</a>";
                $(".MyNewsBox .BoxTop .TopName").html(topLink);
            }
        }
        var count = $(".BoxUserList ul li").length;
        $(".BoxUser .TopName").html("最近联系人(" + count + ")");
        var userList = [];
        if (window.localStorage.getItem(window.un) != null) {
            userList = eval('(' + window.localStorage.getItem(window.un) + ')');
        }
        for (var i = 0; i < userList.length; i++) {
            if (userList[i].UserName == un)
                userList.splice(i, 1);
        }
        window.localStorage.setItem(window.un, JSON.stringify(userList));
        window.localStorage.removeItem(window.un + "_" + un);
    });
}

//初始化联系人列表
function InitUserList() {
    var hasUnRead = false;
    var userList = eval('(' + window.localStorage.getItem(window.un) + ')');
    for (var i = 0; i < userList.length; i++) {
        if (userList[i].UserName != window.un) {
            CreateListAndMsgWindow(userList[i]);
        }
        if (userList[i].UnReadCount > 0)
            hasUnRead = true;
    }
    if (hasUnRead)
        if ($(".MyNewsBox").is(":hidden"))
            $(".MyNews").attr("class", "MyNews New");
}

//创建联系人列表和会话窗口
function CreateListAndMsgWindow(user) {
    var userlink = GetUserLink(user.UserName);
    if ($(".BoxUserList ul li[un=" + user.UserName + "]").length == 0) {
        var temp = $("#UserListTemplate").html();
        temp = temp.replace(/{New}/g, user.UnReadCount > 0 ? "New" : "");
        temp = temp.replace(/{Type}/g, user.UserName.indexOf("ent") == 0 ? " Ent" : "");
        temp = temp.replace(/{NickName}/g, user.NickName);
        temp = temp.replace(/{UserName}/g, user.UserName);
        temp = temp.replace(/{Logo}/g, user.PicUrl.length > 0 ? user.PicUrl : GetDefaultPic(user.UserName));
        temp = temp.replace(/{Url}/g, userlink);
        $(".BoxUserList ul").append(temp);
    }
    if ($(".BoxUserList ul li").length == 1)
        $(".BoxUserList ul li[un=" + user.UserName + "]").addClass("Current");
    var count = $(".BoxUserList ul li").length;
    $(".BoxUser .TopName").html("最近联系人(" + count + ")");
    if ($(".MsgWindow[un=" + user.UserName + "]").length == 0) {
        $(".BoxMsg").append("<div class=\"MsgWindow\" un=\"" + user.UserName + "\" lt=\"\"></div>");
    } else {
        $(".MsgWindow[un=" + user.UserName + "]").html("");
    }
    if ($(".MsgWindow").length == 1) {
        if ($(".MyNewsBox .BoxTop .TopName").html().length == 0) {
            var topLink = "<a href=\"" + userlink + "\" target=\"_blank\">" + user.NickName + "</a>";
            $("#TalkUser").html(topLink);
        }
        $(".MsgWindow[un=" + user.UserName + "]").show();
    }
    var chatLog = [];
    if (window.localStorage.getItem(window.un + "_" + user.UserName) != null) {
        chatLog = eval('(' + window.localStorage.getItem(window.un + "_" + user.UserName) + ')');
    }
    for (var i = 0; i < chatLog.length; i++) {
        AddMsg(user.UserName, chatLog[i]);
    }
    InitClick();
}

//加入对话列表
function AddTalkList(username) {
    var chatUser = {
        UserName: username
    };
    AddUserList(chatUser);
    if (window.hxconn.isOpened()) {
        InitUserList();
        ShowNewsBox();
        $(".MsgWindow").hide();
        $(".MsgWindow[un=" + username + "]").show();
        return;
    }
    $.ajax({
        url: "/ajax/News/GetUserInfo.ashx?r=" + Math.random() + "",
        type: 'POST',
        dataType: 'json',
        timeout: 30000,
        error: function () {
            alert("服务器繁忙，请稍后重试");
        },
        success: function (data) {
            if (data.Success) {
                LoginIM(data.Data.UserName, data.Data.Password, data.Data.PicUrl);
                ShowNewsBox();
                $(".MsgWindow").hide();
                $(".MsgWindow[un=" + username + "]").show();
            } else {
                location.href = "/login/JwLogin.aspx";
            }
        }
    });
}

//接收状态
function RecState() {
    var allunreadcount = 0;
    var userList = [];
    if (window.localStorage.getItem(window.un) != null) {
        userList = eval('(' + window.localStorage.getItem(window.un) + ')');
    }
    for (var i = 0; i < userList.length; i++) {
        if (userList[i].UserName != window.un)
            allunreadcount = allunreadcount + userList[i].UnReadCount;
    }
    $(".MyNewsBox .BoxTop n").html(allunreadcount > 99 ? 99 : allunreadcount);
    if (allunreadcount > 0) {
        if ($(".MyNewsBox").is(":hidden"))
            $(".MyNews").attr("class", "MyNews New");
        $(".MyNewsBox .BoxTop").attr("class", "BoxTop New");
    } else {
        $(".MyNewsBox .BoxTop").attr("class", "BoxTop");
    }
}

//初始化聊天窗口
function InitNewsBox() {
    $(".MyNewsBox").height(document.documentElement.clientHeight);
    $(".MyNewsBox .BoxMsg").height(document.documentElement.clientHeight - 100);
    $(".MyNewsBox .BoxTop .TopName").width(document.documentElement.clientWidth - 100);
    $(".MyNewsBox .BoxSend #inputMsg").width(document.documentElement.clientWidth - 130);
    $(".MyNewsBox .BoxUser .BoxUserList").height(document.documentElement.clientHeight - 50);
}

//显示聊天窗口
function ShowNewsBox() {
    $(".MyNews").hide();
    $(".fullscreen").hide();
    $("body").css({ "overflow-y": "hidden" });
    var width = $(".MyNewsBox").width();
    $(".MyNewsBox").css({ right: '-' + width + 'px' }).show().animate({ right: '0' }, 500);
}

//关闭聊天窗口
function CloseNewsBox() {
    $("html").css({ "overflow-y": "auto" });
    var width = $(".MyNewsBox").width();
    $(".MyNewsBox").animate({ right: '-' + width + 'px' }, 500, function () {
        $(".MyNewsBox").fadeOut();
    });
    $(".fullscreen").show();
    $(".MyNews").show();
}

//显示联系人列表
function ShowUserList() {
    var width = $(".BoxUser").width();
    $(".BoxUser").css({ right: '-' + width + 'px' }).show().animate({ right: '0' }, 500);
}

//关闭联系人列表
function CloseUserList() {
    var width = $(".BoxUser").width();
    $(".BoxUser").animate({ right: '-' + width + 'px' }, 500, function () {
        $(".BoxUser").fadeOut();
    });
}

function GetUserLink(userName) {
    var action = "jw";
    if (userName.indexOf("ent") == 0) {
        action = "ent";
    }
    var link;
    if (action == "jw") {
        link = "/ent/showresume.aspx?jwsn=" + userName.replace(action, "");
    } else {
        link = "/jw/showent_" + userName.replace(action, "") + ".aspx";
    }
    return link;
}

function GetUserInfo(userName) {
    var userList = [];
    if (window.localStorage.getItem(window.un) != null) {
        userList = eval('(' + window.localStorage.getItem(window.un) + ')');
    }
    var user = {};
    for (var i = 0; i < userList.length; i++) {
        if (userList[i].UserName == userName) {
            user = userList[i];
            break;
        }
    }
    return user;
}

function AddMsg(other, log) {
    if (log.Msg.length > 0) {
        var user = GetUserInfo(log.UserName);
        var temp = $("#MsgInfoTemplate").html();
        temp = temp.replace(/{Other}/g, log.UserName == window.un ? " Me" : "");
        temp = temp.replace(/{Logo}/g, user.PicUrl.length > 0 ? user.PicUrl : GetDefaultPic(user.UserName));

        if (log.Msg == "[名片]") {
            var userlink = GetUserLink(log.UserName);
            var msg = "";
            msg = "<a target=\"_blank\" href=\"" + userlink + "\" class=\"MyCard\"><div class=\"CardContent\">";
            msg += "<div class=\"UInfo\">";
            msg += "<div class=\"Item\">" + user.NickName + "</div>";
            msg += "<div class=\"Item\">" + log.Ext.jobtype + "</div>";
            msg += "<u>" + log.Ext.sex + "</u>";
            msg += "</div>";
            msg += "<div class=\"LinkBtn\">点击查看名片信息</div>";
            msg += "</div></a>";
            temp = temp.replace(/{Msg}/g, msg);
        } else {
            temp = temp.replace(/{Msg}/g, log.Msg);
        }

        var lastTime = $(".MsgWindow[un=" + other + "]").attr("lt");
        if (lastTime.length > 0) {
            if (lastTime.split(' ')[0] == log.Time.split(' ')[0]) {
                var last = new Date(lastTime.replace(/-/ig, '/'));
                var now = new Date(log.Time.replace(/-/ig, '/'));
                var m = parseInt(Math.abs(now - last) / 1000 / 60);
                if (m >= 1) {
                    $(".MsgWindow[un=" + other + "]").attr("lt", log.Time);
                    temp = temp.replace(/{Time}/g, log.Time.split(' ')[1]);
                } else {
                    temp = temp.replace(/{Time}/g, "");
                }
            } else {
                $(".MsgWindow[un=" + other + "]").attr("lt", log.Time);
                temp = temp.replace(/{Time}/g, log.Time);
            }
        } else {
            $(".MsgWindow[un=" + other + "]").attr("lt", log.Time);
            temp = temp.replace(/{Time}/g, log.Time);
        }

        $(".MsgWindow[un=" + other + "]").append(temp);
        var lastchild = $(".MsgWindow[un=" + other + "] > div:last-child");
        if (lastchild.length > 0) {
            lastchild.get(0).scrollIntoView();
        }
    }
}

function AddMsgInfo(other, log) {
    var userlink = GetUserLink(other);
    var user = GetUserInfo(other);
    if ($(".BoxUserList ul li[un=" + other + "]").length == 0) {
        var userTemp = $("#UserListTemplate").html();
        userTemp = userTemp.replace(/{NickName}/g, user.NickName);
        userTemp = userTemp.replace(/{Type}/g, user.UserName.indexOf("ent") == 0 ? " Ent" : "");
        userTemp = userTemp.replace(/{UserName}/g, other);
        userTemp = userTemp.replace(/{Logo}/g, user.PicUrl.length > 0 ? user.PicUrl : GetDefaultPic(user.UserName));
        userTemp = userTemp.replace(/{Url}/g, userlink);
        $(".BoxUserList ul").prepend(userTemp);
    } else {
        var li = $(".BoxUserList ul li[un=" + other + "]");
        li.remove();
        $(".BoxUserList ul").prepend(li);
    }

    if ($(".BoxUserList ul li").length == 1)
        $(".BoxUserList ul li[un=" + other + "]").addClass("Current");
    var count = $(".BoxUserList ul li").length;
    $(".BoxUser .TopName").html("最近联系人(" + count + ")");
    if ($(".MsgWindow[un=" + other + "]").length == 0) {
        $(".BoxMsg").append("<div class=\"MsgWindow\" un=\"" + other + "\" lt=\"\"></div>");
    }
    if ($(".MsgWindow").length == 1) {
        if ($(".MyNewsBox .BoxTop .TopName").html().length == 0) {
            var topLink = "<a href=\"" + userlink + "\" target=\"_blank\">" + user.NickName + "</a>";
            $("#TalkUser").html(topLink);
        }
        $(".MsgWindow[un=" + other + "]").show();
    }

    AddMsg(other, log);
    InitClick();
}

function SetUnReadCount(username) {
    if ($(".MsgWindow[un=" + username + "]").css("display") == "none") {
        var unreadcount = 0;
        var userList = [];
        if (window.localStorage.getItem(window.un) != null) {
            userList = eval('(' + window.localStorage.getItem(window.un) + ')');
        }
        for (var i = 0; i < userList.length; i++) {
            if (userList[i].UserName == username) {
                userList[i].UnReadCount = userList[i].UnReadCount + 1;
                unreadcount = userList[i].UnReadCount;
            }
        }
        window.localStorage.setItem(window.un, JSON.stringify(userList));
        $(".BoxUserList ul li[un=" + username + "]").addClass("New");
        $(".BoxUserList ul li[un=" + username + "] u").html(unreadcount > 99 ? 99 : unreadcount);
    }
    RecState();
}

function ClearUnReadCount(username) {
    var userList = [];
    if (window.localStorage.getItem(window.un) != null) {
        userList = eval('(' + window.localStorage.getItem(window.un) + ')');
    }
    for (var i = 0; i < userList.length; i++) {
        if (userList[i].UserName == username) {
            userList[i].UnReadCount = 0;
        }
    }
    window.localStorage.setItem(window.un, JSON.stringify(userList));
    $(".BoxUserList ul li[un=" + username + "]").removeClass("New");
    RecState();
}

function RecNoSupportMsg(message, action) {
    var from = message.from;
    var log = {
        UserName: from,
        Msg: "暂不支持接收" + action + "消息，<a target='_blank' href='/download.aspx'>点击下载App查看</a>",
        Time: getLoacalDateString() + " " + getLoacalTimeString(),
        Ext: message.ext
    };
    AddChatLog(from, log);
    AddMsgInfo(from, log);
    SetUnReadCount(from);
}

function GetDefaultPic(username) {
    var action = "jw";
    if (username.indexOf("ent") == 0) {
        action = "ent";
    }
    var picurl = action == "jw" ? "/images/JwDefLogo.png" : "/images/EntDefLogo.png";
    return picurl;
}