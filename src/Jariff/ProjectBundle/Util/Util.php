<?php

/*
 * Jariff Multi Corpindo (c)
 */

namespace Jariff\ProjectBundle\Util;

class Util
{

    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);

        return $text;
    }

    static public function boldFind($text,$q,$collect){
        $string = $text;
        $searchingFor = "/".$q."/";
        $replacePattern = "<b>$0</b>";

        return preg_replace($searchingFor, $replacePattern, $string);
    }

    public static function encrypt($text, $key)
    {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    public static function decrypt($text, $key)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    public static function token($value = '')
    {
        return substr(str_replace(array('/', '=', '+'), '', Util::encrypt(md5(uniqid()).md5(microtime().$value), md5(uniqid()))), 0, 64);
    }

    public static function ribuan($angka)
    {
        $jadi = number_format($angka, 0, '.', ',');
        return $jadi;
    }


}
