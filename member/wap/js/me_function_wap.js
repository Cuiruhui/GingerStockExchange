//公告轮显示
function news(obj){
	clearTimeout(news_s);
	$(obj).find("ul:first").animate({
		marginTop:"-25px"
	},500,function(){
		$(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
	});
	news_s=window.setTimeout('news("#news")', 10000);
}
function show_time()
{
	clearTimeout(show_s);
	$.ajax({
		url:'showTime.php?rand='+Math.random(),
		type:'GET',
		timeout:5000,
		success:function(data){
			$('#liveTime').html(data);
		}
	});
	show_s=window.setTimeout('show_time()', 5000);
}

function ajaxUrl(url)
{
	var res;
	$.ajax({
		url: url,
		type:'GET',
		timeout:5000,
		async:false,
		success:function(data){
			if(data==undefined || data=='')
				res = '';
			else
				res = data;
		}
	});
	return res;
}

function Trim(str) {
	return str.replace(/(^\s*)|(\s*$)/g, "");
}

/*
interval ：D表示查询精确到天数的之差
interval ：H表示查询精确到小时之差
interval ：M表示查询精确到分钟之差
interval ：S表示查询精确到秒之差
interval ：T表示查询精确到毫秒之差
*/
function dateDiff(interval, date1, date2)
{
	var objInterval = {'D' : 1000 * 60 * 60 * 24, 'H' : 1000 * 60 * 60, 'M' : 1000 * 60, 'S' : 1000, 'T' : 1};
	interval = interval.toUpperCase();
	try
	{
		return Math.round((date2 - date1) / ('(objInterval.' + interval + ')'));
	}
	catch (e)
	{
		return e.message;
	}
} 

//综合指数
function refresh_index()
{
	clearTimeout(userinf_s);
	$.ajax({
		url:'user_info.php?rand='+Math.random(),
		type:'GET',
		timeout:5000,
		success:function(data){
			if(data==undefined || data=='')
			 	return false;
			var mdata = data.split('|');
			var dps = mdata[0];
			stockOpen = mdata[1];
			var dp_stock = dps.split('#');
			for(var i=0; i<2; i++){
				var dp = dp_stock[i];
				var dp_code = dp.split(',')[0];    //代码
				var dp_name = dp.split(',')[1];    //名称
				var dp_yest = dp.split(',')[3];    //昨日收盘
				var dp_curr = dp.split(',')[4];    //当前指数
				var dp_diff = dp_curr - dp_yest;   //价差
				var dp_turnover= dp.split(',')[10]/100000000; //成交金额(亿)
				$('#dpName_'+i).text(dp_name+': ');
				if(dp_curr){
					$('#dpCurr_'+i).html(dp_curr);
					if(parseFloat(dp_curr) > parseFloat(dp_yest)) {
						$('#dpCurr_'+i).css({ color: "#ff0011",'font-weight':"bold"});
					} else if(parseFloat(dp_curr) < parseFloat(dp_yest)) {
						$('#dpCurr_'+i).css({ color: "green",'font-weight':"bold"});
					}
				}
				$('#dp_turnover_'+i).html(dp_turnover.toFixed(2)+"亿元");
				if(parseFloat(dp_diff) > 0) {
					$('#dpDiff_'+i).html("\涨 <font color='#FB0B0B'\>" + dp_diff.toFixed(2) + "\<\/font\>");
					$('#dbPoint_'+i).html("\<font color='#FB0B0B'\>" + ((dp_diff / dp_yest) * 100).toFixed(2) + "％\<\/font\>");
				} else if(parseFloat(dp_diff) < 0) {
					$('#dpDiff_'+i).html("\跌 <font color='green'\>" + dp_diff.toFixed(2) + "\<\/font\>");
					$('#dbPoint_'+i).html("\<font color='green'\>" + ((dp_diff / dp_yest) * 100).toFixed(2) + "％\<\/font\>");
				} else {
					$('#dpDiff_'+i).html(dp_diff.toFixed(2));
					$('#dbPoint_'+i).html(((dp_diff / dp_yest) * 100).toFixed(2) + "％");
				}
			}
			if(stockOpen==1)
			$("#open").html('目前开市');
			else
			$("#open").html('目前休市');
		}
	});
	userinf_s=window.setTimeout('refresh_index()', 5000);
}

function refresh_cancash()
{
	clearTimeout(user_cs);
	$.ajax({
		url:'ajax.php?type=cancash&rand='+Math.random(),
		type:'GET',
		timeout:5000,
		success:function(data){
			if(data.indexOf("lower")!=-1) //提示
			{
				clearTimeout(user_cs);
				msg = data.replace('lower','');
				parent.ymPrompt.confirmInfo({title:'充值提示',message:msg,handler:function(fn){
					if(fn=='ok')
					{
						user_cs=window.setTimeout('refresh_cancash()', 10*60*1000); //10分钟提示一次
						document.all("mainframe").src="payment.php";
					}
					else
					{
						user_cs=window.setTimeout('refresh_cancash()', 60000); //一分钟提示一次
					}
				}});
			}
		}
	});
	user_cs=window.setTimeout('refresh_cancash()', 60000); //一分钟提示一次
}
//买入交易即时价格与浮盈
function showgain()
{
	var get_gain;
	var lcmoney=0; //留仓总金额
	var lcgain=0; //留仓总盈亏
	var total_save_money = 0; //总留仓费
	if(GetCookie('deal_list'))
	{
		clearTimeout(get_gain);
		$.ajax({
			url: 'ajax.php?type=getdeal&rand='+Math.random(),
			type: 'GET',
			dataType: 'html',
			timeout: 5000,
			success: function(response){
				var lcmoney=0; //留仓总金额
				var lcgain=0; //留仓总盈亏
				var total_save_money = 0; //总留仓费
				var bulls = response.split('|');
				for(var i=1; i<bulls.length; i++){
					var bus = bulls[i];
					var bus_code = bus.split(',')[0];    //代码
					var sell_price = bus.split(',')[1];    //当前价格
					var inf=($('#inf_'+i).attr('value')).split(',');
					var all_cost_money=parseFloat(bull_cost_money)+parseFloat(sell_cost_money)+parseFloat(save_money)+parseFloat(dc_money);
					if(bus_type==1)
					{
						//浮盈(卖出金额-买入金额-手续费-留仓费)
						var gain=(parseFloat(sell_money)-parseFloat(bull_money)-parseFloat(bull_cost_money)-parseFloat(save_money)-parseFloat(dc_money)).toFixed(2); //浮盈
						//差价
						var difference = parseFloat(sell_price)-parseFloat(bus_price);
					}else{
						//浮盈(买入金额-卖出金额-买入手续费)
						var gain=(parseFloat(bull_money)-parseFloat(sell_money)-parseFloat(bull_cost_money)-parseFloat(save_money)-parseFloat(dc_money)).toFixed(2); //浮盈
						//差价
						var difference = parseFloat(bus_price)-parseFloat(sell_price);
					}
					//总浮盈
					lcgain =(parseFloat(lcgain)+parseFloat(gain)).toFixed(2);
					//留仓库总金额
					lcmoney = (parseFloat(lcmoney)+parseFloat(sell_money)).toFixed(2);
					//涨跌幅度
					var extent = (difference/sell_price)/100; //幅度
					if(parseFloat(sell_price)>0)
					{
						$('#cur_price_'+i).html(sell_price);
						$('#gain_'+i).html(gain);
						if(parseFloat(gain) > 0) {
							$('#cur_price_'+i).css({ color: "#ff0011",'font-weight':"bold"});
							$('#gain_'+i).css({ color: "#ff0011"});
						} else if(parseFloat(gain) < 0) {
							$('#cur_price_'+i).css({ color: "green",'font-weight':"bold"});
							$('#gain_'+i).css({ color: "#0000FF"});
						}else {
							$('#cur_price_'+i).css({ color: "#000000",'font-weight':"bold"});
							$('#gain_'+i).css({ color: "#0000FF",'font-weight':"bold"});
						}
					}else{
						$('#cur_price_'+i).html('停牌');
						$('#gain_'+i).html('--');
					}
					
					total_save_money = parseFloat(save_money) + total_save_money;
				}
				
				var pcgain = $('#pcgain').html();
				var totalgain = (parseFloat(pcgain) + parseFloat(lcgain)).toFixed(2);
				$('#span_lcmoney').html('￥' + (lcmoney*1).toFixed(2));
				
				$('#span_lcgain').html('￥' + (lcgain*1).toFixed(2));

				if(lcgain==0)
				{
					$('#span_lcgain').css('color','#000000');
				}
				else if(lcgain<0)
				{
					$('#span_lcgain').css('color','#008000');
				}
				$('#span_gain').html('￥' + totalgain);
				if(totalgain==0)
				{
					$('#span_gain').css('color','#000000');
				}
				else if(totalgain<0)
				{
					$('#span_gain').css('color','#008000');
				}
				$('#brsy').html('￥'+totalgain);
				$('#brjl').html('￥'+totalgain);
				$('#lcf').html('￥' + parseFloat(total_save_money).toFixed(2));
				$('#brye').html('￥'+totalgain);
								
				get_gain=window.setTimeout('showgain()', 10000);
			}
		});
	}
	else
	{
		
		var pcgain = $('#pcgain').html();
		var totalgain = (parseFloat(pcgain) + parseFloat(lcgain)).toFixed(2);
		$('#span_lcmoney').html('￥' + (lcmoney*1).toFixed(2));
		$('#span_lcgain').html('￥' + (lcgain*1).toFixed(2));
		if(lcgain==0)
		{
			$('#span_lcgain').css('color','#000000');
		}
		else if(lcgain<0)
		{
			$('#span_lcgain').css('color','#008000');
		}
		$('#span_gain').html('￥' + totalgain);
		if(totalgain==0)
		{
			$('#span_gain').css('color','#000000');
		}
		else if(totalgain<0)
		{
			$('#span_gain').css('color','#008000');
		}
		$('#brsy').html('￥'+totalgain);
		$('#brjl').html('￥'+totalgain);
		$('#lcf').html('￥' + parseFloat(total_save_money).toFixed(2));
		$('#brye').html('￥'+totalgain);
	}
}
/*
*获得列表的股票即时指数
*/
function stockRe(){
	if(GetCookie('stock_list'))
	{
		var url = './stockRe.php?rand='+Math.random();
		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'html',
			timeout: 5000,
			success: function(response){
				var stocks = response.split('|*|');
				var dq_code = $('#buy_code').attr('value');
				var dq_type = $('#price_type').attr('value');
				//$('#buys_price').attr('value','市价买入');
				for(var i=0; i<stocks.length-1; i++){
					var content = stocks[i];
					var code = content.split(',')[0]; //代码
					var name = content.split(',')[1]; //名称
					var tday_f = content.split(',')[2]; //昨日收
					var yest_f = content.split(',')[3]; //今日开
					var curr_f = content.split(',')[4]; //买入价
					var sell   = content.split(',')[8]; //卖出价
					var temp_f = curr_f - yest_f; //差价
					var cj_s= content.split(',')[9]/100;
					var cj_l= content.split(',')[10]/10000;
					//$('#Msg').html('A:'+dq_type);
					if(dq_type==1 && dq_code==code)
					$('#buys_price').attr('value',curr_f);
					$('#o'+i).val(content.split(',')[1]);
					$('#a'+i).html(code);
					$('#b'+i).html(name);
					$('#e'+i).html(yest_f);
					if(parseFloat(curr_f)>0){
						//$('#kxt'+i).html('K线');
						$('#kxt'+i).html('<span class=\'kxt\' onClick="javascript:get_kxt(\''+code+'\');" title="查看 '+code+'['+name+'] 的走势">K线</span>');
						$('#kxt'+i).mouseover(function() { $(this).addClass("kxt2") });
						$('#kxt'+i).mouseout(function() { $(this).removeClass("kxt2") });
						$('#buyID_'+i).text(curr_f);
						if(parseFloat(curr_f) > parseFloat(yest_f)) {
							$('#buyID_'+i).css({ color: "#ff0011",'font-weight':"bold",'cursor':"pointer",'height':"100%",'WIDTH':"100%"});
						} else if(parseFloat(curr_f) < parseFloat(yest_f)) {
							$('#buyID_'+i).css({ color: "green",'font-weight':"bold",'cursor':"pointer",'height':"100%",'WIDTH':"100%"});
						}else {
							$('#buyID_'+i).css({ 'cursor':"pointer",'height':"100%",'WIDTH':"100%"});
						}
						$('#row'+i).css({ 'cursor':"pointer"});
						$('#row'+i).attr("buyinf",code+','+name+','+curr_f+','+sell);
						var val_curr_f=$('#cc'+i).attr("wwww");
						$('#cc'+i).attr("wwww",curr_f);
						if(curr_f!=val_curr_f)
						{
							$('#buyID_'+i).fadeOut(100);$('#buyID_'+i).fadeIn(500);
						}
						if(parseFloat(sell) > parseFloat(yest_f)) {
							$('#cc'+i).css({ color: "#ff0011",'font-weight':"bold",'height':"100%",'WIDTH':"100%"});
						} else if(parseFloat(sell) < parseFloat(yest_f)) {
							$('#cc'+i).css({ color: "green",'font-weight':"bold",'height':"100%",'WIDTH':"100%"});
						}
						$('#cc'+i).html(sell);
						$('#d'+i).html(tday_f);
						if(parseFloat(temp_f) > 0) {
							$('#f'+i).html("\<font color='#FB0B0B'\>" + temp_f.toFixed(2) + "\<\/font\>");
							$('#g'+i).html("\<font color='#FB0B0B'\>" + ((temp_f / yest_f) * 100).toFixed(2) + "%\<\/font\>");
						} else if(parseFloat(temp_f) < 0) {
							$('#f'+i).html("\<font color='green'\>" + temp_f.toFixed(2) + "\<\/font\>");
							$('#g'+i).html("\<font color='green'\>" + ((temp_f / yest_f) * 100).toFixed(2) + "%\<\/font\>");
						} else {
							$('#f'+i).html(temp_f.toFixed(2));
							$('#g'+i).html(((temp_f / yest_f) * 100).toFixed(2) + "%");
						}
						$('#h'+i).html(content.split(',')[5]); //最高价格
						$('#i'+i).html(content.split(',')[6]); //最低价格
						$('#j'+i).html(cj_s.toFixed(0)); //最低价格
						$('#k'+i).html(cj_l.toFixed(2)); //最低价格
						if(stockType<4)
						{
							$('#l'+i).addClass("setup");
							focode='st_'+code;
							$('#l'+i).html('<span onClick="javascript:collect(\''+focode+'\',\''+name+'\');" title="添加 '+code+'['+name+'] 到自选股">收藏</span>');
						}else{
							focode='st_'+code;
							$('#l'+i).html('<span onClick="javascript:del(\''+focode+'\',\''+stockType+'\');" title="删除 '+code+'['+name+']">删除</span>');
							$('#l'+i).addClass("del");
						}
					}else{
						$('#buyID_'+i).attr('');
						$('#buyID_'+i).text('停牌');
						$('#cc'+i).html(' ');
						$('#d'+i).html(' ');
						$('#f'+i).html(' ');
						$('#g'+i).html(' ');
						$('#h'+i).html(' ');
						$('#i'+i).html(' ');
						$('#j'+i).html(' ');
						$('#k'+i).html(' ');
					}

				}
			}
		});
		$(".kxt").mouseover(function() { $(this).addClass("kxt2") });
		$(".kxt").mouseout(function() { $(this).removeClass("kxt2") });
	}
	get_stock=window.setTimeout('stockRe()', 4000);
}

function sale_buy(code,name,num,id,limit_time,limit_cost)
{
	var cansell = false;
	var cancontinue = true;
	var dourl = 'sell.php?stockcode=' + code + '&stockname=' + name + '&num=' + num + '&id=' + id;
	//判断股票是否可卖
	$.ajax({
		url:'buy.php?type=buytime&id='+id,
		type:'GET',
		async:false,
		timeout:5000,
		success:function(data){
			if(data.indexOf('休市中')!=-1)
			{
				parent.ymPrompt.alert({title:'平仓股票',message: '休市中,不能买卖股票!!'});
				cancontinue = false;
			}
			else if(data.indexOf('true')!=-1)
			{
				cansell = true;
				dourl += '&cansell=true';
			}
		}
	});
	
	if(!cancontinue) return;
	if(!cansell)
	{
		dourl += "&cansell=false";
		if(confirm('系统提示：该股票买入时间不足' + limit_time + '分钟将加收' + limit_cost*100 + '%的手续费!\n\n确定卖出吗？'))
		{
			ymPrompt.win({message:dourl,width:500,height:350,title:'平仓股票' + name + '[' + code + ']',iframe:true,handler:function(){self.location.href=self.location.href+'?rnd='+Math.random();}})
		}
	}
	else
	{
		ymPrompt.win({message:dourl,width:500,height:350,title:'平仓股票' + name + '[' + code + ']',iframe:true,handler:function(){self.location.href=self.location.href+'?rnd='+Math.random();}})
	}
}

function cancel_trust(code,name,trust_id)
{
	var txt = '确定要撤销 ['+code+'] '+name+' 的委托交易？';
	ymPrompt.confirmInfo({title:'委托撤单',message:txt,handler:function(fn){
		if(fn == "ok")
		{
			$.ajax({
				url:'ajax.php?type=cancel_trust&id='+trust_id,
				type:'GET',
				timeout:5000,
				success:function(data){
					if(typeof(data)=='undefined' || data=='')
					{
						parent.ymPrompt.errorInfo({title:'委托撤单',message:'撤单失败！'});
						self.location.href=self.location.href;
					}
					else
					{
						res = data.split('|');
						if(res[0]=='true')
						{
							parent.ymPrompt.succeedInfo({title:'委托撤单',message:res[1]});
							self.location.href=self.location.href;
						}
						else
						{
							parent.ymPrompt.errorInfo({title:'委托撤单',message:res[1]});
							self.location.href=self.location.href;
						}
					}
				}
			});
		}
	
	}});
}

function GetQuote(t,c)
{
	$.ajax({
		type: 'GET',
		url: "ajax.php?type=quote&stocktype=" + t + "&stockcode=" + c,
		success:function(res)
		{
			if(res.indexOf(',')!=-1)
			{
				quote = res.split(',');
				if(quote.length!=33)
				{
				//	parent.ymPrompt.alert({title:'系统提示',message:quote[0]+'股票可能退市了！'});
				//	return false;
				}
				zt_rate = quote[1].toLowerCase().indexOf('st')!=-1 ? 1.05 : 1.1;
				dt_rate = quote[1].toLowerCase().indexOf('st')!=-1 ? 0.95 : 0.9;
				//当前可用额度
				var cancash = parseFloat($('#spn_cancash').html());
				//最多可以买多少手
				var cannum = Math.floor(cancash / (100*parseFloat(quote[4])));
				//当前价
				$('#price').html(quote[4]);
				$('#bull_num').val(cannum);
				GetTotalHandqty();
				
				//最新价
				$('#buys_price').val(quote[4]);
				$('#cur_price').html('<font color="' + (parseFloat(quote[4])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[4] + '</font>');
				$('#kp_price').html('<font color="' + (parseFloat(quote[2])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[2] + '</font>');
				$('#hight_price').html('<font color="' + (parseFloat(quote[5])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[5] + '</font>');
				$('#lower_price').html('<font color="' + (parseFloat(quote[6])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[6] + '</font>');
				$('#yes_price').html(quote[3]);
				cur_zd = parseFloat(quote[4]-quote[3]).toFixed(2);
				cur_zdf = parseFloat(cur_zd*100 / quote[3]).toFixed(2);
				cur_zd = '<font color="' + (cur_zd>0 ? '#ff0000' : '#287938') + '">' + cur_zd + '</font>';
				cur_zdf = '<font color="' + (cur_zd>0 ? '#ff0000' : '#287938') + '">' + cur_zdf + '</font>';
				$('#zd').html(cur_zd);
				$('#zdf').html(cur_zdf);
				$('#zcj_num').html(Math.floor(quote[9]/100) + '手');
				$('#zcj_price').html(parseFloat(quote[10]/10000).toFixed(2));
				$('#zt_price').html('<font color="#ff0000">' + parseFloat(quote[3]*zt_rate).toFixed(2) + '</font>');
				$('#dt_price').html('<font color="#287938">' + parseFloat(quote[3]*dt_rate).toFixed(2) + '</font>');
				$('#sell_5_price').html('<font color="' + (parseFloat(quote[28])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[28] + '</font>');
				$('#sell_5_num').html(Math.floor(quote[29]/100));
				$('#sell_4_price').html('<font color="' + (parseFloat(quote[26])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[26] + '</font>');
				$('#sell_4_num').html(Math.floor(quote[27]/100));
				$('#sell_3_price').html('<font color="' + (parseFloat(quote[24])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[24] + '</font>');
				$('#sell_3_num').html(Math.floor(quote[25]/100));
				$('#sell_2_price').html('<font color="' + (parseFloat(quote[22])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[22] + '</font>');
				$('#sell_2_num').html(Math.floor(quote[23]/100));
				$('#sell_1_price').html('<font color="' + (parseFloat(quote[20])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[20] + '</font>');
				$('#sell_1_num').html(Math.floor(quote[21]/100));
				
				$('#buy_1_num').html(Math.floor(quote[11]/100));
				$('#buy_1_price').html('<font color="' + (parseFloat(quote[12])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[12] + '</font>');
				$('#buy_2_num').html(Math.floor(quote[13]/100));
				$('#buy_2_price').html('<font color="' + (parseFloat(quote[14])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[14] + '</font>');
				$('#buy_3_num').html(Math.floor(quote[15]/100));
				$('#buy_3_price').html('<font color="' + (parseFloat(quote[16])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[16] + '</font>');
				$('#buy_4_num').html(Math.floor(quote[17]/100));
				$('#buy_4_price').html('<font color="' + (parseFloat(quote[18])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[18] + '</font>');
				$('#buy_5_num').html(Math.floor(quote[19]/100));
				$('#buy_5_price').html('<font color="' + (parseFloat(quote[20])>parseFloat(quote[3]) ? '#ff0000' : '#287938') + '">' + quote[20] + '</font>');
			}
		}
	});
}

function ShowKline(stockcode) {
	var are_str;
	if(stockcode.substring(0, 1) == "6"){ 
		area_str = "sh"; 
	}else if(stockcode.substring(0, 1) == "0" || stockcode.substring(0, 1) == "3"){
		area_str = "sz";
	}
	ymPrompt.win({title:'股票 ' + stockcode + ' 的K线图', message: "kline.php?stockcode=" + stockcode + "&stocktype=" + area_str, width: 580, height: 420, maxBtn: true, iframe: true, winPos: [(screen.width-580)/2, 80], handler: null });
}

function ShowStockInfo(stockcode) {
	window.showModalDialog("stockinfo.php?stockcode=" + stockcode + "&ts=" + (new Date()).getTime(), "_blank", "dialogHeight: 450px; dialogWidth: 610px; dialogTop: 150px; dialogLeft: 200px; edge: Raised; center: Yes; help: no; resizable: Yes; status: no;");
}

function AddStock1(code)
{
	$.ajax({
	   type:'GET',
	   url:'ajax.php?type=CheckStocksIsHas&stockcode=' + code,
	   cache:false,
	   success:function(rescheck){
			if (rescheck.indexOf('true')==-1) {
				parent.ymPrompt.errorInfo({title:'自选股',message:rescheck});
				return false;
			}
			else {
				//添加自选股
				$.ajax({
					   type:'GET',
					   url:'ajax.php?type=addstock&stockcode=' + code,
					   cache: false,
					   success:function(res){
							parent.ymPrompt.alert({title:'自选股',message:res});
					   }
				});
			}
	   }
	});
}

function AddStock() {
	$('#btnAdd').attr('disabled','true');
	if (Trim($("#stockcode").val()) == "" || Trim($("#stockcode").val()) == "代码/名称/拼音" ) {
		parent.ymPrompt.errorInfo({title:'系统提示', message: "请输入股票代码", handler: function() {
			$("#stockcode").focus();
			$('#btnAdd').attr('disabled','');
		}
		});
		return false;
	}
	var stocksno = Trim($("#stockcode").val());
	$.ajax({
	   type:'GET',
	   url:'ajax.php?type=CheckStocksIsHas&stockcode=' + stocksno,
	   cache:false,
	   success:function(rescheck){
			if (rescheck.indexOf('true')==-1) {
				parent.ymPrompt.succeedInfo({title:'系统提示',message:rescheck});
				$('#stockcode').val('');
				$('#btnAdd').attr('disabled','');
				return false;
			}
			else {
				//添加自选股
				//var res = ajaxUrl("ajax.php?type=addstock&stockcode=" + stocksno);
				$.ajax({
					   type:'GET',
					   url:'ajax.php?type=addstock&stockcode=' + stocksno,
					   cache: false,
					   success:function(res){
							if (res == "收藏自选股成功！") {
								GetUserFav();
								$('#stockcode').val('');
								$('#btnAdd').attr('disabled','');
							}
							else {
								parent.ymPrompt.errorInfo({title:'系统提示',message:res});
								$('#btnAdd').attr('disabled','');
							}
					   }
				});
			}
	   }
	   });
}

function DelStock(stockno) {
	parent.ymPrompt.confirmInfo({ message: "确实要删除该支自选股票记录吗？", handler: function(fn) {
		if (fn == "ok") {
			var res = selforder.DeleteStocks(stockno);
			if (res != null && res != undefined) {
				if (res.value == "") {
					parent.ymPrompt.errorInfo("删除成功");
					GetUserFav();
				}
				else {
					parent.ymPrompt.errorInfo(res.value);
				}
			}
		}
	}
	});
}

function GetUserFav() {
	$.ajax({
		type: 'GET',
		url:  'ajax.php?type=getfav',
		cache: false,
		success:function(res)
		{
			$("#divstockslist").html(res);
		}
	});
}

function ChangeStocksbktypename(stockstypename) {
	$.ajax({
		type: 'GET',
		url:  'ajax.php?type=getbk&class=' + stockstypename,
		success:function(res)
		{
			$("#tdstocksbktypename").html(res);
		}
	});
}

function ChangeStocks(stocksbktypename) {
	$.ajax({
		type: 'GET',
		url:  "ajax.php?type=getbkstock&bk_name=" + stocksbktypename,
		success:function(res)
		{
			$("#tdstocksname").html(res);
		}
	});
	
}

function SetStocksno(stocksno) {
	if (stocksno != "") {
		$("#stockcode").val(stocksno);
	}
}

//删除用户商品
function productDelete() {
	var object;
	var objectID;
	var productIDList = "";
	var url = "";
	var returnVal = "";
	var tempList = "";


	object = document.getElementsByName("check_name");
	for (i = 0; i < object.length; i++) {
		if (object[i].checked == true) {
			productIDList += "'" + object[i].value + "',"
		}
	}

	if (productIDList == "") {
		parent.ymPrompt.errorInfo({title:'系统提示',message:"请先选择要删除的股票！"});
		return;
	}

	productIDList = productIDList.substring(0, productIDList.length - 1);
	parent.ymPrompt.confirmInfo({ message: "确实要删除选定自选股票记录吗？", handler: function(fn) {
		if (fn == "ok") {
			var res = ajaxUrl("ajax.php?type=delfav&stockcode=" + productIDList);
			if (res != null && res != undefined) {
				if (res.indexOf('true')!=-1) {
					parent.ymPrompt.succeedInfo({title:'系统提示', message: "删除成功", handler: function() {
						GetUserFav();
					}
					});
				}
				else {
					parent.ymPrompt.errorInfo({title:'系统提示',message: res.value});
				}
			}
		}
	}
	});
}

function KillErros()
{
		return true;
}

window.onerror = KillErros;

function nocontextmenu() 
{
event.cancelBubble = true
event.returnValue = false;

return false;
}

function norightclick(e) 
{
if (window.Event) 
{
if (e.which == 2 || e.which == 3)
return false;
}
else
if (event.button == 2 || event.button == 3)
{
event.cancelBubble = true
event.returnValue = false;
return false;
}
}

//document.oncontextmenu = nocontextmenu; //对ie5.0以上
//document.onmousedown = norightclick; //对其它浏览器