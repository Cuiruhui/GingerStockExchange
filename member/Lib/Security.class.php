<?php
class Security {
	var $xltm;

	/**
	 * Construct class and clean input
	 * @param object $xltm - Contains all other classes
	 * @return void
	 */
	function Security(&$xltm) {
		// 检查 register_globals
		if (@ini_get('register_globals') == 1 || strtoupper(@ini_get('register_globals')) == 'ON') {
			die('register_globals 必须关闭才能正常使用该系统');
		}
		$this->xltm = &$xltm;
		$this->_CheckRequest($_REQUEST);
		//检查参数
		foreach($_REQUEST as $_k => $_v)
		{
			${$_k} = $this->_RunMagicQuotes($_v);
		}
	}
	
	function _RunMagicQuotes(&$svar)
	{
		if(!get_magic_quotes_gpc())
		{
			
			if( is_array($svar) )
			{
				foreach($svar as $_k => $_v) $svar[$_k] = _RunMagicQuotes($_v);
			}
			else
			{
				//过滤危险字符
				$svar = str_replace("'","",$svar);
				$svar = str_replace("%","",$svar);
				$svar = str_replace("_","",$svar);
				$svar = addslashes($svar);
			}
		}
		//判断是否存在危险字符
		if(eregi('select|insert|update|delete|union|into|load_file|outfile', $svar))
		{
			die("<p align=center><span style='font-size:14px;'>对不起，您输入的参数含有系统感字符！请把以下标红信息复制给我们的管理员：<br /><font color=red>{$svar}</font></span>");
		}  
		return $svar;
	}
	
	//检查和注册外部提交的变量 
	function _CheckRequest(&$val)
	{ 
		if (is_array($val)) { 
			foreach ($val as $_k=>$_v) 
			{ 
				$this->_CheckRequest($_k); 
				$this->_CheckRequest($val[$_k]); 
			} 
		}
		else 
		{ 
			if( strlen($val)>0 && preg_match('#^(cfg_|GLOBALS)#',$val) ) 
			{ 
				exit('Request var not allow!'); 
			} 
		} 
	} 
	
	function is_stockcode($code)
	{
		$result = false;
		if(preg_match('/^[sz|sh]{0,}(0|6|3)\d{5}$/i',$code))
		{
			$result = true;
		}
		return $result;
	}
	

	/**
	 * 过滤插入数据库信息
	 * @param    mixed $input - Can be an array of values or just a string
	 * @optional bool  $html  - Whether or not to use htmlentities()
	 * @return string of cleaned input
	 */
	function make_safe($input, $html = true) {
		if (is_array($input)) {
			/**
			 * Loops to a depth of 3, and it will add slashes to each of
			 * the $value{num} at each depth. If the htmlentities() is chosen
			 * via the $html variable, it will be used.
			 */
			foreach ($input as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $key2 => $value2) {
						if (is_array($value2)) {
							foreach ($value2 as $key3 => $value3) {
								if (is_array($value3)) {
									foreach ($value3 as $key4 => $value4) {
										if ($html === false) {
											$input[$key][$key2][$key3][$key4] = addslashes($value4);
										}
										else {
											$input[$key][$key2][$key3][$key4] = htmlentities($value4, ENT_QUOTES);
										}
									}
								}
								else {
									if ($html === false) {
										$input[$key][$key2][$key3] = addslashes($value3);
									}
									else {
										$input[$key][$key2][$key3] = htmlentities($value3, ENT_QUOTES);
									}
								}
							}
						}
						else {
							if ($html === false) {
								$input[$key][$key2] = addslashes($value2);
							}
							else {
								$input[$key][$key2] = htmlentities($value2, ENT_QUOTES);
							}
						}
					}
				}
				else {
					if ($html === false) {
						$input[$key] = addslashes($value);
					}
					else {
						$input[$key] = htmlentities($value, ENT_QUOTES);
					}
				}
			}

			return $input;
		}
		else {
			if ($html === false) {
				return addslashes($input);
			}
			else {
				return htmlentities($input, ENT_QUOTES);
			}
		}
	}
	/**
	 * 删除任何过时登陆数据和游客数据
	 * @return void but outputs error if found
	 */
	function remove_old_tries() {
		$time_minus_12_hours = time() - ((60 * 60) * 12);
		$SQL="UPDATE user_users set tries='0',last_try='".time()."' where last_try<'$time_minus_12_hours'";
		$this->xltm->SQL->Execute($SQL);
	}
}
?>