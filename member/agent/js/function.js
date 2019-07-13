function ShowUserInfo(user)
{
	parent.ymPrompt.win({message:'member.php?type=userinfo&username='+user,width:500,height:280,title:'会员'+user+'的详细信息',iframe:true})
}

function ShowKline(stockcode) {
	var are_str;
	if(stockcode.substring(0, 1) == "6"){ 
		area_str = "sh"; 
	}else if(stockcode.substring(0, 1) == "0" || stockcode.substring(0, 1) == "3"){
		area_str = "sz";
	}
	parent.ymPrompt.win({title:'股票 ' + stockcode + ' 的K线图', message: "kline.php?stockcode=" + stockcode + "&stocktype=" + area_str, width: 580, height: 420, maxBtn: true, iframe: true, winPos: [(screen.width-580)/2, 80], handler: null });
}
//公告轮显示
function news(obj){
	clearTimeout(news_s);
	$(obj).find("ul:first").animate({
		marginTop:"-25px"
	},500,function(){
		$(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
	});
	news_s=window.setTimeout('news("#news")', 3000);
}
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
		timeout:5000,
		async:false,
		success:function(data){
			res = data;
		}
	});
	return res;
}

function Trim(str) {
	return str.replace(/(^\s*)|(\s*$)/g, "");
}

function CalcDealGain(code)
{
	var s;
	var gain_total = 0;
	if(code=='') return;
	clearTimeout(s);
	$.ajax({
		type:'GET',
		url:'ajax.php?type=deal_quote&code=' + code,
		timeout:10000,
		success:function(res){
			price=res.split(',');
			for(i=0;i<price.length;i++)
			{
				if(price[i]==0)
				{
					$('#cur_price_'+(i+1)).html('停牌');
					$('#gain_'+(i+1)).html('停牌');
					$('#cur_price_'+(i+1)).css('color','gray');
					$('#gain_'+(i+1)).css('color','gray');
					continue;
				}
				$('#cur_price_'+(i+1)).html(price[i]);
				buy_type = $('#buy_type_'+(i+1)).attr('value');
				bull_price = $('#bull_price_'+(i+1)).attr('value');
				bull_num = $('#bull_num_'+(i+1)).attr('value');
				bull_cost = $('#bull_cost_money_'+(i+1)).attr('value');
				dc_money = $('#dc_money_'+(i+1)).attr('value');
				save_money = $('#save_money_'+(i+1)).attr('value');
				if(buy_type=='1') //买升
				{
					gain = (price[i] - bull_price).toFixed(2)*parseInt(bull_num)*100 - bull_cost - dc_money - save_money;
				}
				else
				{
					gain = (bull_price - price[i]).toFixed(2)*parseInt(bull_num)*100 - bull_cost - dc_money - save_money;
				}
				gain = gain.toFixed(2);
				gain_total = gain_total + parseFloat(gain);
				$('#gain_'+(i+1)).html(gain);
				if(gain<0)
				{
					$('#gain_'+(i+1)).css('color','green');
				}
				else if(gain>0)
				{
					$('#gain_'+(i+1)).css('color','red');
				}
			}
			$('#gain_total').html(gain_total.toFixed(2));
			s=window.setTimeout(function(){CalcDealGain(code);}, 5000);
		}
	});
}

function sale_buy(deal_id)
{
	if(confirm('确定要平仓该支股票？'))
	{
		parent.ymPrompt.win({title:'平仓股票',message:'sell_stock.php?id=' + deal_id, width:400, height:320, iframe:true,handler:function(){self.location.href=self.location.href+'?rnd='+Math.random();}});
	}
	else
	{
		return false;
	}
}
function cancel_buy(code,name,buy_id)
{
	var txt = '确定要撤销 ['+code+'] '+name+' 的委托交易??';
	$.prompt(txt,{
		callback: function(v,m,f){
			if(v){
				$.ajax({
					url:'BuyList.php?type=cancel_buy&id='+buy_id,
					type:'GET',
					timeout:5000,
					success:function(data){
						$("#Msg").html(data);
					}
				}
				)
				//$.prompt(v +' ' + name+'|'+id);
			}else{}
		},
		buttons: { '确定':true, '取消': false  }
	});
}

function KillErros()
{
		return true;
}

window.onerror = KillErros;