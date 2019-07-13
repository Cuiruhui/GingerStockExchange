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
					$('#o'+i).val(content.split(',')[1]);
					$('#a'+i).html(code);
					$('#b'+i).html(name);
					$('#e'+i).html(yest_f);
					if(parseFloat(curr_f)>0){
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
						$('#cc'+i).html('-');
						$('#d'+i).html('-');
						$('#f'+i).html('-');
						$('#g'+i).html('-');
						$('#h'+i).html('-');
						$('#i'+i).html('-');
						$('#j'+i).html('-');
						$('#k'+i).html('-');
					}

				}
			}

		});
	}
}
//增加每行股票
function AddStockCode(){
	var table = document.getElementById("tableId");
	var index = table.rows.length;
	var str="";
	var row = index - 1;
	str+='<tr class="tr_cls" id="row' + row + '">'
	str+='<td align="center"><span id="' + "a" + row + '">-</span></td>'
	str+='<td align="center"><span id="' + "b" + row + '">-</span></td>'
	str+='<td align="right" id="buyID_'+row+'"><span id="' + "c" + row + '">-</span></td>'
	str+='<td align="right"><span id="' + "cc" + row + '">-</span></td>'
	str+='<td align="right"><span id="' + "d" + row + '">-</span></td>'
	str+='<td align="right"><span id="' + "e" + row + '">-</span></td>'
	str+='<td align="right"><span id="' + "f" + row + '">-</span></td>'
	str+='<td align="right"><span id="' + "g" + row + '">-</span></td>'
	str+='<td align="right"><span id="' + "h" + row + '">-</span></td>'
	str+='<td align="right"><span id="' + "i" + row + '">-</span></td>'
	str+='<td align="right"><span id="' + "j" + row + '">-</span></td>'
	str+='<td align="right"><span id="' + "k" + row + '">-</span></td>'
	str+='<td align="center"><span id="' + "l" + row + '">-</span></td>'
	str+='</tr>';
	$("#tablebody").append(str);
}
//移除股票列表所有行
function removeRows(){
	var table = document.getElementById("tableId");
	var index = table.rows.length;
	for(var i=(index-1); i>0; i--){
		table.deleteRow(i);
	}
}
//获取股票代码列表
function loadStock(data)
{
	var temp_Data=data.split('|');
	var get_type=temp_Data[0].split(',')[0];
	var page_num=parseFloat(temp_Data[0].split(',')[1]);
	var page_d=temp_Data[0].split(',')[2];
	var hy_id=temp_Data[0].split(',')[3];
	var page_s=Number(page_d)-1;
	var page_x=Number(page_d)+1;
	var page_str='';
	$('#page').html('');
	if(page_num>1)
	{
		if(page_d>1)
		page_str+='<a href="javascript:;" onClick="getPage('+(get_type)+','+page_s+','+hy_id+',0);">上一页</a>&nbsp;&nbsp;';
		if(page_d<page_num)
		page_str+='<a href="javascript:;" onClick="getPage('+(get_type)+','+page_x+','+hy_id+',0);">下一页</a>&nbsp;&nbsp;';
		page_str+='页:'+page_d+'/'+page_num+'&nbsp;&nbsp;';
		$('#page').html(page_str);
	}
	var stocks=temp_Data[1].split(",");
	CreatCookie('stock_list',stocks);
	stocksCookieVal=GetCookie('stock_list');
	removeRows();
	for(var i=0; i<10; i++){
		AddStockCode();
	}
	stockRe();
	$(".tr_cls").hover(function(){
		$(this).addClass("hover");
	},function(){
		$(this).removeClass("hover");
	});
	$(".tr_cls").click(function(){
		var buyi=$(this).attr('buyinf');
		if(buyi)
		BuyStock(buyi.split(',')[0],buyi.split(',')[1],buyi.split(',')[2],buyi.split(',')[3]);
	});

}
//获取指定页
function getPage(type,page,hy_id,code_id)
{
	if(type<4)
	urlfile='test.php';
	else
	urlfile='UserFree.php';
	//$("#LoadMSg").show();
	$.ajax({
		url: urlfile+'?t='+type+'&page='+page+'&hy_id='+hy_id+'&code_id='+code_id+'&rand='+Math.random(),
		type: 'GET',
		dataType: 'html',
		timeout: 4000,
		success: function(response){
			loadStock(response);
			//$("#LoadMSg").hide(700);
		}
	});
}
//收藏到自选股
function collect(id,name)
{
	var txt = '收藏 '+name+' 到: <input type="radio" name="free" id="free" value="1"  checked="checked" />自选股一<input type="radio" name="free" id="free" value="2" />自选股二';
	$.prompt(txt,{
		callback: function(v,m,f){
			if(v){
				divID=parseFloat(f.free)+3;
				$.ajax({
					url:'UserFree.php?free=add&id='+id+'&type='+f.free,
					$type:'GET',
					timeout:3000,
					success:function(data){
						if(data==1){
							$('#tabs .selected').removeClass('selected');
							$("#LoadMSg").show();
							getPage(divID,1,0,0);
							stockType=divID;
							$('#tabs div:nth-child('+divID+')').addClass('selected'); //设置tabs为全部
							//$.prompt('收藏成功');
						}else if(data==2){
							$.prompt('收藏失败！');
						}else {
							$.prompt('你的自选股已经收藏过了！');
						}
					}
				}
				)
				//$.prompt(v +' ' + name+'|'+id);
			}else{}
		},
		buttons: { '确定':true, '取消': false  }
	});
}
//删除自选股
function del(id,type){
	types=parseFloat(type)-3;
	$.ajax({
		url:'UserFree.php?free=del&id='+id+'&type='+types,
		type:'GET',
		timeout:3000,
		success:function(data){
			getPage(type,1,0,0);
		}
	});
}
function showRequest(formData, jqForm, options) {
	var queryString = $.param(formData);
	return true;
}
function showResponse(responseText, statusText)  {
}
