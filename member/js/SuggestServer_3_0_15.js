var SuggestServer=function(){this._Q="http://suggest3.sinajs.cn/suggest/type=@TYPE@&key=@KEY@&name=@NAME@";this._R="";this._S=null;this._T=null;this._U="";this._V="";this._W=null;this._X=null;this._Y={};this._Z={};this._00=false;this._01="";this._02={"11":"A 股","12":"B 股","13":"权证","14":"期货","15":"债券","21":"开基","22":"ETF","23":"LOF","24":"货基","25":"QDII","26":"封基","31":"港股","32":"窝轮","41":"美股","42":"外期"};this._03={"input":null,"loader":null,"default":"","type":0,"max":10,"width":220,"link":null,"target":"_blank","head":["选项","代码","名称"],"body":[-1,2,4],"fix":{"firefox":[1,1]},"onshow":function(){},"onhide":function(){},"hideSelectForIE6":false,"callback":null};this._04=function(_a){return document.getElementById(_a);};this._05=function(){return(new Date()).getTime();};this._06=function(_b,_c){var _d=this;return function(){var _e=null;if(typeof _c!="undefined"){for(var i=0;i<arguments.length;i++){_c.push(arguments[i]);}_e=_c;}else{_e=arguments;}return _b.apply(_d,_e);};};this._07=function(_f,_g,_h){if(window.addEventListener){_f.addEventListener(_g,_h,false);}else if(window.attachEvent){_f.attachEvent("on"+_g,_h);}};this._08=function(){var _i=navigator.userAgent;if(/^Opera.*/.test(_i)==true){return "opera";}else if(/^Mozilla\/4\.0.*/.test(_i)==true){if(_i.indexOf("MSIE 7.0")!=-1){return "ie7";}else if(_i.indexOf("MSIE 6.0")!=-1){return "ie6";}else if(_i.indexOf("MSIE")!=-1){return "ie";}else{return "mozilla4";}}else if(/^Mozilla\/5\.0.*/.test(_i)==true){if(_i.indexOf("Chrome")!=-1){return "chrome";}else if(_i.indexOf("Safari")!=-1){return "safari";}else if(_i.indexOf("Firefox")!=-1){return "firefox";}else{return "mozilla5";}}else{return "unknown"}return "unknown"};this._09=function(){var _j=0;var _k=0;var _f=this._0a;do{_j+=_f.offsetTop||0;_k+=_f.offsetLeft||0;if(_f.style.position!="relative"){break;}_f=_f.offsetParent;}while(_f);var _l=[this._0a.parentNode.style.borderTopWidth.replace("px","")*1,this._0a.parentNode.style.borderLeftWidth.replace("px","")*1];var _m=this._08();var _n="all" in this._03["fix"]?this._03["fix"]["all"]:[0,0];var _o=_m in this._03["fix"]?this._03["fix"][_m]:[0,0];_o=[_n[0]+_o[0],_n[1]+_o[1]];if(this._T.style.top!=_j+"px"){this._T.style.top=_j-_l[0]+_o[0]+"px";}if(this._T.style.left!=_k+"px"){this._T.style.left=_k-_l[1]+_o[1]+"px";}var _p=this._0a.style.borderTopWidth;var _q=this._0a.style.borderBottomWidth;var _r=this._0a.clientHeight;_r+=_p!=""?_p.replace("px","")*1:2;_r+=_q!=""?_q.replace("px","")*1:2;if(this._T.style.marginTop!=_r+"px"){this._T.style.marginTop=_r+"px";}};this._0b=function(_g){return{"1":"stock","2":"fund","3":"hk","4":"us"}[_g.substr(0,1)];};this._0c=function(){var _s=this._0a.value;if(("key_"+_s)in this._Z&&this._Z["key_"+_s]!=""){if(this._T==null){this._T=document.createElement("div");this._T.style.cssText+="display:none; filter:alpha(opacity=95); opacity:0.95; position:absolute; width:"+this._03["width"]+"px; z-index:999;";this._0a.parentNode.insertBefore(this._T,this._0a);this._T["suggest"]=this;}this._09();var _t='';_t+='<table style="border-collapse:collapse; line-height:18px; border:2px solid #EEE; background-color:#FFF; font-size:12px; text-align:center; color:#999; width:'+(this._03["width"]-2)+'px;">'
if(this._03["head"]!=null){_t+='<tr style="background-color:#F3F3F3;">';for(var i in this._03["head"]){_t+='<td>'+this._03["head"][i]+'</td>';}_t+='</tr>';}var _u=this._Z["key_"+_s].replace(/&amp;/g,"&").replace(/;$/,"").split(";");var _v=_u.length>this._03["max"]?this._03["max"]:_u.length;var _w='parentNode.parentNode.parentNode[\'suggest\']';for(var i=0;i<_v;i++){var _x=_u[i].split(",");_x[-1]=_x[0].replace(_s,'<span style="color:#F00;">'+_s+'</span>');_x[-2]=_x[1]in this._02?this._02[_x[1]]:"——";var _y=this._03["link"]==null||this._03["link"]==""?['<td style="padding:0px;"><span style="display:block; padding:1px;">','</span></td>']:['<td style="padding:0px;"><a href="'+this._03["link"].replace(/@type@/g,this._0b(_x[1])).replace(/@code@/g,this._0d(_x))+'" hidefocus="true" onmousedown="return this.parentNode.parentNode.'+_w+'[\'hidepause\'](this);" onclick="return this.parentNode.parentNode.'+_w+'[\'hideresume\'](this);" style="color:#999; display:block; outline:none; padding:1px; text-decoration:none; width:100%;" target="'+this._03["target"]+'">','</a></td>'];_t+='<tr id="'+_u[i]+'" style="cursor:pointer;" onmouseover="this.'+_w+'[\'mouseoverLine\'](this);" onmouseout="this.'+_w+'[\'mouseoutLine\'](this);" onmousedown="this.'+_w+'[\'setLineMouse\'](this);">';for(var j in this._03["body"]){_t+=_y[0]+_x[this._03["body"][j]]+_y[1];}_t+='</tr>';}_t+='</table>';this._Y["key_"+_s]=_t;this._X=null;var _z=document.createElement("div");this._T.innerHTML=this._Y["key_"+_s];this._0e();}else{this._0f();}};this._0g=function(_A){var _B="";if(_A._0h&&_A._0i){_B="#F8FBDF";}else if(_A._0h){_B="#F1F5FC";}else if(_A._0i){_B="#FCFEDF";}if(_A.style.backgroundColor!=_B){_A.style.backgroundColor=_B;}};this["mouseoverLine"]=function(_A){_A._0i=true;this._0g(_A);};this["mouseoutLine"]=function(_A){_A._0i=false;this._0g(_A);};this["setLineMouse"]=function(_A){this["setLine"](_A);if(this._W!=null){this._W(this._0a.value);}};this._0d=function(_C){switch(_C[1]){case "11":return _C[3];break;case "12":return _C[3];break;case "13":return _C[3];break;case "14":return _C[3];break;case "15":return _C[3];break;case "21":return _C[3];break;case "22":return _C[3];break;case "23":return _C[3];break;case "24":return _C[3];break;case "25":return _C[3];break;case "26":return _C[3];break;default:return _C[2];break;}};this["setLine"]=function(_A){var _C=_A.id.split(",");var _D=this._0d(_C);var _s=_A.id;for(var i=2;i<6;i++){this._Z["key_"+_C[i]]=_s+";";}this._V=_D;this._0a.value=_D;if(this._X!=null){this._X._0h=false;this._0g(this._X);}_A._0h=true;this._0g(_A);this._X=_A;};this._0e=function(){if(this._T!=null){this._T.style.display="";this._03["onshow"]();if(this._03["hideSelectForIE6"]&&this._01=="ie6"){var _E=document.getElementsByTagName("select");for(var i=0;i<_E.length;i++){_E[i].style.visibility="hidden";}}}};this["hidepause"]=function(){this._00=true;};this["hideresume"]=function(){this._00=false;this._0j();};this._0f=function(){if(this._00==false){this._0j();}};this._0j=function(){if(this._T!=null){this._T.style.display="none";this._03["onhide"]();if(this._03["hideSelectForIE6"]&&this._01=="ie6"){var _E=document.getElementsByTagName("select");for(var i=0;i<_E.length;i++){_E[i].style.visibility="visible";}}}};this._0k=function(_s,_F,_G){if(this._S==null){this._S=document.createElement("div");this._S.style.display="none";this._0a.parentNode.insertBefore(this._S,this._0a);}var _H="suggestdata_"+this._05();var _I=document.createElement("script");_I.type="text/javascript";_I.charset="gb2312";_I.src=this._R.replace("@NAME@",_H).replace("@KEY@",_s);_I._0l=this;if(_F){_I._0m=_F;}if(_G){_I._0n=_G;}_I._0o=_s;_I._0p=_H;_I[document.all?"onreadystatechange":"onload"]=function(){if(document.all&&this.readyState!="loaded"&&this.readyState!="complete"){return;}var _J=window[this._0p];if(typeof _J!="undefined"){this._0l._Z["key_"+this._0o]=_J;this._0m(_J);window[this._0p]=null;}else if(this._0q){this._0q("");}this._0l=null;this._0o=null;this._0p=null;this[document.all?"onreadystatechange":"onload"]=null;this.parentNode.removeChild(this);};this._S.appendChild(_I);};this._0r=function(){var _s=this._0a.value;if(this._V!=_s){this._V=_s;if(_s!=""){if(("key_"+_s)in this._Z){this._0c();}else{this._0k(_s,this._06(this._0c),this._06(this._0f));}}else{if(this._T!=null){this._X=null;this._T.innerHTML="";}this._0f();}}else{this._0e();}};this._0s=function(){if(this._0a.value==this._U){this._0a.value="";}this._V="";this._0r();};this._0t=function(){if(this._0a.value==""){this._0a.value=this._U;}this._V="";this._0f();};this._0u=function(){var _K=arguments[0]||window.event;var _L=this._03["head"]==null?0:1;switch(_K.keyCode){case 38:if(this._T!=null){this["setLine"](this._T.firstChild.rows[(!this._X||this._X.rowIndex==_L)?this._T.firstChild.rows.length-1:this._X.rowIndex-1]);}break;case 40:if(this._T!=null){this["setLine"](this._T.firstChild.rows[(!this._X||this._X.rowIndex==this._T.firstChild.rows.length-1)?_L:this._X.rowIndex+1]);}break;case 13:if(this._T!=null){if(this._X!=null){this["setLine"](this._X);}if(this._W!=null){this._W(this._0a.value);}}this._0f();break;default:this._0r();break;}};this.getCodeFromCache=function(_s){if(("key_"+_s)in this._Z){return this._Z["key_"+_s];}};this.getCode=function(_s,_M){if(("key_"+_s)in this._Z){_M(this._Z["key_"+_s]);}else{this._0k(_s,_M,_M);}};this.changeType=function(_g){this._Y={};this._Z={};this._0a.value=this._U;if(typeof _g!="undefined"){var _N="";switch(_g.toLowerCase()){case "stock":_N="11,12,13,14,15";break;case "fund":_N="21,22,23,24,25,26";break;case "hkstock":_N="31";break;case "hk":_N="31,32";break;case "usstock":_N="41";break;case "us":_N="41,42";break;default:_N=_g;break;}this._R=this._Q.replace("@TYPE@",_N);}else{this._R=this._Q.replace("type=@TYPE@&","");}this._03["type"]=_g;};this.changeLink=function(_O){this._03["link"]=_O;this._0c();this._0f();};this.bind=function(_P){if(typeof _P!="undefined"){for(var i in _P){this._03[i]=_P[i];}}this._01=this._08();this._0a=typeof this._03["input"]=="string"?document.getElementById(this._03["input"]):this._03["input"];if(this._03["loader"]!=null){this._S=typeof this._03["loader"]=="string"?document.getElementById(this._03["loader"]):this._03["loader"];}if(this._0a){this.changeType(this._03["type"]);this._U=this._03["default"]==null||this._03["default"]==""?this._0a.value:this._03["default"];this._0a.value=this._U;this._0a.setAttribute("autocomplete","off");this._0a.autoComplete="off";this._07(this._0a,"focus",this._06(this._0s));this._07(this._0a,"blur",this._06(this._0t));this._07(this._0a,"keyup",this._06(this._0u));this._07(this._0a,"mouseup",this._06(this._0u));this._W=this._03["callback"];}};};


