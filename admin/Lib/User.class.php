<?php
//加密方式：phpjm加密，代码还原率100%。
//此程序由【找源码】http://Www.ZhaoYuanMa.Com (VIP会员功能）在线逆向还原，QQ：7530782 
?>
<?php
class User {
	var $xltm;
	function User(&$xltm) {
		$this->xltm = &$xltm;
	}
	/**
	 * This will generate a random code
	 * @return string of SHA1 hash
	 */
	function generate_random_code() {
		$hash[] = sha1(sha1(rand(1, 100)) . md5(rand(1, 100)));
		$hash[] = sha1(time() + time() . md5(time() + time()) . md5(rand()));
		$hash[] = sha1($hash[0] . $hash[1] . md5(time()));
		$hash[] = sha1($this->xltm->config['user_regex'] . time());
		return sha1($hash[0] . $hash[0] . $hash[1] . $hash[2] . $hash[3] . time() . time() + time());
	}

	/**
	 * Validates a password
	 * @param string $input - The input password
	 * @return true if valid, false if not
	 */
	function validate_password($input) {
		if (strlen($input) <= $this->xltm->config['max_password'] &&
		strlen($input) >= $this->xltm->config['min_username']) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * 验证用户根据定义的正则表达式的字符串.
	 * @param string $input - The input username
	 * @return true if valid, false if not
	 */
	function validate_username($input) {
		if (preg_match($this->xltm->config['user_regex'], $input)) {
			return true;
		}else {
			return false;
		}
	}

	/**
	 * 检查用户是否已经登陆
	 * @return true on success, false on failure
	 */
	function validate_login() {
		//echo 'validate_login';
		if ($this->xltm->Session->find_session()) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * 获取用户信息
	 * @param string $username - The username
	 * @return array containing info on success, false on failure
	 */
	function fetch_user_info($username) {
		if ($this->validate_username($username)) {
			$row=$this->xltm->SQL->GetRow("select * from user_admin where username='{$username}'");
			return $row;
		}
		else {
			return false;
		}
	}

	/**
	 * 登陆失败,尝试加1
	 * @param string  $username      - The username
	 * @param integer $current_tries - The user's current tries
	 * @return void but will output error if found
	 */
	function update_tries($username, $current_tries) {
		if ($this->validate_username($username)) {
			$this->xltm->SQL->Execute("update user_admin set tries='".($current_tries + 1)."',last_try='".time()."' where username='{$username}'");
		}
	}

	/**
	 * Generates the password hash
	 * @param string $password  - The user's password
	 * @param string $user_code - The user's activation code
	 * @return SHA1 string
	 */
	function generate_password_hash($password, $user_code) {
		$hash[] = md5($password);
		$hash[] = md5($password . $user_code);
		$hash[] = md5($password) . sha1($user_code . $password) . md5(md5($password));
		$hash[] = sha1($password . $user_code . $password);
		$hash[] = md5($hash[3] . $hash[0] . $hash[1] . $hash[2] . sha1($hash[3] . $hash[2]));
		$hash[] = sha1($hash[0] . $hash[1] . $hash[2] . $hash[3]) . md5($hash[4] . $hash[4]) . sha1($user_code);
		return sha1($hash[0] . $hash[1] . $hash[2] . $hash[3] . $hash[4] . $hash[5] . md5($user_code));
	}

	/**
	 * 核对输入的密码
	 * @param string $input_password - The input password
	 * @param string $real_password  - The user's real password
	 * @param string $user_code      - The user's activation code
	 * @return true if they match, false if not
	 */
	function compare_passwords($input_password, $real_password, $user_code) {
		// 生成hash
		$input_hash = $this->generate_password_hash($input_password, $user_code);
		// 进行比较
		if ($input_hash == $real_password) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * 尝试登陆用户
	 * @return true on success, false on failure
	 */
	function login_user() {
		$username = $_POST['username'];//会员账号
		$password = $_POST['password'];//会员密码
		$remember = $_POST['remember'];//是否使用cookie
		//通过会员账号得到会员user数据表的信息
		$user_info = $this->fetch_user_info($username);
		//如果会员账号存在users数据表则实行以下操作
		if ($user_info['id'] != '') {
			//尝试登陆不能大于限制
			if ($user_info['tries'] < $this->xltm->config['max_tries']) {
				//核对输入的会员密码与users的password通过
				if ($this->compare_passwords($password, $user_info['password'], $user_info['code'])) {
					//账号没有被锁定或停用
					if ($user_info['blocked'] == 'no') {
						// 账号必须为激活
						if ($user_info['active'] == 'yes') {
							$this->xltm->SQL->Execute("UPDATE user_admin set tries='0' where username='{$username}'");
							//选择了使用cookie 创建一个新的session会话
							if ($remember == '1') {
								$this->xltm->Session->create_session($username, $password, $user_info['password'], true);
							}else {
								$this->xltm->Session->create_session($username, $password, $user_info['password'], false);
							}
							return true;
						}else {
							$this->xltm->gourl('对不起,你的账号已经被停用请与管理员联系!!','');
							return false;
						}
					}else {
						//账号被锁定或停用,尝试加1，返回错误提示
						$this->update_tries($username, $user_info['tries']);
						$this->xltm->gourl('对不起,你已经被禁止访问本网站','');
						return false;
					}
				}else {
					//输入的密码与users数据表不相符,尝试加1,返回错误提示
					$this->update_tries($username, $user_info['tries']);
					$this->xltm->gourl('密码错误','');
					return false;
				}
			}else {
				$this->xltm->SQL->Execute("UPDATE user_admin set blocked='yes' where username='{$username}'");
				$this->xltm->gourl('你尝试登陆已经超出'.$this->xltm->config['max_tries'].'次限制,账号已经被锁定,请与管理员联系!!','');
				return false;
			}
		}else {
			//users不存在这账号
			$this->xltm->gourl('请输入正确的账号!!','');
			return false;
		}
	}
	/**
	 * 会员退出登陆,清除session和cookie
	 * @returns void but will redirect or output an error
	 */
	function logout_user() {
		$session_names = array('user_id', 'user_time', 'user_unique');
		if (isset($_SESSION[$this->xltm->config['cookie_prefix'] . 'user_unique'])) {
			$this->xltm->SQL->Execute("DELETE from `user_sessions` WHERE id='{$_SESSION[$this->xltm->config['cookie_prefix'] . 'user_unique']}' and type=5");
		}
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time() - 42000, '/');
		}

		if (isset($_COOKIE[$this->xltm->config['cookie_prefix'] . 'user_id'])) {
			foreach ($session_names as $value) {
				setcookie($this->xltm->config['cookie_prefix'] . $value, 0, time() - 3600, $this->xltm->config['cookie_path'], $this->xltm->config['cookie_domain']);
			}
		}
		$this->xltm->gourl('返回登录界面.','login.php');
	}
	/**
	 * Generates a new activation code
	 * @param string $username - The user's username
	 * @param string $password - The user's password
	 * @param string $email    - The user's email address
	 * @return SHA1 string
	 */
	function generate_activation_code($username, $password, $email) {
		$hash[] = md5($username . $password . md5($email));
		$hash[] = sha1($hash[0] . $hash[0]) . md5(sha1(sha1($email) . sha1($password)) . md5($username));
		$hash[] = sha1(sha1(sha1(sha1(md5(md5('   	') . sha1(' 	'))) . sha1($password . $username))));
		$hash[] = sha1($hash[0] . $hash[1] . $hash[2]) . sha1($hash[2] . $hash[0] . $hash[1]);
		$hash[] = sha1($username);
		$hash[] = sha1($password);
		$hash[] = md5(md5($email) . md5($password));
		$hash_count = count($hash);
		for ($x = 0; $x < $hash_count; $x++) {
			$random_hash = rand(0, $hash_count);
			$hash[] = sha1($hash[$x]) . sha1($password) . sha1($hash[$random_hash] . $username);
		}

		return sha1(sha1($hash[0] . $hash[1] . $hash[2] . $hash[3]) . sha1($hash[4] . $hash[5]) . md5($hash[6] . $hash[7] . $hash[8] . sha1($hash[9])) . $password . $email);
	}

	/*
	* 收藏自选股
	*
	*/
	function addFree($code,$type)
	{
		$this->xltm->user_log(9,"新增:".$code." 到自选股".$type);
		$code=substr($code,3);
		$addtime=date("Y-m-d H:i:s",time());
		if($stock=$this->xltm->SQL->GetRow("SELECT * FROM stock_code WHERE code='{$code}'")){
			if($this->xltm->SQL->GetRow("SELECT * FROM user_stock WHERE user='{$this->xltm->user_info['username']}' and freetype='{$type}' and stock_code='{$code}'")){
				return 3;
			}else {
				$this->xltm->SQL->Execute("insert into user_stock set user='{$this->xltm->user_info['username']}',freetype='{$type}',type='{$stock['type']}',stock_code='{$code}',stock_name='{$stock['name']}',add_time='{$addtime}'");
				return 1;
			}
		}else {
			return 2;
		}
	}
	function DelFree($code,$type)
	{
		$this->xltm->user_log(9,"从自选股".$type."删除:".$code);
		$code=substr($code,3);
		echo "DELETE from `user_stock` WHERE user='{$this->xltm->user_info['username']}' and freetype='{$type}' and stock_code='{$code}'";
		$this->xltm->SQL->Execute("DELETE from `user_stock` WHERE user='{$this->xltm->user_info['username']}' and freetype='{$type}' and stock_code='{$code}'");
		return 1;
	}
	
	function user_cost($user)
	{
		$query_member="user_users.cost_bull as member_bull,user_users.cost_sell as member_sell,user_users.cost_save as member_save";
		$query_agent="user_agent.cost_bull as agent_bull,user_agent.cost_sell as agent_sell,user_agent.cost_save as agent_save,user_agent.cost_ret as agent_ret";
		$query="select {$query_member},{$query_agent} from user_users,user_agent where user_users.username='$user' and user_agent.username=user_users.agent";
		$costArr=$this->xltm->SQL->GetRow($query);
		if($costArr)
			return $costArr;
		else
			return false;
	}
}
?>