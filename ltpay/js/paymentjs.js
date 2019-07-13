!function (e) {
    if ("object" == typeof exports && "undefined" != typeof module) module.exports = e(); else if ("function" == typeof define && define.amd) define([], e); else {
        var n;
        n = "undefined" != typeof window ? window : "undefined" != typeof global ? global : "undefined" != typeof self ? self : this, n.paymentjs = e()
    }
}(function () {
    return function e(n, t, r) {
        function a(o, c) {
            if (!t[o]) {
                if (!n[o]) {
                    var u = "function" == typeof require && require;
                    if (!c && u)return u(o, !0);
                    if (i)return i(o, !0);
                    var l = new Error("Cannot find module '" + o + "'");
                    throw l.code = "MODULE_NOT_FOUND", l
                }
                var s = t[o] = {exports: {}};
                n[o][0].call(s.exports, function (e) {
                    var t = n[o][1][e];
                    return a(t ? t : e)
                }, s, s.exports, e, n, t, r)
            }
            return t[o].exports
        }

        for (var i = "function" == typeof require && require, o = 0; o < r.length; o++)a(r[o]);
        return a
    }({
        1: [function (e, n, t) {
            n.exports = {
                userCallback: void 0, innerCallback: function (e, n) {
                    'function' == typeof this.userCallback && ('undefined' == typeof n && (n = this.error()), this.userCallback(e, n), this.userCallback = void 0)
                }, error: function (e, n) {
                    return e = 'undefined' == typeof e ? '' : e, n = 'undefined' == typeof n ? '' : n, {
                        msg: e,
                        extra: n
                    }
                }
            }
        }, {}], 2: [function (e, n, t) {
            var r = {}.hasOwnProperty, a = {};
            n.exports = a, a.channels = {
                sand_h5: e('./channel/sand_h5'),
                sand_pc: e('./channel/sand_pc'),
                wx_pub: e('./channel/wx_pub'),
                ali_pub: e('./channel/ali_pub'),
                bank_pc: e('./channel/bank_pc')
            }, a.extras = {ap: e('./channel/extras/ap')}, a.getChannelModule = function (e) {
                if (r.call(a.channels, e))return a.channels[e]
            }, a.getExtraModule = function (e) {
                if (r.call(a.extras, e))return a.extras[e]
            }
        }, {
            "./channel/ali_pub": 3,
            "./channel/bank_pc": 4,
            "./channel/extras/ap": 5,
            "./channel/sand_h5": 6,
            "./channel/sand_pc": 7,
            "./channel/wx_pub": 8
        }], 3: [function (e, n, t) {
            var r = e('../callbacks'), a = (e('../utils'), e('../stash')), i = (e('../channelMod'), {}.hasOwnProperty);
            n.exports = {
                handleCharge: function (e) {
                    var n = e.params;
                    a.jsApiParameters = n, this.aliAPICall()
                }, aliAPICall: function () {
                    i.call(a, 'jsApiParameters') && AlipayJSBridge.call("tradePay", {tradeNO: a.jsApiParameters.tradeNO}, function (e) {
                        r.innerCallback(e)
                    })
                }
            }
        }, {"../callbacks": 1, "../channelMod": 2, "../stash": 10, "../utils": 11}], 4: [function (e, n, t) {
            var r = e('../utils');
            ({}).hasOwnProperty;
            n.exports = {
                handleCharge: function (e) {
                    var n = e.params, t = e.submitUrl;
                    r.formSubmit(t, 'post', n)
                }
            }
        }, {"../utils": 11}], 5: [function (e, n, t) {
            var r = e('../../stash'), a = {}.hasOwnProperty;
            !function () {
                var e = {}, t = {};
                t.PADCHAR = '=', t.ALPHA = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/', t.makeDOMException = function () {
                    try {
                        return new DOMException(DOMException.INVALID_CHARACTER_ERR)
                    } catch (n) {
                        var e = new Error('DOM Exception 5');
                        return e.code = e.number = 5, e.name = e.description = 'INVALID_CHARACTER_ERR', e.toString = function () {
                            return 'Error: ' + e.name + ': ' + e.message
                        }, e
                    }
                }, t.getbyte64 = function (e, n) {
                    var r = t.ALPHA.indexOf(e.charAt(n));
                    if (r === -1)throw t.makeDOMException();
                    return r
                }, t.decode = function (e) {
                    e = '' + e;
                    var n, r, a, i = t.getbyte64, o = e.length;
                    if (0 === o)return e;
                    if (o % 4 !== 0)throw t.makeDOMException();
                    n = 0, e.charAt(o - 1) === t.PADCHAR && (n = 1, e.charAt(o - 2) === t.PADCHAR && (n = 2), o -= 4);
                    var c = [];
                    for (r = 0; r < o; r += 4)a = i(e, r) << 18 | i(e, r + 1) << 12 | i(e, r + 2) << 6 | i(e, r + 3), c.push(String.fromCharCode(a >> 16, a >> 8 & 255, 255 & a));
                    switch (n) {
                        case 1:
                            a = i(e, r) << 18 | i(e, r + 1) << 12 | i(e, r + 2) << 6, c.push(String.fromCharCode(a >> 16, a >> 8 & 255));
                            break;
                        case 2:
                            a = i(e, r) << 18 | i(e, r + 1) << 12, c.push(String.fromCharCode(a >> 16))
                    }
                    return c.join('')
                }, t.getbyte = function (e, n) {
                    var r = e.charCodeAt(n);
                    if (r > 255)throw t.makeDOMException();
                    return r
                }, t.encode = function (e) {
                    if (1 !== arguments.length)throw new SyntaxError('Not enough arguments');
                    var n, r, a = t.PADCHAR, i = t.ALPHA, o = t.getbyte, c = [];
                    e = '' + e;
                    var u = e.length - e.length % 3;
                    if (0 === e.length)return e;
                    for (n = 0; n < u; n += 3)r = o(e, n) << 16 | o(e, n + 1) << 8 | o(e, n + 2), c.push(i.charAt(r >> 18)), c.push(i.charAt(r >> 12 & 63)), c.push(i.charAt(r >> 6 & 63)), c.push(i.charAt(63 & r));
                    switch (e.length - u) {
                        case 1:
                            r = o(e, n) << 16, c.push(i.charAt(r >> 18) + i.charAt(r >> 12 & 63) + a + a);
                            break;
                        case 2:
                            r = o(e, n) << 16 | o(e, n + 1) << 8, c.push(i.charAt(r >> 18) + i.charAt(r >> 12 & 63) + i.charAt(r >> 6 & 63) + a)
                    }
                    return c.join('')
                }, e.url = 'pay.htm', e.pay = function (n) {
                    var i = encodeURIComponent(t.encode(n));
                    a.call(r, 'APURL') && (e.url = r.APURL), location.href = e.url + '?goto=' + i
                }, e.decode = function (e) {
                    return t.decode(decodeURIComponent(e))
                }, n.exports = e
            }()
        }, {"../../stash": 10}], 6: [function (e, n, t) {
            var r = e('../utils');
            ({}).hasOwnProperty;
            n.exports = {
                handleCharge: function (e) {
                    var n = e.params, t = e.submitUrl;
                    r.redirectTo(t + '?params=' + n)
                }
            }
        }, {"../utils": 11}], 7: [function (e, n, t) {
            arguments[4][4][0].apply(t, arguments)
        }, {"../utils": 11, dup: 4}], 8: [function (e, n, t) {
            var r = e('../callbacks'), a = (e('../utils'), e('../stash')), i = e('../channelMod'),
                o = {}.hasOwnProperty;
            n.exports = {
                handleCharge: function (e) {
                    for (var n = e.params, t = ['appId', 'timeStamp', 'nonceStr', 'package', 'signType', 'paySign'], i = 0; i < t.length; i++)if (!o.call(n, t[i]))return void r.innerCallback('fail', r.error('invalid_credential', 'missing_field_' + t[i]));
                    a.jsApiParameters = n, this.callpay()
                }, callpay: function () {
                    var e = this, n = i.getExtraModule('wx_jssdk');
                    if ('undefined' != typeof n && n.jssdkEnabled()) n.callpay(); else if ('undefined' == typeof WeixinJSBridge) {
                        var t = function () {
                            e.jsApiCall()
                        };
                        document.addEventListener ? document.addEventListener('WeixinJSBridgeReady', t, !1) : document.attachEvent && (document.attachEvent('WeixinJSBridgeReady', t), document.attachEvent('onWeixinJSBridgeReady', t))
                    } else this.jsApiCall()
                }, jsApiCall: function () {
                    o.call(a, 'jsApiParameters') && WeixinJSBridge.invoke('getBrandWCPayRequest', a.jsApiParameters, function (e) {
                        delete a.jsApiParameters, 'get_brand_wcpay_request:ok' == e.err_msg ? r.innerCallback('success') : 'get_brand_wcpay_request:cancel' == e.err_msg ? r.innerCallback('cancel') : r.innerCallback('fail', r.error('wx_result_fail', e.err_msg))
                    })
                }
            }
        }, {"../callbacks": 1, "../channelMod": 2, "../stash": 10, "../utils": 11}], 9: [function (e, n, t) {
            var r = e('./callbacks'), a = e('./channelMod'), i = {}.hasOwnProperty, PaymentSDK = function () {
            };
            PaymentSDK.prototype = {
                createPayment: function (e, n) {
                    'function' == typeof n && (r.userCallback = n);
                    var t;
                    if ('string' == typeof e)try {
                        t = new Function("return " + e)()
                    } catch (e) {
                        return void r.innerCallback('fail', r.error('json_decode_fail', e))
                    } else t = e;
                    if ('undefined' == typeof t)return void r.innerCallback('fail', r.error('json_decode_fail'));
                    var o = t.payMode;
                    if (!i.call(t, 'params'))return void r.innerCallback('fail', r.error('invalid_charge', 'no_params'));
                    var c = a.getChannelModule(o);
                    c.handleCharge(t)
                }
            }, n.exports = new PaymentSDK
        }, {"./callbacks": 1, "./channelMod": 2}], 10: [function (e, n, t) {
            n.exports = {}
        }, {}], 11: [function (e, n, t) {
            var r = {}.hasOwnProperty, a = n.exports = {
                stringifyData: function (e, n, t) {
                    'undefined' == typeof t && (t = !1);
                    var a = [];
                    for (var i in e)r.call(e, i) && 'function' != typeof e[i] && ('bfb_wap' == n && 'url' == i || 'yeepay_wap' == n && 'mode' == i || 'channel_url' != i && a.push(i + '=' + (t ? encodeURIComponent(e[i]) : e[i])));
                    return a.join('&')
                }, request: function (e, n, t, i, o, c) {
                    if ('undefined' != typeof XMLHttpRequest) {
                        var u = new XMLHttpRequest;
                        if ('undefined' != typeof u.timeout && (u.timeout = 6e3), n = n.toUpperCase(), 'GET' === n && 'object' == typeof t && t && (e += '?' + a.stringifyData(t, '', !0)), u.open(n, e, !0), 'undefined' != typeof c)for (var l in c)r.call(c, l) && u.setRequestHeader(l, c[l]);
                        'POST' === n ? (u.setRequestHeader('Content-type', 'application/json; charset=utf-8'), u.send(JSON.stringify(t))) : u.send(), 'undefined' == typeof i && (i = function () {
                        }), 'undefined' == typeof o && (o = function () {
                        }), u.onreadystatechange = function () {
                            4 == u.readyState && i(u.responseText, u.status, u)
                        }, u.onerror = function (e) {
                            o(u, 0, e)
                        }
                    }
                }, formSubmitNew: function (e, n, t) {
                    if ('undefined' != typeof window) {
                        var a = document.createElement('form');
                        a.setAttribute('method', n), a.setAttribute('action', e), a.setAttribute('target', '_blank');
                        for (var i in t)if (r.call(t, i)) {
                            var o = document.createElement('input');
                            o.setAttribute('type', 'hidden'), o.setAttribute('name', i), o.setAttribute('value', t[i]), a.appendChild(o)
                        }
                        document.body.appendChild(a), a.submit()
                    }
                }, formSubmit: function (e, n, t) {
                    if ('undefined' != typeof window) {
                        var a = document.createElement('form');
                        a.setAttribute('method', n), a.setAttribute('action', e);
                        for (var i in t)if (r.call(t, i)) {
                            var o = document.createElement('input');
                            o.setAttribute('type', 'hidden'), o.setAttribute('name', i), o.setAttribute('value', t[i]), a.appendChild(o)
                        }
                        document.body.appendChild(a), a.submit()
                    }
                }, randomString: function (e) {
                    'undefined' == typeof e && (e = 32);
                    for (var n = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', t = n.length, r = '', a = 0; a < e; a++)r += n.charAt(Math.floor(Math.random() * t));
                    return r
                }, redirectTo: function (e) {
                    'undefined' != typeof window && (window.location.href = e)
                }, documentReady: function (e) {
                    return 'undefined' == typeof document ? void e() : void('loading' != document.readyState ? e() : document.addEventListener('DOMContentLoaded', e))
                }
            }
        }, {}]
    }, {}, [9])(9)
});
//# sourceMappingURL=paymentjs.js.map
