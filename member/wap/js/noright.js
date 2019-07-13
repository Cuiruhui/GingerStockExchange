function nocontextmenu() 
{
	event.cancelBubble = true
	event.returnValue = false;
	return false;
}

function norightclick(e) 
{
	if (window.Event) 
	{
		if (e.which == 2 || e.which == 3)
		return false;
	}
	else if (event.button == 2 || event.button == 3)
		{
			event.cancelBubble = true
			event.returnValue = false;
			return false;
		}
}

function KillErros()
{
	return true;
}
window.onerror = KillErros;
document.oncontextmenu = nocontextmenu; //对ie5.0以上
document.onmousedown = norightclick; //对其它浏览器