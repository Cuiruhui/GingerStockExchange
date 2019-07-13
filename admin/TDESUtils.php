<?php
class CryptDes {
                     
     function encrypt($input, $key){
         $size = mcrypt_get_block_size(MCRYPT_TRIPLEDES,MCRYPT_MODE_ECB);
         $input = $this->pkcs0_pad($input, $size);
     
         $td = mcrypt_module_open(MCRYPT_TRIPLEDES, '', MCRYPT_MODE_ECB, '');
         $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
         @mcrypt_generic_init($td, $key, $iv);
         $data = mcrypt_generic($td, $input);

         mcrypt_generic_deinit($td);
         mcrypt_module_close($td);
         $data = base64_encode($data);
         return $data;
     }
            
	 function encrypt3Des1($string, $key){	 
        $cipher_alg = MCRYPT_TRIPLEDES;
        //初始化向量来增加安全性 
        $iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg,MCRYPT_MODE_ECB), MCRYPT_RAND); 
         
        //开始加密 
        $encrypted_string = mcrypt_encrypt($cipher_alg, $key, $string, MCRYPT_MODE_ECB, $iv); 
        return base64_encode($encrypted_string);//转化成16进制
        } 

     function decrypt($encrypted, $key){
          while (strlen($encrypted) % 4 != 0)
         {
              $encrypted .= "=";
	 
         }

         $encrypted = base64_decode($encrypted);
	      
         $td = mcrypt_module_open(MCRYPT_3DES,'',MCRYPT_MODE_ECB,'');
         $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
         $ks = mcrypt_enc_get_key_size($td);
         @mcrypt_generic_init($td, $key, $iv);
         $decrypted = mdecrypt_generic($td, $encrypted);
         mcrypt_generic_deinit($td);
         mcrypt_module_close($td);
      //   $y=$this->pkcs5_unpad($decrypted);
	       $y=$decrypted;
         return $y;
     }
   
    function K16byteTo24str($key16){
         $key24 = $key16 . substr($key16,0,16);
         $str="";

		 for ($i=0; $i < strlen($key24)/2; $i++) { 
		  	 $tmp=substr($key24, $i*2,2);
			 $str .= chr(hexdec($tmp));   
		  }

          return $str;
     }

	  function HexstrTobytes($str){
		$bytes=array();

		for ($i=0; $i < strlen($str)/2; $i++) { 
			$tmp=substr($str, $i*2,2);
			$bytes[] =  hexdec($tmp);   
		}
   
		return $bytes;
     }
     
	 function PackUrlBase64($strdata) {
		$strdata = str_ireplace("+","-",$strdata);
		$strdata = str_ireplace("/","_",$strdata);
		$strdata = str_ireplace("=","",$strdata);

		return $strdata;
     }

	 function UnPackUrlBase64($strdata) {
		$strdata = str_ireplace("-","+",$strdata);
		$strdata = str_ireplace("_","/",$strdata);
		
		return $strdata;
     }
				 
     function pkcs5_pad ($text, $blocksize) {
         $pad = $blocksize - (strlen($text) % $blocksize);
         return $text . str_repeat(chr($pad), $pad);
     }

	 function pkcs0_pad ($text, $blocksize) {
         $pad = $blocksize - (strlen($text) % $blocksize);
         return $text . str_repeat("\0", $pad);
     }
                 
     function pkcs5_unpad($text){
         $pad = ord($text{strlen($text)-1});
         if ($pad > strlen($text)) {
             return false;
         }
         if (strspn($text, chr($pad), strlen($text) - $pad) != $pad){
             return false;
         }
         return substr($text, 0, -1 * $pad);
     }

     function BytetoHexStr($bytes) { 
        $str = ''; 
        foreach($bytes as $ch) { 
            $str .= base_convert($ch , 10 , 16); 
        } 
  
           return $str; 
     } 
      function BytetoStr($bytes) {
        $str = '';
        foreach($bytes as $ch) {
            $str .= chr($ch);
        }


           return $str;
    }
}
                 
//$des = new CryptDes();
 
//$encrypt_str = $des->encrypt("1111",$key);//加密字符串
 
//echo $encrypt_str."\n";
//echo $decrypt_str = $des->decrypt($encrypt_str,"44DEAF82111DB2B04882B12D");//解密字符串


?>