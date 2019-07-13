<?php
class Curl_HTTP_Client
{
	var $ch ;
	var $debug = true;
	var $error_msg;
	var $error_no="";
	function Curl_HTTP_Client($debug = false)
	{
		$this->debug = $debug;
		$this->init();
	}
	function init()
	{
		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_FAILONERROR, true);
		curl_setopt($this->ch,CURLOPT_ENCODING , 'gzip, deflate');
		
		// do not veryfy ssl
		// this is important for windows
		// as well for being able to access pages with non valid cert
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
	}
	function set_credentials($username,$password)
	{
		curl_setopt($this->ch, CURLOPT_USERPWD, "$username:$password");
	}
	function set_referrer($referrer_url)
	{
		curl_setopt($this->ch, CURLOPT_REFERER, $referrer_url);
	}
	function set_user_agent($useragent)
	{
		curl_setopt($this->ch, CURLOPT_USERAGENT, $useragent);
	}
	function include_response_headers($value)
	{
		curl_setopt($this->ch, CURLOPT_HEADER, $value);
	}
	function set_proxy($proxy)
	{
		curl_setopt($this->ch, CURLOPT_PROXY, $proxy);
	}
	function send_post_data($url, $postdata, $ip=null, $timeout=10)
	{
		curl_setopt($this->ch, CURLOPT_URL,$url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,true);
		//ï¿½ó¶¨¹Ì¶ï¿½IP
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			curl_setopt($this->ch,CURLOPT_INTERFACE,$ip);
		}
		//ï¿½ï¿½ï¿½ï¿½curlï¿½ï¿½ï¿½ï¿½Ö´ï¿½Ðµï¿½ï¿½î³¤ï¿½ï¿½ï¿½ï¿½ $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
		//ï¿½ï¿½ï¿½ï¿½Ê±ï¿½á·¢ï¿½ï¿½Ò»ï¿½ï¿½ï¿½ï¿½POSTï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Îªï¿½ï¿½application/x-www-form-urlencodedï¿½ï¿½ï¿½ï¿½ï¿½ï¿½?ï¿½á½»ï¿½ï¿½Ò»ï¿½ï¿½
		curl_setopt($this->ch, CURLOPT_POST, true);
		//generate post string
		$post_array = array();
		if(is_array($postdata))
		{
			foreach($postdata as $key=>$value)
			{
				//$post_array[] = urlencode($key) . "=" . urlencode($value);
				$post_array[] = $key . "=" . $value;
			}

			$post_string = implode("&",$post_array);

			if($this->debug)
			{
				echo "Url: $url\nPost String: $post_string\n";
			}
		}
		else
		{
			$post_string = $postdata;
		}

		//ï¿½ï¿½HTTPï¿½ÐµÄ¡ï¿½POSTï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Òªï¿½ï¿½ï¿½ï¿½Ò»ï¿½ï¿½ï¿½Ä¼ï¿½ï¿½ï¿½ï¿½ï¿½ÒªÒ»ï¿½ï¿½@ï¿½ï¿½Í·ï¿½ï¿½ï¿½Ä¼ï¿½ï¿½ï¿½
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_string);


		//Ö´ï¿½ï¿½Ò»ï¿½ï¿½curlï¿½á»°
		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error number: " .curl_errno($this->ch) ."\n";
				echo "Error message: " .curl_error($this->ch)."\n";
			}

			return false;
		}
		else
		{
			return $result;
		}
	}
	function fetch_url($url, $ip=null, $timeout=5)
	{
		curl_setopt($this->ch, CURLOPT_URL,$url);

		curl_setopt($this->ch, CURLOPT_HTTPGET,true);

		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,true);

		//bind to specific ip address if it is sent trough arguments
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			curl_setopt($this->ch,CURLOPT_INTERFACE,$ip);
		}

		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);

		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error number: " .curl_errno($this->ch) ."\n";
				echo "Error message: " .curl_error($this->ch)."\n";
			}

			return false;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * Fetch data from target URL
	 * and store it directly to file	 	 
	 * @param string url	 
	 * @param resource value stream resource(ie. fopen)
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 5)
	 * @return boolean true on success false othervise
	 * @access public
	 */
	function fetch_into_file($url, $fp, $ip=null, $timeout=5)
	{
		//ï¿½ï¿½Òªï¿½ï¿½È¡ï¿½ï¿½URLï¿½ï¿½Ö·
		curl_setopt($this->ch, CURLOPT_URL,$url);

		//ï¿½ï¿½ï¿½ï¿½Ê±ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½HTTPï¿½ï¿½methodÎªGETï¿½ï¿½ï¿½ï¿½ÎªGETï¿½ï¿½Ä¬ï¿½ï¿½ï¿½Ç£ï¿½ï¿½ï¿½ï¿½ï¿½Ö»ï¿½Ú±ï¿½ï¿½Þ¸Äµï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ê¹ï¿½ï¿½
		curl_setopt($this->ch, CURLOPT_HTTPGET, true);

		//ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ä¼ï¿½ï¿½ï¿½Î»ï¿½Ã£ï¿½Öµï¿½ï¿½Ò»ï¿½ï¿½ï¿½ï¿½Ô´ï¿½ï¿½ï¿½Í£ï¿½Ä¬ï¿½ï¿½ÎªSTDOUT (ï¿½ï¿½ï¿½ï¿½ï¿½)ï¿½ï¿½
		curl_setopt($this->ch, CURLOPT_FILE, $fp);

		//bind to specific ip address if it is sent trough arguments
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			//ï¿½ï¿½ï¿½â²¿ï¿½ï¿½ï¿½ï¿½Ó¿ï¿½ï¿½ï¿½Ê¹ï¿½Ãµï¿½ï¿½ï¿½Æ£ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½Ò»ï¿½ï¿½Ó¿ï¿½ï¿½ï¿½IPï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½ï¿½
			curl_setopt($this->ch, CURLOPT_INTERFACE, $ip);
		}

		//ï¿½ï¿½ï¿½ï¿½curlï¿½ï¿½ï¿½ï¿½Ö´ï¿½Ðµï¿½ï¿½î³¤ï¿½ï¿½ï¿½ï¿½ $timeout
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);

		//Ö´ï¿½ï¿½Ò»ï¿½ï¿½curlï¿½á»°
		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error number: " .curl_errno($this->ch) ."\n";
				echo "Error message: " .curl_error($this->ch)."\n";
			}

			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Send multipart post data to the target URL	 
	 * return data returned from url or false if error occured
	 * (contribution by vule nikolic, vule@dinke.net)
	 * @param string url
	 * @param array assoc post data array ie. $foo['post_var_name'] = $value
	 * @param array assoc $file_field_array, contains file_field name = value - path pairs
	 * @param string ip address to bind (default null)
	 * @param int timeout in sec for complete curl operation (default 30 sec)
	 * @return string data
	 * @access public
	 */
	function send_multipart_post_data($url, $postdata, $file_field_array=array(), $ip=null, $timeout=30)
	{
		curl_setopt($this->ch, CURLOPT_URL, $url);

		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

		//bind to specific ip address if it is sent trough arguments
		if($ip)
		{
			if($this->debug)
			{
				echo "Binding to ip $ip\n";
			}
			curl_setopt($this->ch,CURLOPT_INTERFACE,$ip);
		}

		curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);

		curl_setopt($this->ch, CURLOPT_POST, true);

		// disable Expect header
		$headers = array("Expect: ");
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);

		// initialize result post array
		$result_post = array();

		//generate post string
		$post_array = array();
		$post_string_array = array();
		if(!is_array($postdata))
		{
			return false;
		}

		foreach($postdata as $key=>$value)
		{
			$post_array[$key] = $value;
			$post_string_array[] = urlencode($key)."=".urlencode($value);
		}

		$post_string = implode("&",$post_string_array);


		if($this->debug)
		{
			echo "Post String: $post_string\n";
		}

		// set post string
		//curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_string);


		// set multipart form data - file array field-value pairs
		if(!empty($file_field_array))
		{
			foreach($file_field_array as $var_name => $var_value)
			{
				if(strpos(PHP_OS, "WIN") !== false) $var_value = str_replace("/", "\\", $var_value); // win hack
				$file_field_array[$var_name] = "@".$var_value;
			}
		}

		$result_post = array_merge($post_array, $file_field_array);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $result_post);


		$result = curl_exec($this->ch);

		if(curl_errno($this->ch))
		{
			if($this->debug)
			{
				echo "Error Occured in Curl\n";
				echo "Error: " .curl_errno($this->ch) ."\n";
				echo "Message: " .curl_error($this->ch)."\n";
			}

			return false;
		}
		else
		{
			return $result;
		}
	}

	/**
	 * Set file location where cookie data will be stored and send on each new request
	 * @param string absolute path to cookie file (must be in writable dir)
	 * @access public
	 */
	function store_cookies($cookie_file)
	{
		curl_setopt ($this->ch, CURLOPT_COOKIEJAR, $cookie_file);
		curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $cookie_file);
	}

	/**
	 * Set custom cookie
	 * @param string cookie
	 * @access public
	 */
	function set_cookie($cookie)
	{
		curl_setopt ($this->ch, CURLOPT_COOKIE, $cookie);
	}

	/**
	 * Get last URL info 
	 * usefull when original url was redirected to other location	
	 * @access public
	 * @return string url
	 */
	function get_effective_url()
	{
		return curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
	}

	/**
	 * Get http response code	 
	 * @access public
	 * @return int
	 */
	function get_http_response_code()
	{
		return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
	}

	/**
	 * Return last error message and error number
	 * @return string error msg
	 * @access public
	 */
	function get_error_msg()
	{
		//$this->error_no=curl_errno($this->ch);
		echo "Error: " .curl_errno($this->ch) ."\n";
		echo "Message3: " .curl_error($this->ch)."\n";

		return $err;
	}
	function highlightHtml($code,$line_number=false)
	{
		//$code=$this->results;
		$code = htmlspecialchars($code);
		//$code  =  preg_replace_callback('/&lt;([a-zA-Z0-9]+)(.*?)(\/?&gt;)/',array('WebLoad','__pv'), $code);

		$code  =  preg_replace('/(&lt;[a-zA-Z0-9]+)/',  '<font color="#0000FF">$1</font>',  $code);
		$code  =  preg_replace('/(&lt;\/[a-zA-Z0-9]+&gt;)/',  '<font color="#0000FF">$1</font>',  $code);
		$code  =  preg_replace('/(\/&gt;)/',  '<font color="#0000FF">${1}</font>',  $code);
		//$code = preg_replace('/(&lt;\/?[a-zA-Z]+&nbsp;.*?&gt;)/','<font color="#0000FF">${1}</font>', $code);

		$code = preg_replace('/&lt;!DOCTYPE\s+.+?&gt;/','<font color="#3300FF">${0}</font>',$code);
		//×¢ï¿½ï¿½
		$code  =  str_replace('&lt;!--',  '<font color="#666666"><em>&lt;!--',  $code);
		$code  =  str_replace('--&gt;',  '--&gt;</em></font>',  $code);

		//block : begin|end
		$code  =  preg_replace('/(&lt;!--\s*)(begin|end)(\s+)([a-z_\x7f-\xfe]+)/i','${1}<font size="" color="#0000FF"><b>${2}</b></font>${3}<b><font color="#FF0000">${4}</font></b>',  $code);
		$code  =  preg_replace('/(\$[a-z0-9_]+)\s*=\s*(per|on)\(([0-9]+),(\'.*?\'),(\'.*?\')\)/i','<font color="#009900"><b>${1}</b></font>=<font color="#0000FF">${2}</font>(${3},<font color="#FF9999">${4}</font>,<font color="#FF9999">${5}</font>)',  $code);
		//vip	: vip|endvip
		$code  =  preg_replace('/<font color="#666666"><em>&lt;!--\s*vip/i',  '<span style="display:block;border:1px dashed #696969;padding 3px" >${0}',  $code);
		$code  =  preg_replace('/&lt;!--\s*endvip\s*--&gt;<\/em><\/font>/i',  '${0}</span>',  $code);
		//ssi   : #include
		$code  =  preg_replace('/&lt;!--\s*#include\s+file.+?--&gt;/i','<span style="background-color:#FFFF66; font-weight:bold; font-style:normal;padding:3px">${0}</span>',$code);
		$code = preg_replace('/(\{\$[a-zA-Z0-9_\x7f-\xfe]+\})/','<font style="background-color:#D7FED1;padding:1px" color="#009900">${1}</font>', $code);
		$code = preg_replace('/(\{\$[a-zA-Z0-9_\x7f-\xfe]+;)([a-zA-Z]+)=\'([^\']+?)\'\}/','<font style="background-color:#D7FED1;padding:1px" color="#009900">${1}<font color="#CC0000">${2}</font>=<font color="#FF33CC">\'${3}\'</font>}</font>', $code);

		//$code  =  preg_replace_callback('/>[^<]+?</',array('WebLoad','__htmlspace'), $code);
		//$code = nl2br($code);
		if(! $line_number){
			return '<PRE>'.$code.'</PRE>';
		}else{
			$code = '<pre><ol><li>' . str_replace("\n",'</li><li>',$code) . '</li></ol></pre>';
			return $code;
		}
	}
	function close()
	{
		curl_close($this->ch);
	}
}
?>