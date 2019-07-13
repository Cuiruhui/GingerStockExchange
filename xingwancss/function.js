function showtime() {
	var today = new Date((new Date()).getTime());
	
	/*Daylight Saving Time Start*/
	var dst = 0;
	var lsm = new Date;
	var lso = new Date;
	lsm.setMonth(2); // March
	lsm.setDate(31);
	var day = lsm.getDay();// day of week of 31st
	lsm.setDate(31-day); // last Sunday
	lso.setMonth(9); // October
	lso.setDate(31);
	day = lso.getDay();
	lso.setDate(31-day);
	if (today < lsm || today >= lso) dst = 1; 
	/*Daylight Saving Time End*/

	var hour = today.getHours();
	var minute = today.getMinutes();
	var second = today.getSeconds();
	if (hour <= 9)
		hour = "0" + hour;
	if (minute <= 9)
		minute = "0" + minute;
	if (second <= 9)
		second = "0" + second;

	var utc = today.getTime() + (today.getTimezoneOffset() * 60000);
	var ldDate = new Date(utc + (3600000 * (1-dst)));
	var nyDate = new Date(utc + (3600000 * (-4-dst)));
	var tyDate = new Date(utc + (3600000 * (9)));
	var sxDate = new Date(utc + (3600000 * (2)));

	var ldhour = ldDate.getHours() < 9 ? ("0" + ldDate.getHours()) : ldDate.getHours();
	var nyhour = nyDate.getHours() < 9 ? ("0" + nyDate.getHours()) : nyDate.getHours();
	var tyhour = tyDate.getHours() < 9 ? ("0" + tyDate.getHours()) : tyDate.getHours();
	var sxhour = sxDate.getHours() < 9 ? ("0" + sxDate.getHours()) : sxDate.getHours();

	var strldtime = ldhour + ":" + minute + ":" + second;
	var strnytime = nyhour + ":" + minute + ":" + second;
	var strtytime = tyhour + ":" + minute + ":" + second;
	var strhktime = hour + ":" + minute + ":" + second;
	var strsxtime = sxhour + ":" + minute + ":" + second;
	
	document.getElementById('ldtime').innerHTML = strldtime;
	document.getElementById('nytime').innerHTML = strnytime;
	document.getElementById('tytime').innerHTML = strtytime;
	document.getElementById('hktime').innerHTML = strhktime;

	setTimeout("showtime();", 500);
}


$(function() {

	$("a#chat_no").click(function(){
		$(".chat_float .chat_box").hide();
		$("span.o_title").addClass("tithov");
		setTimeout(function(){$("span.o_title").removeClass("tithov")},2000);
	})
	$("#rightclose").click(function(){$(".rightfloat").hide()})
	$(".rightfloat .box").hover(function(){
		$(".rightfloat").css({"width":"245px"});
		$(this).find("span").addClass("tithov");
		if($(this).find(".m_qrcode").length>0){$(this).css({width:"177px"});$(this).find(".m_qrcode").slideToggle("fast");}
		else{$(this).css({width:(($(this).find("li").length+1)*60)+($(this).find("li").length-1)});
		$(this).find("ul").animate({right:"60px"},100);}
	},function(){
		if($(this).find(".m_qrcode").length>0){$(this).find(".m_qrcode").slideToggle("fast");}
		else{$(this).find("ul").animate({right:"-300px"},100);}
		$(this).find("span").removeClass("tithov");
		$(this).css({width:"60px"});
		$(".rightfloat").css({"width":"60px"});
	})
	/* 更换 */
	var str= '';
	$(".rightfloat .box li").hover(
		function(){str=$(this).attr("class");$(this).attr("id",str+"hov");},
		function(){$(this).attr("id","");}
	)
	/* 微信 */
	$(".rightfloat .box li.f_wx").hover(
		function(){$(".wxqrcode").slideToggle("fast");},
		function(){$(".wxqrcode").slideToggle("fast");}
	)
	/* 顶部 */
	$(".t_title").click(function(){$('html,body').animate({scrollTop: '0px'}, 800);})
	
	$(".flow_icon").click(function(){
	
		$(this).removeClass("flow_icon_after");
		$(this).parent().parent().nextAll("li").find(".flow_icon").removeClass("flow_icon_after");
	})
	//悬浮对话框关闭
		$("a#chat_close").click(function(){
			$(".chat_float .chat_box").hide();
			$("span.o_title").addClass("tithov");
			setTimeout(function(){$("span.o_title").removeClass("tithov")},2000);
		})
		$("#rightclose").click(function(){$(".rightfloat").hide()})
		$(".rightfloat .box").hover(function(){
			$(".rightfloat").css({"width":"245px"});
			$(this).find("span").addClass("tithov");
			if($(this).find(".m_qrcode").length>0){$(this).css({width:"177px"});$(this).find(".m_qrcode").slideToggle("fast");}
			else{$(this).css({width:(($(this).find("li").length+1)*60)+($(this).find("li").length-1)});
			$(this).find("ul").animate({right:"60px"},100);}
		},function(){
			if($(this).find(".m_qrcode").length>0){$(this).find(".m_qrcode").slideToggle("fast");}
			else{$(this).find("ul").animate({right:"-300px"},100);}
			$(this).find("span").removeClass("tithov");
			$(this).css({width:"60px"});
			$(".rightfloat").css({"width":"60px"});
		})
	
});

$(window).scroll(function(){
	if($(window).scrollTop() >= 88)
	{
		menuBarfix();
	}
	else
	{
		clearMenuBarfix();
	}
})
var menuBarfix = function(){	
	$("#wplist").css({"position":"fixed","width":"100%","top":"0","left":"0","z-index":"9999999"})
}
var clearMenuBarfix = function(){	
	$("#wplist").css({"position":"relative","width":"100%","top":"auto","left":"auto","z-index":"10000"})
}


/*分页菜单*/
function doClick1(o){
	 o.className="title1a";
	 var j;
	 var id;
	 var a;
	 for(var i=1;i<=2;i++){
	   id ="aa"+i;
	   j = document.getElementById(id);
	   a = document.getElementById("a"+i);
	   if(id != o.id){
	   	 j.className="title1b";
	   	 a.style.display = "none";
	   }else{
			a.style.display = "block";
	   }
	 }
}

function doClick2(o){
	 o.className="title2a";
	 var j;
	 var id;
	 var b;
	 for(var i=1;i<=4;i++){
	   id ="bb"+i;
	   j = document.getElementById(id);
	   b = document.getElementById("b"+i);
	   if(id != o.id){
	   	 j.className="title2b";
	   	 b.style.display = "none";
	   }else{
			b.style.display = "block";
	   }
	 }
}

function doClick3(o){
	 o.className="title3a";
	 var j;
	 var id;
	 var c;
	 for(var i=1;i<=3;i++){
	   id ="cc"+i;
	   j = document.getElementById(id);
	   c = document.getElementById("c"+i);
	   if(id != o.id){
	   	 j.className="title3b";
	   	 c.style.display = "none";
	   }else{
			c.style.display = "block";
	   }
	 }
}

function doClick4(o){
	 o.className="title4a";
	 var j;
	 var id;
	 var d;
	 for(var i=1;i<=4;i++){
	   id ="dd"+i;
	   j = document.getElementById(id);
	   d = document.getElementById("d"+i);
	   if(id != o.id){
	   	 j.className="title4b";
	   	 d.style.display = "none";
	   }else{
			d.style.display = "block";
	   }
	 }
}

function doClick5(o){
	 o.className="title5a";
	 var j;
	 var id;
	 var e;
	 for(var i=1;i<=2;i++){
	   id ="ee"+i;
	   j = document.getElementById(id);
	   e = document.getElementById("e"+i);
	   if(id != o.id){
	   	 j.className="title5b";
	   	 e.style.display = "none";
	   }else{
			e.style.display = "block";
	   }
	 }
}

function doClick8(o){
	 o.className="title8a";
	 var j;
	 var id;
	 var h;
	 for(var i=1;i<=3;i++){
	   id ="hh"+i;
	   j = document.getElementById(id);
	   h = document.getElementById("h"+i);
	   if(id != o.id){
	   	 j.className="title8b";
	   	 h.style.display = "none";
	   }else{
			h.style.display = "block";
	   }
	 }
}