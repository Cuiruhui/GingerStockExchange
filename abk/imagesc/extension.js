(function() {
	"use strict";
	//if exist return, else construct
	if(!window.comcgm) {
		window.comcgm = {};
	}
	if(!window.comcgm.extension) {
		window.comcgm.extension = {};
	}
	if("undefined" !== typeof window.comcgm.extension.loopdomain) {
		return ;
	}

	var that = {
		baseUrl: "http://static.caigoumi.com",
		rules: {
			shopping: /^http(s)?:\/\/(.*)\.(taobao|tmall)\.com/i,
			blacklist: /^http(s)?:\/\/(.*)\.(aliway|alimama|alibaba-inc|v2ex|zuanke8|github)\.com/i,
			mall: /^http(s)?:\/\/(.*\.)?(360buy\.com|jd\.com|dangdang\.com|yihaodian\.com|amazon\.cn|yintai\.com|paipai\.com|xinbaigo\.com|yinzuo100\.com|anportshop\.com|usashopcn\.com|51youpin\.com|oulele\.com|kypbuy\.com|suning\.com|1mall\.com|quwan\.com|m18\.com|d1\.com\.cn|hitao\.com|qqgshop\.com|pinstore\.cn|ule\.com|k\.cn|jxmall\.com|xjh\.com|wangfujing\.com|buysku\.com|kuaishubao\.com|jajn\.com|ujipin\.com|chnimport\.com|shop\.boqii\.com|blzoom\.com|blemall\.com|lhmart\.com|jianianle\.com|wowsai\.com|epetbar\.com|igrandbuy\.com|yksuit\.com|nop\.cn|bosideng\.cn|tonlion\.com|agcatton\.com|gaojie\.com|sportica\.cn|banggo\.com|mfplaza\.com|handuyishe\.com|j1923\.com|onlyts\.cn|vancl\.com|vjia\.com|moonbasa\.com|masamaso\.com|yohobuy\.com|pupai\.cn|pb89\.com|justyle\.com|osa\.com\.cn|gap\.cn|giordano\.com|hodo\.cn|esprit\.cn|ikappa\.com\.cn|qipaimall\.com|liebo\.com|winjeans\.com|8090meizhuang\.com|maoer360\.com|nzmall\.cn|junph\.com|gewei\.com|parkson\.com\.cn|girdear\.cn|misun\.cn|feissi\.com|hyj\.com|9wow\.com\.cn|xtep\.com\.cn|yougou\.com|utcbag\.com|uelux\.com|ezeroshop\.com|aidai\.com|uiyi\.cn|jpeen\.com|htjz\.com|e-lining\.com|chris-tina\.com|paixie\.net|mbaobao\.com|keede\.com|vsigo\.cn|vsnoon\.com|s\.cn|idx\.com\.cn|zgshoes\.com|sky-view\.cn|bagtree\.com|maibaoqu\.cn|anta\.cn|51hqt\.com|maimaix\.cn|easeeyes\.com|longfeng\.com|trioo\.com|ailiebe\.com|camel\.com\.cn|51wagu\.com|meijing\.com|m\.rax\.cn|rax\.cn|myxiequ\.com|yichao\.cn|meixiu\.com|strawberrynet\.com|pba\.cn|yingtao\.me|naruko\.com\.cn|nala\.com\.cn|lefeng\.com|xifuquan\.com|no5\.com\.cn|fashionls\.com|shop\.lumilady\.com|tiantian\.com|dhc\.net\.cn|meitianyijian\.com|fubaihui\.com|bedook\.cn|sasa\.com|rosheskin\.cn|uzise\.com|jollymm\.com|lzwg\.com|chinaskin\.cn|milipp\.com|milier\.com|fenixmall\.com|xiaoye\.com|hmeili\.com|xzhuang\.com|vzi800\.cn|sephora\.cn|xiangshe\.com|zm7\.cn|new7\.com|tao3c\.com|gome\.com\.cn|yidianda\.com|newegg\.com\.cn|coo8\.com|51buy\.com|mmloo\.com|hicdma\.com|shop\.coolpad\.cn|ahongmall\.com|shop\.lenovo\.com\.cn|mya123\.com|shopping1\.hp\.com|ingping\.com|mobi189\.com|bedove\.net|ehaier\.com|dakele\.com|vhappybuy\.com|xiaomi\.com|vmall\.com|store\.logitech\.com\.cn|okhqb\.com|uya100\.com|purcotton\.com|newx\.cn|lovo\.cn |liwai\.com|bblfloor\.com|mmall\.com|comagic\.cn|yuegoo\.cn|dapu\.com|jiayougo\.com|gaole\.com|51tt\.com\.cn|beyond\.cn|239buy\.com|zgjf168\.com|lifevc\.com|royaldesign\.com|umanto\.com|vegaga\.com|supuy\.com|shop\.yunfubao\.com|baiyjk\.com|leyou\.com\.cn|muyingzhijia\.com|m6go\.com|baobeigou\.com|haohaizi\.com|anngo\.net|motherbuy\.com|234\.cn|wuyang\.cn|51ibaby\.com|kissbb\.com|tootoo\.cn|didamall\.com|lecake\.com|womai\.com|wine9\.com|jiuxian\.com|100wine\.com|lingshi\.com|nonmin88\.com|maimaicha\.com|giftport\.com\.cn|gjw\.com|winenice\.com|yesmywine\.com|hecha\.cn|wfboy\.com|9yuejiu\.com|fieldschina\.com|6666688888\.com|sfbest\.com|tonicare\.cn|nongren\.com|gdcct\.com|crabchina\.com|maidangao\.com|wangjiu\.com|21cake\.com|najue\.com|shi78\.com|yaohongjiu\.com|wellness-online\.com\.cn|benlai\.com|maichawang\.com|zuipin\.cn|ouweiduo\.com|ganso\.com\.cn|tastev\.com|yiguo\.com|fuwa\.com|yummy77\.com|laiyifen\.com|gongtianxia\.com|lvmama\.com|mipang\.com|qmango\.com|flight\.mangocity\.com|zhuna\.cn|wangpiao\.com|uzai\.com|flight\.etpass\.com|u\.ctrip\.com|travel\.elong\.com|e\.7daysinn\.cn|hotelhotel\.cn|muchm\.com|jinjiang\.com|springtour\.com|feiren\.com|99inn\.cc|wyn88\.com|228\.com\.cn|tujia\.com|998\.com|trip8\.com|1youku\.com|china-cbs\.com|wandafilm\.com|qtour\.com|17u\.cn|kuxun\.com|12580\.10086\.cn|aoyou\.com|yododo\.cn|chncpa\.org|maizuo\.com|podinns\.com|tj\.tieyou\.com|misstuan\.com|glituan\.com|d8wed\.com|nuomi\.com|sheyingtg\.com|liketuan\.com|pinkeka\.com|go\.cn|51remai\.com|tuan\.xiu\.com|shuangtuan\.com|t\.dianping\.com|jumei\.com|55tuan\.com|lashou\.com|17mh\.com|manzuo\.com|miqi\.cn|bj\.meituan\.com|gantuan\.com|zhiwo\.com|didatuan\.com|t\.58\.com|shop\.vipshop\.com|bbhun\.com|zpsoso\.com|tuanweihui\.com|awotuan\.com|juqi\.com|gaopeng\.qq\.com|gaopeng\.com|wkol\.cn|365h\.com|napai\.cn|qiibii\.com|mxgogo\.com|vetuan\.com|5173tuan\.com|dongxintuan\.com|haotaotuan\.com|miwei\.com|qiaopier\.com|xilituan\.com|aolaituan\.com|88legou\.cn|hongxiutuan\.net|juyuewang\.com|weimeituan\.com|tuanlego\.com|tuan\.maizuo\.com|77zuo\.com|qinqin\.net|pztuan\.com|tuan\.bangzhufu\.com|xiugou\.com|sifangtuan\.com|hebaotuan\.com|linglongtuan\.com|tuan\.27\.cn|taohv\.cn|7cv\.com|aizhigu\.com\.cn|oyeah\.com\.cn|x-playboy\.com|x\.com\.cn|qingaige\.cn|panduola\.com|2155\.com|bookuu\.com|wl\.cn|bookschina\.com|china-pub\.com|winxuan\.com|book\.beifabook\.com|shop\.edu\.cn|zazhipu\.com|dooland\.com|taoshu\.com|spider\.com\.cn|book\.huatu\.com|glamour-sales\.com\.cn|uemall\.com|zhenpin\.com|secoo\.com|buyfine\.net|shangpin\.com|xiu\.com|5lux\.com|aolai\.com|meici\.com|saite\.com|dada360\.com|huihao\.com|yaofang\.cn|818\.com|360kxr\.com|800pharm\.com|jxdyf\.com|7cha\.com|360kad\.com|rmttjkw\.com|huatuoyf\.com|bjypw\.com|7lk\.cn|yowal\.com|10000yao\.com|111\.com\.cn|cocochong\.com|qianyecao\.com|21yod\.com|cptshop\.com|shop\.bhc888\.com|kzj365\.com|j1\.com|yiliu88\.com|111yao\.com|xiangqinyw\.com|91yao\.com|jianke\.com|jjlg\.com\.cn|ikang\.com|12yao\.com|star365\.com|kmzjw\.org|daoyao\.com|99vk\.com|haoshili\.com\.cn|lvshou\.com|ediy\.com|yxp\.163\.com|hua\.com|kadang\.com|flower-easy\.com|xipin\.me|aidibao\.cc|liwuyou\.com|yofus\.com|wodinghua\.com|gmecity\.com|cnyu\.com|oohdear\.com|hxepawn\.com|95buy\.com|time100\.cn|wdzhubao\.com|detime\.com|tian10\.net|binlun\.com\.cn|24kjx\.com|jufengshang\.com|ppbiao\.com|wsyu\.com|ctfeshop\.com\.cn|kela\.cn|wbiao\.cn|zocai\.com |wozhongla\.com|darengong\.com|baoxian\.com|woxiu\.com|f\.youdao\.com|webitrader\.com|5173\.com|8791\.com|zhubajie\.com|5biying\.com|gaitu\.com|zj\.189\.cn|tuan\.qunar\.com|kaixinbao\.com|xinnet\.com|365fanyi\.com|qfpay\.com|gonpo\.cn|cpdyj\.com|128cai\.com|longquan-baojian\.com|kuaipan\.cn|198tc\.com|mayi\.com|staples\.cn|koolearn\.com|jiandan100\.cn|51talk\.com|teatreexy\.com|zhudou\.com|v\.umiwi\.com|class\.dict\.cn|dezhi\.com|kuakao\.net|v\.huatu\.com|jinghua\.com|m360\.com\.cn|hi-tec\.cn|xoutshop\.cn|yoger\.com\.cn|kouclo\.com|shopin\.net|fclub\.cn|ga\.tianpin\.com|manyalittle\.com|cnrmall\.com|17ugo\.com|ocj\.com\.cn|chinadrtv\.com|happigo\.com|hao24\.cn|aimer\.com\.cn|lamiu\.com|xzyd\.net|yeardear\.com|chesudi\.com|toowell\.com|mchepin\.com|chebao360\.com|z\.cn|coolpad\.cn|7daysinn\.cn|boqii\.com|lumilady\.com|lenovo\.com|hp\.com|logitech\.com|yunfubao\.com|mangocity\.com|etpass\.com|ctrip\.com|elong\.com|tieyou\.com|dianping\.com|meituan\.com|vipshop\.com|bangzhufu\.com|beifabook\.com|huatu\.com|bhc888\.com|163\.com|qunar\.com|umiwi\.com|tianpin\.com)/i,
			other: /^http(s)?:\/\/.*\/.*/i
		},
		utils: {
			A: function(a, b) {
				if (typeof b == "string") {
					a.innerHTML = b;
				} else {
					a.appendChild(b);
				}
				return b;
			},
			C: function(name) {
				return document.createElement(name);
			},			
			T: function(name, no) {
				return document.getElementsByTagName(name)[no];
			},
			D: function(id) {
				var obj = document.getElementById(id);
				obj.parentNode.removeChild(obj);
			},
			addScript: function(content, inline, callback, async) {
				var head = that.utils.T("head", 0);
				var script = that.utils.C("script");
				script.type = "text/javascript";
				script.charset = "utf-8";
				if (inline) {
					script.text = content;
				} else {
					if (async) {
						script.setAttribute("defer", "");
						script.setAttribute("async", "true");
					}
					script.onload = script.onreadystatechange = function() {
						if (script.readyState && script.readyState != "loaded" && script.readyState != "complete") {
							return;
						} else {
							if (callback) {
								callback();
							}
							script.onload = script.onreadystatechange = null;
						}
					}
					script.src = content;
				}				
				that.utils.A(head, script);
				if (inline && callback) {
					callback();
				}
			},
			getSuffix: function() {
				var d = new Date();
				var suffix = '?t=' + d.getFullYear() + (d.getMonth() + 1) + d.getDate();
				return suffix;
			},
			getScriptPath: function(path, params) {
				var url = that.baseUrl + path + that.utils.getSuffix();
				if(params) {
					for(var key in params) {
						url += "&" + key + "=" + params[key];
					}
				}
				return url;
			},
			parseURL: function(url){
				var a = document.createElement("a");
				a.href = url;
				return {
					source: url,
					protocol: a.protocol.replace(":", ""),
					host: a.hostname,
					port: a.port,
					query: a.search,
					params: (function(){
						var ret = {}, seg = a.search.replace(/^\?/, "").split("&"), len = seg.length, i = 0, s;
						for (; i < len; i++) {
							if (!seg[i]) {
								continue;
							}
							s = seg[i].split("=");
							ret[s[0]] = s[1];
						}
						return ret;
					})(),
					file: (a.pathname.match(/\/([^\/?#]+)$/i) || [, ""])[1],
					hash: a.hash.replace("#", ""),
					path: a.pathname.replace(/^([^\/])/, "/$1"),
					relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [, ""])[1],
					segments: a.pathname.replace(/^\//, "").split("/")
				};
			}
		},
		urlMatch: function(url) {
			for (var module in that.rules) {
				var pattern = that.rules[module];
				//console.log(url.match(pattern));
				//console.log(matches);
				if (pattern.test(url)) {
					var rt = {
						module: module
					};
					return rt;
				}
			}
			return false;
		},
		init: function(url) {
			var params = that.utils.parseURL(url);
			url = params.protocol + "://" + params.host;
			var rt = that.urlMatch(url);
			var module = rt.module;
			//console.log(rt, module);
			var script = false;
			switch(module) {
				case 'shopping':
				case 'blacklist':
					script = that.utils.getScriptPath("/api/loadjs/", {r: encodeURIComponent(url)});
					break;
				case 'mall':
					script = that.utils.getScriptPath("/js/extension/mall.js");
					break;
				case 'other':
				defalut: 
					break;
			}
			if(script) {
				that.utils.addScript(script, false, false, true);
			}
		}
	}
	that.init(window.location.href);

	window.comcgm.extension.loopdomain = that;
})();