<?php

namespace app\helpers;

use Yii;
use \phpseclib\Crypt\AES;

class AesHelper
{

    private static $aesKey = 'THISISAAESENCRYPTHELPERM';

    public static function encrypt(string $string)
    {
        $cipher = new Aes(Aes::MODE_ECB);
        $cipher->setKey(self::$aesKey);
        return bin2hex($cipher->encrypt($string));
    }

    public static function decrypt(string $string)
    {
        if (empty($string)) {
            return "";
        }
        $cipher = new Aes(Aes::MODE_ECB);
        $cipher->setKey(self::$aesKey);
        return $cipher->decrypt(hex2bin($string));
    }

}
