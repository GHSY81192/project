<?php
/**
 * Crypt3Des
 */
class Crypt3Des {
    public $key;
    function __construct($key){
        $this->key = $key;
    }
    function encrypt($input){
        $size = mcrypt_get_block_size(MCRYPT_3DES,'ecb');
        $input = $this->pkcs5_pad($input, $size);
        $key = str_pad($this->key,24,'0');
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'ecb', '');
        $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        //$data = base64_encode($this->PaddingPKCS7($data));
        $data = base64_encode($data);
        return $data;
    }

    function decrypt($encrypted){
        $encrypted = base64_decode($encrypted);
        $key = str_pad($this->key,24,'0');
        $td = mcrypt_module_open(MCRYPT_3DES,'','ecb','');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $encrypted);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $y=$this->pkcs5_unpad($decrypted);
        return $y;
    }

    function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
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

    function PaddingPKCS7($data) {
        $block_size = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_CBC);
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char),$padding_char);
        return $data;
    }
}

?>