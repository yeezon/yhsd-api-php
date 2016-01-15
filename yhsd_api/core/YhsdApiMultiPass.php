<?php


class YhsdApiMultiPass
{
    protected $cipher_key;
    protected $cipher_iv;
    protected $token;

    public function __construct($key)
    {
        $this->token = $key;
        $this->cipher_key = substr($key,0,16);
        $this->cipher_iv = substr($key,16,16);

    }

    public function aes_encrypt($data) {

        $block_size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char),$padding_char);
        $secret = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->cipher_key, $data, MCRYPT_MODE_CBC, $this->cipher_iv));
        $data = str_replace(array('+','/'),array('-','_'),$secret);
        return $data;
    }

    public function aes_decrypt($data) {
        $encryptedData = base64_decode($data);
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, pack('H*', $this->cipher_key), pack('H*', $encryptedData), MCRYPT_MODE_CBC, pack('H*', $this->cipher_iv));
        $decrypted = rtrim($decrypted,"\x00..\x1F");
        return $decrypted;
    }

    public function verify_sha_256($data,$hmac_header) {
        if (!$this->token) throw new Exception('无法获取token值');
        $digest = base64_encode(hash_hmac('sha256',$data,$this->token,true));
        return $hmac_header == $digest;
    }

}