function show_time()
{
	clearTimeout(show_s);
	$.ajax({
		url:'showTime.php?rand='+Math.random(),
		type:'GET',
		timeout:1000,
		success:function(data){
			$('#liveTime').html(data);
		}
	});
	show_s=window.setTimeout('show_time()', 1000);
}

function ajaxUrl(url)
{
	var res;
	$.ajax({
		url: url,
		type:'GET',
		timeout:10000,
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
function countDealGain(code)
{
	var s;
	if(code=='') return;
	clearTimeout(s);
	//quote = ajaxUrl('ajax.php?type=deal_quote&code=' + code);
	$.ajax({
	   type:'GET',
	   url:'ajax.php?type=deal_quote&code=' + code,
	   success:function(res){
		   	if(res==undefined || res=='')
			{
				s=window.setTimeout(function(){countDealGain(code);}, 5000);
				return false;
			}
			var gain_total = 0;
			price=res.split(',');
			for(i=0;i<price.length;i++)
			{
				var cur_id = (page-1)*pagesize + i + 1;
				if(price[i]==0)
				{
					$('#price_'+cur_id).html('停牌');
					$('#gain_'+cur_id).html('停牌');
					continue;
				}
				$('#price_'+cur_id).html(price[i]);
				buy_type = $('#buy_type_'+cur_id).attr('value');
				bull_price = $('#bull_price_'+cur_id).attr('value');
				bull_num = $('#bull_num_'+cur_id).attr('value');
				bull_cost = $('#cost_'+cur_id).attr('value');
				dc_money = $('#dc_money_'+cur_id).attr('value');
				if(buy_type=='1') //买升
				{
					gain = parseFloat((price[i] - bull_price).toFixed(2)*parseInt(bull_num)*100 - bull_cost - dc_money);
				}
				else
				{
					gain = parseFloat((bull_price - price[i]).toFixed(2)*parseInt(bull_num)*100 - bull_cost - dc_money);
				}
				gain = gain.toFixed(2);
				$('#gain_'+cur_id).html(gain);
				if(gain<0)
				{
					$('#gain_'+cur_id).css('color','green');
				}
				else if(gain>0)
				{
					$('#gain_'+cur_id).css('color','red');
				}
				gain_total = parseFloat(gain_total) + parseFloat(gain);
			}
			$('#gain_total').html(gain_total.toFixed(2));
			s=window.setTimeout(function(){countDealGain(code);}, 5000);
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
	ymPrompt.win({title:'股票 ' + stockcode + ' 的K线图', message: "./kline.php?stockcode=" + stockcode + "&stocktype=" + area_str, width: 580, height: 420, maxBtn: true, iframe: true});
}

function ShowUserInfo(user)
{
	ymPrompt.win({message:'./member_user.php?type=userinfo&username='+user,width:500,height:280,title:'会员'+user+'的详细信息',iframe:true});
}

function Message(id)
{
	ymPrompt.win({message:'./message.php?type=read&id='+id,width:500,height:280,title:'阅读短信',iframe:true,handler:function(){self.location.href=self.location.href;}});
}

function sale_stock(id)
{
	if(confirm('确定要平仓ID号为' + id + '的用户持仓单吗？')){
		ymPrompt.win({message:'./sell_stock.php?id='+id,width:500,height:320,title:'平仓',iframe:true});
	}
}

function cancel_trust(code,name,trust_id)
{
	var txt = '确定要撤销 ['+code+'] '+name+' 的委托交易？';
	parent.ymPrompt.confirmInfo({title:'委托撤单',message:txt,handler:function(fn){
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

function KillErros()
{
		return true;
}

function xiugaideal(id)
{
	
	ymPrompt.win({message:'./sell_stock.php?id='+id+'&act=xiugai',width:500,height:320,title:'修改',iframe:true});

}


//window.onerror = KillErros;