document.writeln("<div id=\"appdown\" style=\"position:absolute; left:10px; z-index: 999; top:150px;\"><a href=\"/appdown/\" target=\"_blank\"><img src=\"/appdown/index_android.jpg\" border=\"0\" /></a><div style=\"text-align: right;\"onClick='javascript:window.hide()'>X</DIV></div>");

$(document).ready(function(){
        //var menuYloc = $('#appdown').offset().top;    
		var menuYloc = 150;
        $(window).scroll(function() {  
            var offsetTop = menuYloc + $(window).scrollTop() + 'px';
			var offsetLeft = '10px';
            $('#appdown').animate({ top: offsetTop ,left:offsetLeft }, { duration: 200, queue: false });
        });
    });

function hide()  
{   
appdown.style.visibility="hidden";
}