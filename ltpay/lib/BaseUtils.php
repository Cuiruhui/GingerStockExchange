<?php
class BaseUtils
{  
    /**
     * @param $data
     * @param $params
     * @return 返回签名明文串
     * @throws Exception
     */
    private static function getStringData($data) {
        ksort($data);
        $fieldString = "";
        foreach($data as $key=>$value){
            $fieldString []= $key . "=" . $value . "";
        }
        $fieldString=implode('&',$fieldString);
        return $fieldString;
    }
    /**
     * 生成MD5签名
     * @param array $raw_arr
     * @param string $signType
     * @return string
     */
    public  static function createMd5Sign($raw_arr, $appsecret) {
        $str=self::getStringData($raw_arr);
        $sign=md5($str.$appsecret);
        return $sign;
    }
    /**
     * 生成Rsa签名
     * @param array $raw_arr
     * @param string $signType
     * @return string
     */
    public  static function createRsaSign($raw_arr, $cerFile, $signType = "RSA") {
        $str=self::getStringData($raw_arr);
        $path = dirname(__FILE__);
        $priKey = file_get_contents($path.$cerFile);
        //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_get_privatekey($priKey);
        //调用openssl内置签名方法，生成签名$sign
        openssl_sign($str, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }
    /**Md5验证签名
    * @param $raw_arr 要验证的数据
    * @param $sign是需要验证的签名数据
    * return 验签是否通过，为BOOL值
    */
    public static function verifyMd5Sign($raw_arr, $sign, $appsecret)  {
        $_sign=self::createMd5Sign($raw_arr,$appsecret);
        if($_sign==$sign){
            return true;
        }else{
            return false;
        }
    }
    /**RSA验证签名
    * $data为要验证的数据字符串
    * $sign是需要验证的签名数据，是直接从URL中取到的$_POST["sign"]型参数，函数里面会进行base64_decode的。
    * return 验签是否通过，为BOOL值
    */
    public static function verifyRsaSign($raw_arr, $sign, $cerFile)  {
        $str=self::getStringData($raw_arr);
        //读取公钥文件,也就是签名方公开的公钥，用来验证这个data是否真的是签名方发出的
        $path = dirname(__FILE__);
        $pubKey = file_get_contents($path.$cerFile);
        $res = openssl_get_publickey($pubKey);
        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($str, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    
    }
    /**
     * 生成支付提交表单
     *
     * @access protected
     * @param string $link  请求url
     * @param array $postData  请求数据
     * @param string $charset  字符编码格式
     * @param string $method   提交方式
     * @return string
     */
    public static function buildForm($url, $postData,$charset='utf-8', $method='post') {
        header("Content-type:text/html; charset={$charset}");
        $htmlArr = array();
        $htmlArr[] = '<form id="applyForm" target="_self"  name="form1" action="' . $url . '" method="' . $method . '">';
        foreach ($postData as $key => $val) {
			$htmlArr[] = '    <input type="hidden" name="' . $key . '" value="' . $val . '">';
        }
        $htmlArr[] = '</form>';
        $htmlArr[] = '<script>window.parent.document.forms["form1"].submit();</script>';
        return implode('', $htmlArr);
    }
    public static function buildCurl($PostArry, $request_url)
    {
        $postData = $PostArry;
        $postDataString = http_build_query($postData); // 格式化参数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $request_url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postDataString); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 60); // 设置超时限制防止死循环返回
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            $tmpInfo = curl_error($curl); // 捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo; // 返回数据
    }
    /**
     * @desc 获取IP
     */
    public static function getServerAddress() {
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        $ip=($ip ? $ip : $_SERVER['REMOTE_ADDR']);
        return $ip;
    }
}  