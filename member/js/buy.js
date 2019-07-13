//??�示购买信�??
function BuyStock(code,name,price,sell)
{
	$('#buy_name').text(name);
	$('#buy_code').attr('value',code);
	$('#buy_num').attr('value',$('#info_money3').attr('value'));
	$('#bull_price').attr('value',price);
	$('#sell_price').attr('value',sell);
	$('#buy_type').attr('value',1);
	$('#price_type').attr('value',1);
	$('#buys_price').attr('value','市价买入');
	$("#buys_price").attr("disabled",true);
	$("#bull_but").attr("disabled",false);
	$('#price_type').change( function() {
		// 这里可以写些验证代码
		var princ_type=$(this).val();
		if(princ_type==1)
		{
			$('#buys_price').attr('value','市价买入');
			$("#buys_price").attr("disabled",true);
		}else{
			$('#buys_price').attr('value',price);
			$("#buys_price").attr("disabled",false);
		}
		//alert($(this).val());
	});
	$('#buy_title').css({'color':'#FF0000'});
	$('#buy_type').change( function() {
		if($(this).val()==2)
		{
			$('#buy_title').css({'color':'#009900'});
		}else {
			$('#buy_title').css({'color':'#FF0000'});
		}
	});
	if(sell==0)
	{
		$('#buys_price').attr('value','涨停');
		$("#bull_but").attr("disabled",true);
	}
	if(price==0)
	{
		$('#buys_price').attr('value','跌停');
		$("#bull_but").attr("disabled",true);
	}
	if(stockOpen==0)
	{
		$('#buys_price').attr('value','停止交易');
		$("#bull_but").attr("disabled",true);
	}
	if(st)clearTimeout(st);
	setTimeout(function() {
		alert($("#bull_but").attr("disabled"));
		$("#bull_but").attr("disabled",true);
	}, 3000);

	//var st=setTimeout(''+$("#bull_but").attr("disabled",true)+'',13000);
	//$('#buys_code').val(code);bull_but
	//var vll=$("button").val();
	//alert('ddd'+vll);
}
function buylist(bid)
{
	$.ajax({
		url: 'BuyList.php?type='+bid+'&rand='+Math.random(),
		type: 'GET',
		dataType: 'html',
		timeout: 5000,
		success: function(response){
			if(bid=='buy4')
			{
				$("#html").html(response);
			}else{
				$("#buylist").html(response);
			}
			get_buy1();
		}
	});
}
function minute()
{
	$.ajax({
		url: 'main_deal.php?rand='+Math.random(),
		type: 'GET',
		dataType: 'html',
		timeout: 5000,
		success: function(response){
			$("#minute").html(response);
		}
	});
}
//买入交易即时价格与浮盈
function get_buy1()
{
	if(GetCookie('buy1') || GetCookie('buy2') || GetCookie('buy4'))
	{
		$.ajax({
			url: 'get_buy1.php?rand='+Math.random(),
			type: 'GET',
			dataType: 'html',
			timeout: 5000,
			success: function(response){
				var bulls = response.split('|');
				for(var i=1; i<bulls.length; i++){
					var bus = bulls[i];
					var bus_code = bus.split(',')[0];    //代码
					var sell_price = bus.split(',')[1];    //当前价格
					var bus_price =$('#buy1A_'+i).text(); //买入价格
					var bus_type=$('#buy1B_'+i).attr('value'); //多/空
					var bus_num=$('#buy1C_'+i).text(); //买入数量
					var bus_cost=$('#buy1cost_'+i).text(); //手续费
					var save_day=$('#save_'+i).text(); //留仓天数
					var save_cost=$('#save_'+i).attr('value'); //留仓费率
					var sell_cost=$('#buy1E_'+i).attr('value'); //卖出手续费率
					if(bus_type==1)
					{
						var difference = parseFloat(ses_price)-parseFloat(bus_price);
					}else{
						var difference = parseFloat(bus_price)-parseFloat(sell_price);
					}
					var bull_money=(bus_price*bus_num*100).toFixed(2);
					var sell_money=(sell_price*bus_num*100).toFixed(2);
					var sell_cost_money=(sell_money*sell_cost).toFixed(2);
					var save_cost_money=sell_cost_money*save_cost*save_day;
					var gain=(difference.toFixed(2)*bus_num*100-((bus_cost)+(sell_cost_money)+((sell_money)*(save_cost)*(save_day)))).toFixed(2); //浮盈
					var extent = (difference/sell_price)/100; //幅度
					if(parseFloat(sell_price)>0)
					{
						//alert(ses_price);
						$('#buy1E_'+i).html(sell_price);
						$('#buy1F_'+i).html('123');
						$('#buy1G_'+i).html(((difference/bus_price ) *100).toFixed(2) + "%");
						if(parseFloat(gain) > 0) {
							$('#buy1E_'+i).css({ color: "#ff0011",'font-weight':"bold"});
							$('#buy1F_'+i).css({ color: "#ff0011",'font-weight':"bold"});
							$('#buy1G_'+i).css({ color: "#ff0011",'font-weight':"bold"});
						} else if(parseFloat(gain) < 0) {
							$('#buy1E_'+i).css({ color: "green",'font-weight':"bold"});
							$('#buy1F_'+i).css({ color: "green",'font-weight':"bold"});
							$('#buy1G_'+i).css({ color: "green",'font-weight':"bold"});
						}else {
							$('#buy1E_'+i).css({ color: "#000000",'font-weight':"bold"});
							$('#buy1F_'+i).css({ color: "#000000",'font-weight':"bold"});
							$('#buy1G_'+i).css({ color: "#000000",'font-weight':"bold"});
						}
					}else{
						$('#buy1E_'+i).html('停牌');
						$('#buy1F_'+i).html('--');
						$('#buy1G_'+i).html('--');
					}
				}
				//$("#test").html(response);
			}
		});
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
function sale_buy(code,name,buy_num,buy_id)
{
	var txt = '确定要平仓 '+buy_num+' 手 ['+code+'] '+name+' 股票交易??';
	$.prompt(txt,{
		callback: function(v,m,f){
			if(v){
				$.ajax({
					url:'buy.php?type=sale&id='+buy_id,
					type:'GET',
					timeout:5000,
					success:function(data){
						if(GetCookie('buy4'))
						$("#html").html(data);
						else
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
function user_info()
{
	$.ajax({
		url:'user_info.php?rand='+Math.random(),
		type:'GET',
		timeout:5000,
		success:function(data){
			var mdata = data.split('|');
			var user_M = mdata[0];
			var dps = mdata[1];
			var re = mdata[3];
			stockOpen = mdata[4];
			var u_credit = user_M.split(',')[0];
			var u_money = user_M.split(',')[1];
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
					$('#dpDiff_'+i).html("\<font color='#FB0B0B'\>" + dp_diff.toFixed(2) + "\<\/font\>");
					$('#dbPoint_'+i).html("\<font color='#FB0B0B'\>" + ((dp_diff / dp_yest) * 100).toFixed(2) + "％\<\/font\>");
				} else if(parseFloat(dp_diff) < 0) {
					$('#dpDiff_'+i).html("\<font color='green'\>" + dp_diff.toFixed(2) + "\<\/font\>");
					$('#dbPoint_'+i).html("\<font color='green'\>" + ((dp_diff / dp_yest) * 100).toFixed(2) + "％\<\/font\>");
				} else {
					$('#dpDiff_'+i).html(dp_diff.toFixed(2));
					$('#dbPoint_'+i).html(((dp_diff / dp_yest) * 100).toFixed(2) + "％");
				}
			}
			$("#info_credit").html(u_credit);
			$("#info_money").html(u_money);
			if(re=='B')
			{
				minute();
				$('#buytabs .selected').removeClass('selected');
				buylist('buy1');
				$('#buytabs div:nth-child(2)').addClass('selected');
			}
			if(stockOpen==1)
			$("#open").html('目前开市');
			else
			$("#open").html('目前休市');
		}
	});
}
function getWindows(Win,Url)
{
	$.openPopupLayer({
		name: Win,
		//width: 606,
		url: Url
	});
}