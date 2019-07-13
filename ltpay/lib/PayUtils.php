<?php
/* *
 * 功能：支付
 * 版本：1.0
 * 修改日期：2018-06-09
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */
require_once dirname ( __FILE__ ).'/BaseUtils.php';
class PayUtils {
    //版本
    public $version='1.1';

    //支付网关地址
    public $gateway_url = "https://pay.95588ok.com/api.php/webRequest/tradePay";

    //查询网关地址
    public $query_url = "https://pay.95588ok.com/api.php/webRequest/orderQuery";

    //异步回调
    public $notify_url="http://www.yataitz999.com/ltpay/notify.php";

    //同步返回
    public $return_url="http://www.yataitz999.com/ltpay/return.php";

	//编码格式
	public $charset = "UTF-8";

	//签名方式 
    public $sign_type = "MD5";

    //商户id
    public $appid='80062708';
    //商户密匙
    public $appsecret='5c45dffb3f1b5d7a78706f7f791ebfd9';

    //参与加密的字段
    public $sign_field=array(
        'pay'=>array('version','appid','out_trade_no','total_fee','pay_id','return_url','notify_url','bank_code'),
        'pay_return'=>array('version','appid','resp_code','resp_desc','tran_amount','out_trade_no','pay_no','pay_id','amount','payment'),
        'query'=>array('version','sign_type','appid','out_trade_no'),
        'query_return'=>array('version','appid','out_trade_no'),
    );
    public function __construct()
	{
        $this->return_url='http://'.$_SERVER['HTTP_HOST'].$this->return_url;
	}
    public function addLog($str) {
        if ( is_array($str) || is_object($str) ) {
            $str = var_export($str, true);
        }
        $str = date('Y-m-d H:i:s') . "\t" . $str . "\r\n";
        $r = @file_put_contents(dirname ( __FILE__ ).'log.txt', $str, FILE_APPEND);
        if ( !$r ) {
            @unlink('log.txt');
            @file_put_contents(dirname ( __FILE__ ).'log.txt', $str);
        }
    }
    /**
     * 支付提交
     * @param $tranCode
     * @param $params
     * @return string
     * @throws Exception
     */
    public function paySubmit($data){
        //业务参数
        $business['version']=$this->version;
        $business['appid']=$this->appid;
        $business['out_trade_no']=$data['out_trade_no'];
        $business['total_fee']=$data['total_fee'];
        $business['pay_id']=$data['pay_id'];
        $business['bank_code']=$data['bank_code'];
        $business['return_url']=$this->return_url;
        $business['notify_url']=$this->notify_url;
        $business['sign']=$this->makeRequestSign($business,'pay');
        if($data['pay_id'] == 'GATEWAY_PAY'){
           echo BaseUtils::buildForm($this->gateway_url,$business);
        }else{
            $result=BaseUtils::buildCurl($business,$this->gateway_url);
            return json_decode($result,true);
        }
    }
    /**
     * 支付提交
     * @param $tranCode
     * @param $params
     * @return string
     * @throws Exception
     */
    public function payQuery($data){
        //业务参数
        $business['version']=$this->version;
        $business['sign_type']=$this->sign_type;
        $business['appid']=$this->appid;     
        $business['out_trade_no']=$data['out_trade_no'];
        $business['sign']=$this->makeRequestSign($business,'query');
        return $result=BaseUtils::buildCurl($business,$this->query_url);
    }
    /**
     * 生成sign字符串
     */
    public function makeRequestSign($data = array(),$type='pay') {
        $raw_arr = array();
        foreach($this->sign_field[$type] as $k=>$v){
            $raw_arr[$v]=$data[$v];  
        }
        $sign=BaseUtils::createMd5Sign($raw_arr,$this->appsecret);
        return $sign;
    }
     /**
     * 验证回调sign字符串-用于与通知中的签名比较验证是否通过
     */
    public function makeNotifySign($data=  array(),$type='pay') {
        $raw_arr = array();
        foreach($this->sign_field[$type] as $k=>$v){
            $raw_arr[$v]=$data[$v]; 
        }
        $status=BaseUtils::verifyMd5Sign($raw_arr,$data['sign'],$this->appsecret);  
        return $status;
    }
    
    
    
    
	

}


