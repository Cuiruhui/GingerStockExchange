<?php
class Session {
	var $xltm;
	function Session(&$xltm) {
		$this->xltm = &$xltm;
	}

	/**
	 * 清除任何过期 session
	 * 约2周
	 * @return 无输出,但发现错误则提示
	 */
	function clear_old_sessions() {
		$time_minus_defined = time() - $this->xltm->config['cookie_length'];
		$this->xltm->SQL->Execute("DELETE from `user_sessions` WHERE time<'{$time_minus_defined}' and type='{$this->xltm->user_type}'");
	}

	/**
	 * 生成一个新的session->id的唯一ID 
	 * @param string $password  - 用户密码
	 * @param string $user_code - user_code 激活码
	 * @return SHA1 字符串
	 */
	function generate_unique_id($password, $user_code) {
		$hash[] = md5(uniqid(rand(), true));
		$hash[] = sha1(uniqid(rand(), true)) . $hash[0] . md5($user_code);
		$hash[] = sha1($password . $hash[0] . $hash[1] . md5(sha1($user_code) . sha1($user_code))) . sha1($password);
		$hash[] = md5($hash[0] . $hash[1] . $hash[2]) . sha1($hash[0] . $hash[1] . $hash[0]);
		return sha1($hash[0] . $hash[1]. $hash[2] . $hash[3] . md5($user_code . $password));
	}

	/**
	 * 生成一个session value
	 * @param string  $password  - 用户密码
	 * @param string  $user_code - user->code
	 * @param string  $unique_id - 之前生成的session->id
	 * @param integer $time      - 有效时间
	 * @return SHA1 字符串
	 */
	function generate_unique_value($password, $user_code, $unique_id, $time) {
		$hash[] = sha1($unique_id . $password . $user_code) . sha1(time() * 2 + $time);
		$hash[] = md5($password . $user_code . $hash[0] . time() . $time);
		$hash[] = sha1($hash[0] . $hash[1] . $user_code . sha1($password));
		return sha1($hash[0] . $hash[1] . $hash[2] . sha1($password . $user_code . $time . $hash[1]));
	}

	/**
	 * 创建一个新的session会话
	 * @param    string  $username  - 用户名称
	 * @param    string  $password  - 用户密码
	 * @param    string  $user_code - 用户激活码
	 * @optional boolean $remember  - 是否使用COOKIES
	 * @return void but outputs error if found
	 */
	function create_session($username, $password, $user_code, $remember = false) {
		$time = time();
		// 获取用户ID
		$user_id=$this->xltm->SQL->GetRow("select id from `user_agent` where username='{$username}'");
		// 生成session的ID
		$unique_id = $this->generate_unique_id($password, $user_code);       
		// 生成session的value
		$value = $this->generate_unique_value($password, $user_code, $unique_id, $time);
		$sha1_time = sha1($time);
		// 插入新的session会话到 sessions 数据表
		$this->xltm->SQL->Execute("INSERT INTO `user_sessions` set id='{$unique_id}',value='{$value}',time='{$time}',type='{$this->xltm->user_type}'");
		// 更新用户表的最后登陆 last_session 与 last_login时间
		$this->xltm->SQL->Execute("UPDATE `user_agent` SET `last_session`='{$value}',last_login='".time()."' WHERE `username`='{$username}'");
		//永久写入
		$this->xltm->SQL->Execute('COMMIT');
		//如果选择使用cookie则设置cookie相关信息
		if ($remember === true) {
			setcookie($this->xltm->config['cookie_prefix'] . 'user_id', $user_id['id'], time() + $this->xltm->config['cookie_length'], $this->xltm->config['cookie_path'], $this->xltm->config['cookie_domain']);
			setcookie($this->xltm->config['cookie_prefix'] . 'user_time', sha1($time), time() + $this->xltm->config['cookie_length'], $this->xltm->config['cookie_path'], $this->xltm->config['cookie_domain']);
			setcookie($this->xltm->config['cookie_prefix'] . 'user_unique', $unique_id, time() + $this->xltm->config['cookie_length'], $this->xltm->config['cookie_path'], $this->xltm->config['cookie_domain']);
		}
		// 配置session信息
		$_SESSION[$this->xltm->config['cookie_prefix'] . 'user_id'] = $user_id['id'];
		$_SESSION[$this->xltm->config['cookie_prefix'] . 'user_time'] = sha1($time);
		$_SESSION[$this->xltm->config['cookie_prefix'] . 'user_unique'] = $unique_id;
	}

	/**
	 * 验证session是否有效
	 * @param array $information - 包含cookie信息
	 * @return true=通过, false=失败
	 */
	function fetch_session($information) {
		//根据session信息的ID得到用户表的last_session的值
		$user_session=$this->xltm->SQL->GetRow("select last_session from `user_agent` where id='{$information[0]}'");
		//根据用户的last_session值得到sessions表的信息
		$session_info=$this->xltm->SQL->GetRow("SELECT * FROM user_sessions WHERE id='{$information[2]}' and value='{$user_session['last_session']}' and type={$this->xltm->user_type}");
		//如果用户的session有效
		if ($session_info['id'] != '') {
			// session数据表的time与 session信息的time相等则判断为有效
			//if (sha1($session_info['time']) == $information[1]) {
				return true;
			//}
			//else {
			//	return false;
			//}
		}
		else {
			return false;
		}
	}

	/**
	 * 通过session获取用户user数据表的信息
	 * @param array $information - 包含cookie/session信息
	 * @return true=成功, false=失败
	 */
	function validate_session($information) {
		//$information必须为数组格式
		if (is_array($information)) {
			if (strlen($information[1]) == 40 //长度符合40
			&& strlen($information[2]) == 40  //长度符合40
			&& preg_match('/^[a-fA-F0-9]{40}$/', $information[1])
			&& preg_match('/^[a-fA-F0-9]{40}$/', $information[2])) {
				   // 验证session
					if ($this->fetch_session($information)) {
					// 获取用户users数据表的信息
					$row=$this->xltm->SQL->GetRow("select * from `user_agent` where id='{$information[0]}'");
					//循环$row设定用户资料到user_info[]
					foreach ($row as $key => $value) {
						$this->xltm->user_info[$key] = stripslashes($value);
					}
					// 更新这session时间
					$upTime=time();
					$this->xltm->SQL->Execute("update user_sessions set time='$upTime' where id='{$information[2]}' and type='{$this->xltm->user_type}'");
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	/**
	 * 获取session或cookie的信息并以数组格式输出到$information
	 * @return true=成功, false=失败
	 */
	function find_session() {
		/**
		 * 如果用户是使用了session?
		 * session为数字
		 */
		//如果存在session则获取
		if (isset($_SESSION[$this->xltm->config['cookie_prefix'] . 'user_id']) && is_numeric($_SESSION[$this->xltm->config['cookie_prefix'] . 'user_id'])) {
			$information = array(
			$_SESSION[$this->xltm->config['cookie_prefix'] . 'user_id'],
			$_SESSION[$this->xltm->config['cookie_prefix'] . 'user_time'],
			$_SESSION[$this->xltm->config['cookie_prefix'] . 'user_unique']
			);
			return $this->validate_session($information);
		}elseif (isset($_COOKIE[$this->xltm->config['cookie_prefix'] . 'user_id']) && is_numeric($_COOKIE[$this->xltm->config['cookie_prefix'] . 'user_id'])) {
			$information = array(
			$_COOKIE[$this->xltm->config['cookie_prefix'] . 'user_id'],
			$_COOKIE[$this->xltm->config['cookie_prefix'] . 'user_time'],
			$_COOKIE[$this->xltm->config['cookie_prefix'] . 'user_unique']
			);
			return $this->validate_session($information);
		}
		else {
			// 没有session和cookie则输出失败
			return $this->validate_session('');
		}
	}
}
?>