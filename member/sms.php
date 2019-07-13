<?php
session_start();
header("content-type:text/html; charset=utf-8");
require_once(dirname(__FILE__)."/common.php");
require_once(ROOT."/Lib/xltm.class.php");
require_once(ROOT."/Lib/my.function.php");
//生成验证码
$_SESSION["duanxinyanzhengma"]= rand(100000,999999);;
$type = isset($_GET['type'])?$_GET['type']:'';

if($type=='reg'){
		//获得手机号码
		$mobile = $_POST['mobile'];
		if(preg_match("/^1[3-5,8]{1}[0-9]{9}$/",$mobile)){ //号码正确
			//查询号码是否有注册过。
			if(!is_array($xltm->SQL->GetRow("select * from `user_users` where mobile='{$mobile}'")))
			{
				//号码可以注册
				$msg = "您的验证码是：{$_SESSION['duanxinyanzhengma']}。请不要把验证码泄露给其他人。如非本人操作，可不用理会！";
				$smsres=sendmsg($mobile,$msg);
				//$smsres="提交成功";
				if($smsres=="提交成功"){
						echo "ok";
					}else{
						echo $smsres;
					}
			}
			else
			{
				//号码存在
				exit('手机号码已注册');
			}
			
		}else{
			//号码格式错误
			echo "请输入正确的手机号码";
		}
	}



?>
