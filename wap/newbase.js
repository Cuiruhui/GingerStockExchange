window.onload = function(){
//设置字体大小
var hTML = document.documentElement;
var dWidth = hTML.getBoundingClientRect().width;  
hTML.style.fontSize = dWidth/15 + "px";
}