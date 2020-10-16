<?php


namespace MUSICAA\lib\traits;


trait Encription
{
    public function enc($data) {
        $enc_key = base64_decode(KEY);
        $openssl = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $iv = ($openssl !== false)? $openssl:'';
        $enc_data = openssl_encrypt($data, 'aes-256-cbc', $enc_key, 0, $iv);
        return base64_encode($enc_data . '::' . $iv);
    }


    public function dec($data){
        $enc_key = base64_decode(KEY);

        list($enc_data, $iv) = array_pad(explode('::', base64_decode($data), 2),2,null);
        return openssl_decrypt($enc_data, 'aes-256-cbc', $enc_key, 0, $iv);
    }
}