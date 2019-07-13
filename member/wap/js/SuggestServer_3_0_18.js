var SuggestServer=function(){
	this._O="http://suggest3.sinajs.cn/suggest/type=@TYPE@&key=@KEY@&name=@NAME@";
	this._P="";
	this._Q=null;
	this._R=null;
	this._S="";
	this._T="";
	this._U=null;
	this._V=null;
	this._W={};
	this._X={};
	this._Y=false;
	this._Z="";
	this._00={
		"11":"A ��","12":"B ��","13":"Ȩ֤","14":"�ڻ�","15":"ծȯ","21":"����","22":"ETF","23":"LOF","24":"����","25":"QDII","26":"���","31":"�۹�","32":"����","41":"����","42":"����"
	};
	this._01={
		"input":null,"loader":null,"value":null,"default":null,"type":0,"max":10,"width":220,"link":null,"target":"_blank","head":["ѡ��","����","����"],"body":[-1,2,4],"fix":{
			"firefox":[1,1]
		}
		,"onshow":function(){},"onhide":function(){},"hideSelectForIE6":false,"callback":null
	};
	this._02=function(_a){
		return document.getElementById(_a);
	};
	this._03=function(){
		return(new Date()).getTime();
	};
	this._04=function(_b,_c){
		var _d=this;
		return function(){
			var _e=null;
			if(typeof _c!="undefined"){
				for(var i=0;i<arguments.length;i++){
					_c.push(arguments[i]);
				}
				_e=_c;
			}
			else{
				_e=arguments;
			}
			return _b.apply(_d,_e);
		};
	};
	this._05=function(_f,_g,_h){
		if(window.addEventListener){
			_f.addEventListener(_g,_h,false);
		}
		else if(window.attachEvent){
			_f.attachEvent("on"+_g,_h);
		}
	};
	this._06=function(){
		var _i=0;
		var _j=0;
		var _f=this._07;
		do{
			_i+=_f.offsetTop||0;
			_j+=_f.offsetLeft||0;
			if(_f.style.position!="relative"){
				break;
			}
			_f=_f.offsetParent;
		}
		while(_f);
		var _k=[this._07.parentNode.style.borderTopWidth.replace("px","")*1,this._07.parentNode.style.borderLeftWidth.replace("px","")*1];
		_l=[0,0];
		if(this._R.style.top!=_i+"px"){
			this._R.style.top=_i-_k[0]+_l[0]+"px";
		}
		if(this._R.style.left!=_j+"px"){
			this._R.style.left=_j-_k[1]+_l[1]+"px";
		}
		var _m=this._07.style.borderTopWidth;
		var _n=this._07.style.borderBottomWidth;
		var _o=this._07.clientHeight;
		_o+=_m!=""?_m.replace("px","")*1:2;
		_o+=_n!=""?_n.replace("px","")*1:2;
		if(this._R.style.marginTop!=_o+"px"){
			this._R.style.marginTop=_o+"px";
		}
	};
	this._08=function(_g){
		return{
			"1":"stock","2":"fund","3":"hk","4":"us"
		}
		[_g.substr(0,1)];
	};
	this._09=function(){
		var _p=this._07.value;
		if(("key_"+_p)in this._X&&this._X["key_"+_p]!=""){
			if(this._R==null){
				this._R=document.createElement("div");
				this._R.style.cssText+="display:none; filter:alpha(opacity=95); opacity:0.95; position:absolute; width:"+this._01["width"]+"px; z-index:999;";
				this._07.parentNode.insertBefore(this._R,this._07);
				this._R["suggest"]=this;
			}
			this._06();
			var _q='';
			_q+='<table style="border-collapse:collapse; line-height:18px; border:2px solid #EEE; background-color:#FFF; font-size:12px; text-align:center; color:#999; width:'+(this._01["width"]-2)+'px;">'
			if(this._01["head"]!=null){
				_q+='<tr style="background-color:#F3F3F3;">';
				for(var i in this._01["head"]){
					_q+='<td>'+this._01["head"][i]+'</td>';
				}
				_q+='</tr>';
			}
			var _r=this._X["key_"+_p].replace(/&amp;/g,"&").replace(/;$/,"").split(";");
			var _s=_r.length>this._01["max"]?this._01["max"]:_r.length;
			var _t='parentNode.parentNode.parentNode[\'suggest\']';
			for(var i=0;i<_s;i++){
				var _u=_r[i].split(",");
				_u[-1]=_u[0].replace(_p,'<span style="color:#F00;">'+_p+'</span>');
				_u[-2]=_u[1]in this._00?this._00[_u[1]]:"����";
				var _v=this._01["link"]==null||this._01["link"]==""?['<td style="padding:0px;"><span style="display:block; padding:1px;">','</span></td>']:['<td style="padding:0px;"><a href="'+this._01["link"].replace(/@type@/g,this._08(_u[1])).replace(/@code@/g,this._0a(_u))+'" hidefocus="true" onmousedown="return this.parentNode.parentNode.'+_t+'[\'hidepause\'](this);" onclick="return this.parentNode.parentNode.'+_t+'[\'hideresume\'](this);" style="color:#999; display:block; outline:none; padding:1px; text-decoration:none; width:100%;" target="'+this._01["target"]+'">','</a></td>'];
				_q+='<tr id="'+_r[i]+'" style="cursor:pointer;" onmouseover="this.'+_t+'[\'mouseoverLine\'](this);" onmouseout="this.'+_t+'[\'mouseoutLine\'](this);" onmousedown="this.'+_t+'[\'setLineMouse\'](this);">';
				for(var j in this._01["body"]){
					_q+=_v[0]+_u[this._01["body"][j]]+_v[1];
				}
				_q+='</tr>';
			}
			_q+='</table>';
			this._W["key_"+_p]=_q;
			this._V=null;
			var _w=document.createElement("div");
			this._R.innerHTML=this._W["key_"+_p];
			this._0b();
		}
		else{
			this._0c();
		}
	};
	this._0d=function(_x){
		var _y="";
		if(_x._0e&&_x._0f){
			_y="#F8FBDF";
		}
		else if(_x._0e){
			_y="#F1F5FC";
		}
		else if(_x._0f){
			_y="#FCFEDF";
		}
		if(_x.style.backgroundColor!=_y){
			_x.style.backgroundColor=_y;
		}
	};
	this["mouseoverLine"]=function(_x){
		_x._0f=true;
		this._0d(_x);
	};
	this["mouseoutLine"]=function(_x){
		_x._0f=false;
		this._0d(_x);
	};
	this["setLineMouse"]=function(_x){
		this["setLine"](_x);
		if(this._U!=null){
			this._U(this._07.value);
		}
	};
	this._0a=function(_z){
		switch(_z[1]){
			case "11":return _z[3];
			break;
			case "12":return _z[3];
			break;
			case "13":return _z[3];
			break;
			case "14":return _z[3];
			break;
			case "15":return _z[3];
			break;
			case "21":return _z[3];
			break;
			case "22":return _z[3];
			break;
			case "23":return _z[3];
			break;
			case "24":return _z[3];
			break;
			case "25":return _z[3];
			break;
			case "26":return _z[3];
			break;
			default:return _z[2];
			break;
		}
	};
	this["setLine"]=function(_x){
		var _z=_x.id.split(",");
		var _A=this._01["value"];
		if(_A!=null&&_A!=""){
			for(var i=0;i<_z.length;i++){
				_A=_A.replace(new RegExp("@"+i+"@","g"),_z[i]);
			}
			var _B=_A;
		}
		else{
			var _B=this._0a(_z);
		}
		var _p=_x.id;
		for(var i=2;i<6;i++){
			this._X["key_"+_z[i]]=_p+";";
		}
		this._T=_B;
		this._07.value=_B;
		if(this._V!=null){
			this._V._0e=false;
			this._0d(this._V);
		}
		_x._0e=true;
		this._0d(_x);
		this._V=_x;
	};
	this._0b=function(){
		if(this._R!=null){
			this._R.style.display="";
			this._01["onshow"]();
			if(this._01["hideSelectForIE6"]&&this._Z=="ie6"){
				var _C=document.getElementsByTagName("select");
				for(var i=0;i<_C.length;i++){
					_C[i].style.visibility="hidden";
				}
			}
		}
	};
	this["hidepause"]=function(){
		this._Y=true;
	};
	this["hideresume"]=function(){
		this._Y=false;
		this._0g();
	};
	this._0c=function(){
		if(this._Y==false){
			this._0g();
		}
	};
	this._0g=function(){
		if(this._R!=null){
			this._R.style.display="none";
			this._01["onhide"]();
			if(this._01["hideSelectForIE6"]&&this._Z=="ie6"){
				var _C=document.getElementsByTagName("select");
				for(var i=0;i<_C.length;i++){
					_C[i].style.visibility="visible";
				}
			}
		}
	};
	this._0h=function(_p,_D,_E){
		if(this._Q==null){
			this._Q=document.createElement("div");
			this._Q.style.display="none";
			this._07.parentNode.insertBefore(this._Q,this._07);
		}
		var _F="suggestdata_"+this._03();
		var _G=document.createElement("script");
		_G.type="text/javascript";
		_G.charset="gb2312";
		_G.src=this._P.replace("@NAME@",_F).replace("@KEY@",_p);
		_G._0i=this;
		if(_D){
			_G._0j=_D;
		}
		if(_E){
			_G._0k=_E;
		}
		_G._0l=_p;
		_G._0m=_F;
		_G[document.all?"onreadystatechange":"onload"]=function(){
			if(document.all&&this.readyState!="loaded"&&this.readyState!="complete"){
				return;
			}
			var _H=window[this._0m];
			if(typeof _H!="undefined"){
				this._0i._X["key_"+this._0l]=_H;
				this._0j(_H);
				window[this._0m]=null;
			}
			else if(this._0n){
				this._0n("");
			}
			this._0i=null;
			this._0l=null;
			this._0m=null;
			this[document.all?"onreadystatechange":"onload"]=null;
			this.parentNode.removeChild(this);
		};
		this._Q.appendChild(_G);
	};
	this._0o=function(){
		var _p=this._07.value;
		if(this._T!=_p){
			this._T=_p;
			if(_p!=""){
				if(("key_"+_p)in this._X){
					this._09();
				}
				else{
					this._0h(_p,this._04(this._09),this._04(this._0c));
				}
			}
			else{
				if(this._R!=null){
					this._V=null;
					this._R.innerHTML="";
				}
				this._0c();
			}
		}
		else{
			this._0b();
		}
	};
	this._0p=function(){
		if(this._07.value==this._S){
			this._07.value="";
		}
		this._T="";
		this._0o();
	};
	this._0q=function(){
		if(this._07.value==""){
			this._07.value=this._S;
		}
		this._T="";
		this._0c();
	};
	this._0r=function(){
		var _I=arguments[0]||window.event;
		var _J=this._01["head"]==null?0:1;
		switch(_I.keyCode){
			case 38:if(this._R!=null){
				this["setLine"](this._R.firstChild.rows[(!this._V||this._V.rowIndex==_J)?this._R.firstChild.rows.length-1:this._V.rowIndex-1]);
			}
			break;
			case 40:if(this._R!=null){
				this["setLine"](this._R.firstChild.rows[(!this._V||this._V.rowIndex==this._R.firstChild.rows.length-1)?_J:this._V.rowIndex+1]);
			}
			break;
			case 13:if(this._R!=null){
				if(this._V!=null){
					this["setLine"](this._V);
				}
				if(this._U!=null){
					this._U(this._07.value);
				}
			}
			this._0c();
			break;
			default:this._0o();
			break;
		}
	};
	this.getCodeFromCache=function(_p){
		if(("key_"+_p)in this._X){
			return this._X["key_"+_p];
		}
	};
	this.getCode=function(_p,_K){
		if(("key_"+_p)in this._X){
			_K(this._X["key_"+_p]);
		}
		else{
			this._0h(_p,_K,_K);
		}
	};
	this.changeType=function(_g){
		this._W={};
		this._X={};
		this._07.value=this._S;
		if(typeof _g!="undefined"){
			var _L="";
			switch(_g.toLowerCase()){
				case "stock":_L="11,12,13,14,15";
				break;
				case "fund":_L="21,22,23,24,25,26";
				break;
				case "hkstock":_L="31";
				break;
				case "hk":_L="31,32";
				break;
				case "usstock":_L="41";
				break;
				case "us":_L="41,42";
				break;
				default:_L=_g;
				break;
			}
			this._P=this._O.replace("@TYPE@",_L);
		}
		else{
			this._P=this._O.replace("type=@TYPE@&","");
		}
		this._01["type"]=_g;
	};
	this.changeLink=function(_M){
		this._01["link"]=_M;
		this._09();
		this._0c();
	};
	this.bind=function(_N){
		if(typeof _N!="undefined"){
			for(var i in _N){
				this._01[i]=_N[i];
			}
		}
		this._07=typeof this._01["input"]=="string"?document.getElementById(this._01["input"]):this._01["input"];
		if(this._01["loader"]!=null){
			this._Q=typeof this._01["loader"]=="string"?document.getElementById(this._01["loader"]):this._01["loader"];
		}
		if(this._07){
			this._S=(this._01["default"]==null||this._01["default"]=="")?this._07.value:this._01["default"];
			this.changeType(this._01["type"]);
			this._07.value=this._S;
			this._07.setAttribute("autocomplete","off");
			this._07.autoComplete="off";
			this._05(this._07,"focus",this._04(this._0p));
			this._05(this._07,"blur",this._04(this._0q));
			this._05(this._07,"keyup",this._04(this._0r));
			this._05(this._07,"mouseup",this._04(this._0r));
			this._U=this._01["callback"];
		}
	};
};
