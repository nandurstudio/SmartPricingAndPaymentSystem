<?php

namespace App\Helpers;

class Encrypt
{
    // Ini bisa dipakai untuk data lain, bukan untuk password
    private static $encryptionKey = '3c0mp3t3ncy1234'; // Contoh key, jangan hardcode di production

    public static function encryptPassword($data)
    {
        $cipher = "aes-256-cbc";
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encrypted = openssl_encrypt($data, $cipher, self::$encryptionKey, 0, $iv);

        return base64_encode($encrypted . '::' . $iv);
    }

    public static function decryptPassword($encryptedData)
    {
        $cipher = "aes-256-cbc";
        list($encrypted, $iv) = explode('::', base64_decode($encryptedData), 2);

        return openssl_decrypt($encrypted, $cipher, self::$encryptionKey, 0, $iv);
    }
}
