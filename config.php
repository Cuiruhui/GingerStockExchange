<?php 
function ka_config($i){   
   	$result=mysql_query("Select ID,webname,weburl,tm,tmdx,tmps,zm,zmdx,ggpz,sanimal,affice,fenb,haffice2,a1,a2,a3,a10,opwww,a4,a5,flash,a6,a7,a8,a9,a12,a13,a14,a15,a16,a17,a18,a19,a20,a21 From config Order By ID Desc"); 
	$ka_config1=mysql_fetch_array($result); 
		
	return $ka_config1[$i];
}
function ka_memuser(){   
  	$result=mysql_query("select money from user_users where  username='".$_SESSION['username']."' "); 
	$ka_guanuser1=mysql_fetch_array($result); 
	return $ka_guanuser1['money']/10;
}
?>