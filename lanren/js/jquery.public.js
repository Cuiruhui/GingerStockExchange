var DateTime = {
	now:function(){
		var   ret= new StringBuilder();
		var d = new Date();
		ret.append(d.getFullYear()+ "-");
		ret.append(( "00"+(d.getMonth()+1)).slice(-2)+ "-");
		ret.append(( "00"+d.getDate()).slice(-2)+ " ");
		ret.append(( "00"+d.getHours()).slice(-2)+ ":");
		ret.append(( "00"+d.getMinutes()).slice(-2)+ ":");
		ret.append(( "00"+d.getSeconds()).slice(-2));
		return ret.toString();
	},
	 dateDiff:function(interval, date1, date2)
	{
		var objInterval = {'D' : 1000 * 60 * 60 * 24, 'H' : 1000 * 60 * 60, 'M' : 1000 * 60, 'S' : 1000, 'T' : 1};
		 interval = interval.toUpperCase();
		 var dt1 = Date.parse(date1.replace(/-/g, '/'));
		 var dt2 = Date.parse(date2.replace(/-/g, '/'));
		 try
		{
		 return Math.round((dt2 - dt1) / eval('(objInterval.' + interval + ')'));
		 }
		 catch (e)
		{
		 return e.message;
		 }
	},
     //格式化分钟为时分
     formatMinutes:function(minutes){
        var day = parseInt(Math.floor(minutes / 1440));
        var hour = day >0 
                       ?Math.floor((minutes - day*1440)/60) 
                       :Math.floor(minutes/60);  
        var minute = hour > 0
                          ? Math.floor(minutes -day*1440 - hour*60)                          :minutes;
        var time="";       
        if (day > 0) time += day + "天";
        if (hour > 0) time += hour + "小时";
        if (minute > 0) time += minute + "分钟";
        return time;
    },
    //格式化秒数为时分秒
     formatSeconds:function(second) {
	    return [parseInt(second / 60 / 60), parseInt(second / 60 % 60), parseInt(second % 60)].join(":")
	        .replace(/\b(\d)\b/g, "0$1");
    }
};
function escape2(s){
	return encodeURIComponent(s).replace(/%/g,'$');
}
function GetAppInstanceId(){
	var oldinstanceid = $.cookie("uuid");
	if (!oldinstanceid) {
		var nuuid =  (new Date()).valueOf();
		$.cookie("uuid",nuuid);
		return nuuid;
	}
	return oldinstanceid;
}
function IsNumeric(input){
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    return (RE.test(input));
}
function isBoolean(input){
	input= $.trim(input).toLowerCase();
	if(input=="false"||input=="true"){
		return true;
	}
}
function CreateGUID() 
{
   return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
}
function S4() 
{
   return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
}
function val(n){
	if(!n) return 0;
	return IsNumeric(n)?n:0;
}
/* ================StringBuilder========================*/
function StringBuilder(value)
{
	this.strings = new Array();
	//this.append(value);
}
StringBuilder.prototype.append = function (value)
{
	if (value)
	{
		this.strings.push(value);
	}
}
StringBuilder.prototype.appendFormat=function(){
    if( arguments.length == 0 )
        return null;
    var str = arguments[0];
    for(var i=1;i<arguments.length;i++) {
        var re = new RegExp('\\{' + (i-1) + '\\}','gm');
        str = str.replace(re, arguments[i]);
    }
    this.strings.push(str);
}
StringBuilder.prototype.clear = function ()
{
	this.strings.length = 1;
}
StringBuilder.prototype.toString = function (sp)
{
	return this.strings.join(!sp?"":sp);
}
String.format = function() {
    if( arguments.length == 0 )
        return null;
    var str = arguments[0];
    for(var i=1;i<arguments.length;i++) {
        var re = new RegExp('\\{' + (i-1) + '\\}','gm');
        str = str.replace(re, arguments[i]);
    }
    return str;
} 
String.prototype.escapeHTML = function () {                                   
  return this.replace(/>/g,'&gt;').replace(/</g,'&lt;').replace(/"/g,'&quot;').replace(/\'/g,  '&#39;');
  //.replace(/&/g,'&amp;');
};
Array.prototype.indexOf=function(substr,start){
 var ta,rt,d='\0';
 if(start!=null){ta=this.slice(start);rt=start;}else{ta=this;rt=0;}
 var str=d+ta.join(d)+d,t=str.indexOf(d+substr+d);
 if(t==-1)return -1;rt+=str.slice(0,t).replace(/[^\0]/g,'').length;
 return rt;
}
Array.prototype.unique = function()   
{   
   var ret = [],
   resultArr = [],
   returnArr = [];
   var a = {};   
   for(var i=0; i<this.length; i++) {
    if(typeof a[this[i]] == "undefined") {
       a[this[i]] = false; //数组中只有一项
    }
    else{
       a[this[i]] = true;   //数组中有重复的项
    }
   }     
   for(var i in a) {
      resultArr[resultArr.length] = i; 
      if (a[i]) {
        returnArr[returnArr.length] = i; 
    }
   }
   ret[0] = resultArr;
   ret[1] = returnArr;
   //return ret;   
   return resultArr;
}
Array.prototype.clear=function(){   
    this.length=0;   
}   
Array.prototype.insertAt=function(index,obj){   
    this.splice(index,0,obj);   
    return this;
}   
Array.prototype.removeAt=function(index){   
    this.splice(index,1);   
    return this;
}   
Array.prototype.remove=function(obj){   
    var index=this.indexOf(obj);   
    if (index>=0){   
    this.removeAt(index);   
    }   
    return this;
}   
//仅支持数组中数字求和
Array.prototype.sum = function(){
	for (var i=0, sum=0; i < this.length;i++){
		if(typeof this[i]!='number') continue;
		sum += this[i];
	}
	return sum;
}
Array.prototype.pksum = function(){
	for (var i=0, sum=0; i < this.length;i++){
		if(typeof this[i]!='number') continue;
		sum += this[i]>9?0:this[i];
	}
	return sum%10;
}
Array.prototype.max = function(){
	return Math.max.apply({},this)
}
Array.prototype.min = function(){
	return Math.min.apply({},this)
}
String.prototype.toArray=function(_p){
	return this.split(_p!=undefined?_p:'');
};
String.prototype.filterNumber=function(){
	return this.replace(/\d|[^a-o]+/gi,'');//去除数字或非a-o的字符
}
function HTMLEnCode(str) {
	var s = "";
	if (str.length == 0) return "";
	s = str.replace(/&/g, "&gt;");
	s = s.replace(/</g, "&lt;");
	s = s.replace(/>/g, "&gt;");
	s = s.replace(/    /g, "&nbsp;");
	s = s.replace(/\'/g, "'");
	s = s.replace(/\"/g, "&quot;");
	s = s.replace(/\n/g, "<br>");
	return s;
}
function HTMLDeCode(str) {
	var s = "";
	if (str.length == 0) return "";
	s = str.replace(/&gt;/g, "&");
	s = s.replace(/&lt;/g, "<");
	s = s.replace(/&gt;/g, ">");
	s = s.replace(/&nbsp;/g, "    ");
	s = s.replace(/'/g, "\'");
	s = s.replace(/&quot;/g, "\"");
	s = s.replace(/<br>/g, "\n");
	return s;
}
/*TextArea 插入文本*/
function InsertString(tbid, str){
    var tb = document.getElementById(tbid);
    tb.focus();
    if (document.all){
        var r = document.selection.createRange();
        document.selection.empty();
        r.text = str;
        r.collapse();
        r.select();
    }
    else{
        var newstart = tb.selectionStart+str.length;
        tb.value=tb.value.substr(0,tb.selectionStart)+str+tb.value.substring(tb.selectionEnd);
        tb.selectionStart = newstart;
        tb.selectionEnd = newstart;
    }
}
/*TextArea 获取选择处文本*/
function GetSelection(tbid){
    var sel = '';
    if (document.all){
        var r = document.selection.createRange();
        document.selection.empty();
        sel = r.text;
    }
    else{
    	var tb = document.getElementById(tbid);
    	   // tb.focus();
        var start = tb.selectionStart;
        var end = tb.selectionEnd;
        sel = tb.value.substring(start, end);
    }
    return sel;
}
var NET={
	//基本包装请求封装函数
	//默认GET,XML,非缓存
	basicUrl:'/service/',//basic url 
	Async:function(url,params,callback,requestType,method,cache){
		$.ajax({
			"type":!method?"GET":method,
			"url":url,
			"cache":typeof cache=='undefined'?false:cache,
			"dataType":typeof requestType=='undefined'?'html':requestType,
			//dataType: xml,html,script,json
			"data":params,
			//event
			success:function(res){
				callback(res);
			},
			error:function(){
				callback(null);
			}
		});
	},
	loadXML:function(src,bfile) {
		var xmldoc = null;
		if(window.ActiveXObject)
		{
			xmldoc = new ActiveXObject('Microsoft.XMLDOM');
			xmldoc.async = false;
			if (!bfile) {
				xmldoc.loadXML(src);
			}
			else{
				xmldoc.load(src);	
			}
		}
		else if (document.implementation&&document.implementation.createDocument)
		{
			 if (!bfile) {
			  	var oParser=new DOMParser();
		          	 xmldoc=oParser.parseFromString(src,"text/xml");
		          }
		          else{
		          	xmldoc = document.implementation.createDocument('', '', null);
		          	xmldoc.async=false;
				xmldoc.load(src);
			 }
		}
		return xmldoc;
	}
};
/*jQuery Center DIV*/
jQuery.fn.extend({
     setCenter:function(){
        var p={};//在ie浏览器下用top left 和标签相同的做变量，会出问题
        h=$(this).height();
        w=$(this).width();

        p.top =($(document).scrollTop()-30)+($(window).height()-h)/2;
        p.left=($(window).width()-w)/2;

        
         $(this).css("left",p.left+'px');
        $(this).css("top",p.top+'px');
      }
}); 
/*bgiframe*/
(function(a){a.fn.bgiframe=(a.browser.msie&&/msie 6\.0/i.test(navigator.userAgent)?function(d){d=a.extend({top:"auto",left:"auto",width:"auto",height:"auto",opacity:true,src:"javascript:false;"},d);var c='<iframe class="bgiframe"frameborder="0"tabindex="-1"src="'+d.src+'"style="display:block;position:absolute;z-index:-1;'+(d.opacity!==false?"filter:Alpha(Opacity='0');":"")+"top:"+(d.top=="auto"?"expression(((parseInt(this.parentNode.currentStyle.borderTopWidth)||0)*-1)+'px')":b(d.top))+";left:"+(d.left=="auto"?"expression(((parseInt(this.parentNode.currentStyle.borderLeftWidth)||0)*-1)+'px')":b(d.left))+";width:"+(d.width=="auto"?"expression(this.parentNode.offsetWidth+'px')":b(d.width))+";height:"+(d.height=="auto"?"expression(this.parentNode.offsetHeight+'px')":b(d.height))+';"/>';return this.each(function(){if(a(this).children("iframe.bgiframe").length===0){this.insertBefore(document.createElement(c),this.firstChild)}})}:function(){return this});a.fn.bgIframe=a.fn.bgiframe;function b(c){return c&&c.constructor===Number?c+"px":c}})(jQuery);
/*ie checkbox change events*/
$(function () {if ($.browser.msie) {$('input:checkbox').click(function () { this.blur();   this.focus();  });     }});  
/*jquery.cookie.js*/
jQuery.cookie=function(key,value,options){if(arguments.length>1&&(value===null||typeof value!=="object")){options=jQuery.extend({},options);if(value===null)options.expires=-1;if(typeof options.expires==="number"){var days=options.expires,t=options.expires=new Date;t.setDate(t.getDate()+days)}return document.cookie=[encodeURIComponent(key),"=",options.raw?String(value):encodeURIComponent(String(value)),options.expires?"; expires="+options.expires.toUTCString():"",options.path?"; path="+options.path:"",options.domain?"; domain="+options.domain:"",options.secure?"; secure":""].join("")}options=value||{};var result,decode=options.raw?function(s){return s}:decodeURIComponent;return (result=(new RegExp("(?:^|; )"+encodeURIComponent(key)+"=([^;]*)")).exec(document.cookie))?decode(result[1]):null};
/*jscroller-0.4.min.js*/
$jScroller={info:{Name:"ByRei jScroller Plugin for jQuery",Version:.4,Author:"Markus Bordihn (http://markusbordihn.de)",Description:"Next Generation Autoscroller"},config:{obj:[],refresh:30,regExp:{px:/([0-9,.\-]+)px/}},cache:{timer:0,init:0},add:function(parent,child,direction,speed,mouse){if($(parent).length&&$(child).length&&direction&&speed>=1){$(parent).css({overflow:"hidden"});$(child).css({position:"absolute",left:0,top:0});mouse&&$(child).hover(function(){$jScroller.pause($(child),true)},function(){$jScroller.pause($(child),false)});$jScroller.config.obj.push({parent:$(parent),child:$(child),direction:direction,speed:speed,pause:false})}},pause:function(obj,status){if(obj&&typeof status!=="undefined")for(var i in $jScroller.config.obj)if($jScroller.config.obj[i].child.attr("id")===obj.attr("id"))$jScroller.config.obj[i].pause=status},start:function(){if($jScroller.cache.timer===0&&$jScroller.config.refresh>0)$jScroller.cache.timer=window.setInterval($jScroller.scroll,$jScroller.config.refresh);if(!$jScroller.cache.init){$(window).focus($jScroller.start);$(window).resize($jScroller.start);$(window).scroll($jScroller.start);$(document).mousemove($jScroller.start);$.browser.msie&&window.focus();$jScroller.cache.init=1}},stop:function(){if($jScroller.cache.timer){window.clearInterval($jScroller.cache.timer);$jScroller.cache.timer=0}},"get":{px:function(value){var result="";if(value)if(value.match($jScroller.config.regExp.px))if(typeof value.match($jScroller.config.regExp.px)[1]!=="undefined")result=value.match($jScroller.config.regExp.px)[1];return result}},scroll:function(){for(var i in $jScroller.config.obj)if($jScroller.config.obj.hasOwnProperty(i)){var obj=$jScroller.config.obj[i],left=Number($jScroller.get.px(obj.child.css("left"))||0),top=Number($jScroller.get.px(obj.child.css("top"))||0),min_height=obj.parent.height(),min_width=obj.parent.width(),height=obj.child.height(),width=obj.child.width();if(!obj.pause)switch(obj.direction){case "up":if(top<=-1*height)top=min_height;obj.child.css("top",top-obj.speed+"px");break;case "right":if(left>=min_width)left=-1*width;obj.child.css("left",left+obj.speed+"px");break;case "left":if(left<=-1*width)left=min_width;obj.child.css("left",left-obj.speed+"px");break;case "down":if(top>=min_height)top=-1*height;obj.child.css("top",top+obj.speed+"px")}}}};