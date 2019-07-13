function checkLogin(form)
{
	if(InValidValue(form.loginName,"��¼�û�������Ϊ��")||
		InValidValue(form.loginPass,"��¼���벻��Ϊ��")||
		InValidValue(form.checkCode,"��������֤��") )
		return false;
	else
		return true;
}

function CheckComUserInfo(form)
{
	if(InValidValue(form.ComName,"��¼�û�������Ϊ��")||
		InValidValue(form.ComPass,"��¼���벻��Ϊ��")||
		InValidValue(form.personName,"��ʵ��������Ϊ��")||
		InValidValue(form.personId,"����д���֤����")||
		InValidValue(form.phone,"����д��ϵ�绰")||
		InValidValue(form.hostName,"��վ���Ʋ���Ϊ��")||
		InValidValue(form.hostUrl,"��վ��ַ��ַ����Ϊ��")||
		InValidValue(form.qqNum,"QQ��������д")||
		InValidValue(form.bankName,"�����в���Ϊ��")||
		InValidValue(form.cardName,"�տ�����������Ϊ��")||
		InValidValue(form.cardId,"�տ����ʺŲ���Ϊ��")||
		InValidValue(form.cardAddr,"�����е�ַ����Ϊ��")
		)
		return false;
	else
		return true;
}

function CheckUserInfo(form)
{
	if(InValidValue(form.userName,"��¼�û�������Ϊ��")||
		InValidValue(form.userPass,"��¼���벻��Ϊ��")||
		InValidValue(form.personName,"��ʵ��������Ϊ��")||
		InValidValue(form.personId,"����д���֤����")||
		InValidValue(form.phone,"����д��ϵ�绰")||
		InValidValue(form.hostName,"��վ���Ʋ���Ϊ��")||
		InValidValue(form.hostUrl,"��վ��ַ��ַ����Ϊ��")||
		InValidValue(form.qqNum,"QQ��������д")||
		InValidValue(form.bankName,"�����в���Ϊ��")||
		InValidValue(form.cardName,"�տ�����������Ϊ��")||
		InValidValue(form.cardId,"�տ����ʺŲ���Ϊ��")||
		InValidValue(form.cardAddr,"�����е�ַ����Ϊ��")||
		
		InValidValue(form.salfStr,"��ȫ���벻��Ϊ��")||
		InValidValue(form.ClientServerIp,"������IP��ַ����Ϊ��")||
		InValidValue(form.callBack,"CallBack��ַ����Ϊ��")
		)
		return false;
	else
		return true;
}

function checkComUserModifyPass(form)
{
	if( InValidValue(form.newPass,"������������")||
		InValidValue(form.rePass,"��������һ��������") )
		return false;
	
	if(form.newPass.value!=form.rePass.value)
	{
		alert("������������벻Ψһ��");
		form.rePass.focus();
		form.rePass.select();
		return false;
	}
	
	return true;
}

function checkUserInfoModifyPass(form)
{
	if( InValidValue(form.newPass,"������������")||
		InValidValue(form.rePass,"��������һ��������") )
		return false;
	
	if(form.newPass.value!=form.rePass.value)
	{
		alert("������������벻Ψһ��");
		form.rePass.focus();
		form.rePass.select();
		return false;
	}
	
	return true;
}

function checkSalesModifyPass(form)
{
	if( InValidValue(form.newPass,"������������")||
		InValidValue(form.rePass,"��������һ��������") )
		return false;
	
	if(form.newPass.value!=form.rePass.value)
	{
		alert("������������벻Ψһ��");
		form.rePass.focus();
		form.rePass.select();
		return false;
	}
	
	return true;
}

function checkSuperModifyPass(form)
{
	if( InValidValue(form.newPass,"������������")||
		InValidValue(form.rePass,"��������һ��������") )
		return false;
	
	if(form.newPass.value!=form.rePass.value)
	{
		alert("������������벻Ψһ��");
		form.rePass.focus();
		form.rePass.select();
		return false;
	}
	
	return true;
}

function checkModifyPass(form)
{
	if( InValidValue(form.oldPass,"������ԭʼ����")||
		InValidValue(form.newPass,"������������")||
		InValidValue(form.rePass,"��������һ��������") )
		return false;
	
	if(form.newPass.value!=form.rePass.value)
	{
		alert("������������벻Ψһ��");
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
		alert("����100���޷�����");return false;
	}
	var payMoney=parseFloat(form.payMoney.value);
	if(payMoney>money)
	{
		alert("����Ľ������������");form.payMoney.focus();return false;
	}
	return true;
}

function checkBankForUser(form,money)
{
	if(money<100)
	{
		alert("����100���޷�����");return false;
	}
	var payMoney=parseFloat(form.payMoney.value);
	if(payMoney>money)
	{
		alert("����Ľ������������");form.payMoney.focus();return false;
	}
	return true;
}

function checkBankForComAskPay(form)
{
	if(!isNumeric(form.payMoney,"���ֽ�����������"))return false;
	
	var balance=parseFloat(form.balance.value);
	var tx0=parseFloat(form.tx0.value);
	var tx1=parseFloat(form.tx1.value);
	var payMoney=parseFloat(form.payMoney.value);
	
	if(payMoney<=0)
	{
		alert("����֧�����������0");return false;
	}

	if( payMoney > balance)
	{
		alert("���������������������˻���������ȡ����");
		form.payMoney.focus();
		return false;
	}
	
	var pTx=0;
	if(form.txType[0].checked)//��������
	{
		if(payMoney<tx0){alert("��������֧�������ѣ�������ȡ��");return false;}
	}
	else
	{
		if(payMoney<tx1){alert("��������֧�������ѣ�������ȡ��");return false;}
	}	
	
	return confirm("�Ƿ�ȷ���������֣���");	
}

function checkSalesInfo(form)
{
	if(InValidValue(form.saleName,"ҵ���¼�û���������д")||
		InValidValue(form.salePass,"ҵ���¼���������д")||
		InValidValue(form.personName,"����дҵ������")||
		!isNumeric(form.commission,"��ɱ���������С��") )
		return false;
	else
	{
		if(!isCommissionForSales(form.commission))
		{
			return confirm("ҵ����ɴ�����ֵ��10�����Ƿ�Ҫ������");
		}
		return true;
	}
}

function checkChannel(form)
{
	if(InValidValue(form.channelName,"ͨ�����Ʋ���Ϊ��")||
		!isNumeric(form.commission,"��ɱ���������С��"))
		return false;
	else
	{
		if(!isCommissionForUsers(form.commission))
		{
			return confirm("��ɱ�������95%���Ƿ�Ҫ������");
		}
		return true;		
	}
}

function checkDoMethod(form)
{
	if(InValidValue(form.methodName,"���Ʋ���Ϊ��"))
		return false;
	else
		return true;
}

function checkChannelForCom(form)
{
	if(!isNumeric(form.UserCommission,"��ɱ���������С��"))
		return false;
	else
	{
		if(!isCommission(form.UserCommission,form.commission))
			return confirm("'���۷ֳɱ���'����'������ɱ���'���Ƿ�Ҫ������");
			
		return true;
	}
}

function checkChannelForUser(form)
{
	if(!isNumeric(form.commission,"��ɱ���������С��"))
		return false;
	else
	{
		return confirm("���顮���۷ֳɱ��������Ƿ���ڡ�����ֳɱ��������Ƿ�Ҫ������");
	}
}

function checkSuperAdd(form)
{
	if(InValidValue(form.superName,"��¼�û�������Ϊ��")||
		InValidValue(form.superPass,"��¼���벻��Ϊ��"))
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
	return confirm("ȷ��֧���");	
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
	return confirm("�˲������ɻָ�,ȷ��Ҫɾ����?");	
}

function showHidden(id)
{
	var obj=document.getElementById("msg_"+id);	
	if(obj.style.display=="none")
		obj.style.display="block";
	else
		obj.style.display="none"
}

