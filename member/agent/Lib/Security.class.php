<?php
/**
 * Contains all necessary security functions
 */
class Security {

	/**
 * @var object $xltm - Will contain everything else
 */
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

		// 如果开启所有的 ' (单引号), " (双引号), \ (反斜线) and 空字符会自动转为含有反斜线的溢出字符
		if (get_magic_quotes_gpc()) {
			// POST 方式
			foreach ($_POST as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $key2 => $value2) {
						if (is_array($value2)) {
							foreach ($value2 as $key3 => $value3) {
								if (is_array($value3)) {
									foreach ($value3 as $key4 => $value4) {
										// 不能去任何更深
										if (is_array($value4)) {
											$_POST[$key][$key2][$key3][$key4] = $value4;
										}
										else {
											$_POST[$key][$key2][$key3][$key4] = stripslashes($value4);
										}
									}
								}
								else {
									$_POST[$key][$key2][$key3] = stripslashes($value3);
								}
							}
						}
						else {
							$_POST[$key][$key2] = stripslashes($value2);
						}
					}
				}
				else {
					$_POST[$key] = stripslashes($value);
				}
			}

			// GET 方式
			foreach ($_GET as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $key2 => $value2) {
						if (is_array($value2)) {
							foreach ($value2 as $key3 => $value3) {
								if (is_array($value3)) {
									foreach ($value3 as $key4 => $value4) {
										// 不能去任何更深
										if (is_array($value4)) {
											$_GET[$key][$key2][$key3][$key4] = $value4;
										}
										else {
											$_GET[$key][$key2][$key3][$key4] = stripslashes($value4);
										}
									}
								}
								else {
									$_GET[$key][$key2][$key3] = stripslashes($value3);
								}
							}
						}
						else {
							$_GET[$key][$key2] = stripslashes($value2);
						}
					}
				}
				else {
					$_GET[$key] = stripslashes($value);
				}
			}

			// COOKIE 方式
			foreach ($_COOKIE as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $key2 => $value2) {
						if (is_array($value2)) {
							foreach ($value2 as $key3 => $value3) {
								if (is_array($value3)) {
									foreach ($value3 as $key4 => $value4) {
										// Can't go any deeper
										if (is_array($value4)) {
											$_COOKIE[$key][$key2][$key3][$key4] = $value4;
										}
										else {
											$_COOKIE[$key][$key2][$key3][$key4] = stripslashes($value4);
										}
									}
								}
								else {
									$_COOKIE[$key][$key2][$key3] = stripslashes($value3);
								}
							}
						}
						else {
							$_COOKIE[$key][$key2] = stripslashes($value2);
						}
					}
				}
				else {
					$_COOKIE[$key] = stripslashes($value);
				}
			}
		}
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
		$SQL="UPDATE `user_agent` set tries='0',last_try='".time()."' where last_try<'$time_minus_12_hours'";
		$this->xltm->SQL->Execute($SQL);
	}
}
?>