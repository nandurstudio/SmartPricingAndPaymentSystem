<?php

namespace App\Helpers;

class Encrypt
{
    private static $encryptionKey = '3c0mp3t3ncy1234'; // Kunci enkripsi

    // Fungsi untuk enkripsi password
    public static function encryptPassword($password)
    {
        $cipher = "aes-256-cbc"; // Algoritma AES 256-bit
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $encryptedPassword = openssl_encrypt($password, $cipher, self::$encryptionKey, 0, $iv);

        // Kembalikan hasil enkripsi bersama IV
        return base64_encode($encryptedPassword . '::' . $iv);
    }

    // Fungsi untuk dekripsi password
    public static function decryptPassword($encryptedPassword)
    {
        $cipher = "aes-256-cbc"; // Algoritma yang sama untuk dekripsi
        list($encryptedData, $iv) = explode('::', base64_decode($encryptedPassword), 2);

        return openssl_decrypt($encryptedData, $cipher, self::$encryptionKey, 0, $iv);
    }
}
