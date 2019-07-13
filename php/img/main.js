function checkLogin(form)
{
	if(InValidValue(form.loginName,"登录用户名不能为空")||
		InValidValue(form.loginPass,"登录密码不能为空")||
		InValidValue(form.checkCode,"请输入验证码") )
		return false;
	else
		return true;
}

function CheckComUserInfo(form)
{
	if(InValidValue(form.ComName,"登录用户名不能为空")||
		InValidValue(form.ComPass,"登录密码不能为空")||
		InValidValue(form.personName,"真实姓名不能为空")||
		InValidValue(form.personId,"请填写身份证号码")||
		InValidValue(form.phone,"请填写联系电话")||
		InValidValue(form.hostName,"网站名称不能为空")||
		InValidValue(form.hostUrl,"网站地址地址不能为空")||
		InValidValue(form.qqNum,"QQ号码请填写")||
		InValidValue(form.bankName,"开户行不能为空")||
		InValidValue(form.cardName,"收款人姓名不能为空")||
		InValidValue(form.cardId,"收款人帐号不能为空")||
		InValidValue(form.cardAddr,"开户行地址不能为空")
		)
		return false;
	else
		return true;
}

function CheckUserInfo(form)
{
	if(InValidValue(form.userName,"登录用户名不能为空")||
		InValidValue(form.userPass,"登录密码不能为空")||
		InValidValue(form.personName,"真实姓名不能为空")||
		InValidValue(form.personId,"请填写身份证号码")||
		InValidValue(form.phone,"请填写联系电话")||
		InValidValue(form.hostName,"网站名称不能为空")||
		InValidValue(form.hostUrl,"网站地址地址不能为空")||
		InValidValue(form.qqNum,"QQ号码请填写")||
		InValidValue(form.bankName,"开户行不能为空")||
		InValidValue(form.cardName,"收款人姓名不能为空")||
		InValidValue(form.cardId,"收款人帐号不能为空")||
		InValidValue(form.cardAddr,"开户行地址不能为空")||
		
		InValidValue(form.salfStr,"安全密码不能为空")||
		InValidValue(form.ClientServerIp,"服务器IP地址不能为空")||
		InValidValue(form.callBack,"CallBack地址不能为空")
		)
		return false;
	else
		return true;
}

function checkComUserModifyPass(form)
{
	if( InValidValue(form.newPass,"请输入新密码")||
		InValidValue(form.rePass,"请再输入一次新密码") )
		return false;
	
	if(form.newPass.value!=form.rePass.value)
	{
		alert("两次输入的密码不唯一！");
		form.rePass.focus();
		form.rePass.select();
		return false;
	}
	
	return true;
}

function checkUserInfoModifyPass(form)
{
	if( InValidValue(form.newPass,"请输入新密码")||
		InValidValue(form.rePass,"请再输入一次新密码") )
		return false;
	
	if(form.newPass.value!=form.rePass.value)
	{
		alert("两次输入的密码不唯一！");
		form.rePass.focus();
		form.rePass.select();
		return false;
	}
	
	return true;
}

function checkSalesModifyPass(form)
{
	if( InValidValue(form.newPass,"请输入新密码")||
		InValidValue(form.rePass,"请再输入一次新密码") )
		return false;
	
	if(form.newPass.value!=form.rePass.value)
	{
		alert("两次输入的密码不唯一！");
		form.rePass.focus();
		form.rePass.select();
		return false;
	}
	
	return true;
}

function checkSuperModifyPass(form)
{
	if( InValidValue(form.newPass,"请输入新密码")||
		InValidValue(form.rePass,"请再输入一次新密码") )
		return false;
	
	if(form.newPass.value!=form.rePass.value)
	{
		alert("两次输入的密码不唯一！");
		form.rePass.focus();
		form.rePass.select();
		return false;
	}
	
	return true;
}

function checkModifyPass(form)
{
	if( InValidValue(form.oldPass,"请输入原始密码")||
		InValidValue(form.newPass,"请输入新密码")||
		InValidValue(form.rePass,"请再输入一次新密码") )
		return false;
	
	if(form.newPass.value!=form.rePass.value)
	{
		alert("两次输入的密码不唯一！");
		form.rePass.focus();
		form.rePass.select();
		return false;
	}
	
	return true;
}

function checkBankForCom(form,money)
{
	if(money<100)
	{
		alert("余额不足100，无法结算");return false;
	}
	var payMoney=parseFloat(form.payMoney.value);
	if(payMoney>money)
	{
		alert("输入的结算金额大于其余额！");form.payMoney.focus();return false;
	}
	return true;
}

function checkBankForUser(form,money)
{
	if(money<100)
	{
		alert("余额不足100，无法结算");return false;
	}
	var payMoney=parseFloat(form.payMoney.value);
	if(payMoney>money)
	{
		alert("输入的结算金额大于其余额！");form.payMoney.focus();return false;
	}
	return true;
}

function checkBankForComAskPay(form)
{
	if(!isNumeric(form.payMoney,"提现金额必须是数字"))return false;
	
	var balance=parseFloat(form.balance.value);
	var tx0=parseFloat(form.tx0.value);
	var tx1=parseFloat(form.tx1.value);
	var payMoney=parseFloat(form.payMoney.value);
	
	if(payMoney<=0)
	{
		alert("申请支付金额必须大于0");return false;
	}

	if( payMoney > balance)
	{
		alert("您输入的提现申请金额超过您账户余额，操作被取消！");
		form.payMoney.focus();
		return false;
	}
	
	var pTx=0;
	if(form.txType[0].checked)//立即提现
	{
		if(payMoney<tx0){alert("您的余额不够支付手续费，操作被取消");return false;}
	}
	else
	{
		if(payMoney<tx1){alert("您的余额不够支付手续费，操作被取消");return false;}
	}	
	
	return confirm("是否确定申请提现？？");	
}

function checkSalesInfo(form)
{
	if(InValidValue(form.saleName,"业务登录用户名必须填写")||
		InValidValue(form.salePass,"业务登录密码必须填写")||
		InValidValue(form.personName,"请填写业务姓名")||
		!isNumeric(form.commission,"提成比例必须是小数") )
		return false;
	else
	{
		if(!isCommissionForSales(form.commission))
		{
			return confirm("业务提成大于面值的10％，是否要继续？");
		}
		return true;
	}
}

function checkChannel(form)
{
	if(InValidValue(form.channelName,"通道名称不能为空")||
		!isNumeric(form.commission,"提成比例必须是小数"))
		return false;
	else
	{
		if(!isCommissionForUsers(form.commission))
		{
			return confirm("提成比例超过95%，是否要继续？");
		}
		return true;		
	}
}

function checkDoMethod(form)
{
	if(InValidValue(form.methodName,"名称不能为空"))
		return false;
	else
		return true;
}

function checkChannelForCom(form)
{
	if(!isNumeric(form.UserCommission,"提成比例必须是小数"))
		return false;
	else
	{
		if(!isCommission(form.UserCommission,form.commission))
			return confirm("'销售分成比例'大于'代理提成比例'，是否要继续？");
			
		return true;
	}
}

function checkChannelForUser(form)
{
	if(!isNumeric(form.commission,"提成比例必须是小数"))
		return false;
	else
	{
		return confirm("请检查‘销售分成比例’，是否大于‘代理分成比例’，是否要继续？");
	}
}

function checkSuperAdd(form)
{
	if(InValidValue(form.superName,"登录用户名不能为空")||
		InValidValue(form.superPass,"登录密码不能为空"))
		return false;
	else
	{
		return true;
	}	
}

function isNumeric(obj,msg)
{
	if(InValidValue(obj,msg))return false;
	var p=/^\d+(\.\d+)?$/;
	if(!p.test(obj.value))
	{
		alert(msg);
		obj.focus();
		obj.select();
		return false;
	}
	return true;
}

function isCommission(oa,ob)
{
	var a=parseFloat(oa.value);
	var b=parseFloat(ob.value);
	if(a>b)return false;
	return true;
}

function isCommissionForSales(obj)
{
	//alert(val);
	var a=parseFloat(obj.value);	
	if(a>=0.1)return false;
	return true;
}

function isCommissionForUsers(obj)
{
	//alert(val);
	var a=parseFloat(obj.value);	
	if(a>0.95)return false;
	return true;
}

function checkPay()
{
	return confirm("确定支付嘛？");	
}

function InValidValue(obj,msg)
{
	if(obj.value=="")
	{
		obj.focus();
		alert(msg);
		return true;
	}
	return false;
}

function askDel()
{
	return confirm("此操作不可恢复,确定要删除嘛?");	
}

function showHidden(id)
{
	var obj=document.getElementById("msg_"+id);	
	if(obj.style.display=="none")
		obj.style.display="block";
	else
		obj.style.display="none"
}

