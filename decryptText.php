<?php
    function decryptText($encryptedText, $method) {
        $data = base64_decode($encryptedText);
        $iv = substr($data, 0, 16);
        $ciphertext = substr($data, 16);
        return openssl_decrypt($ciphertext, $method, getPrivateKey(), 0, $iv);
    }
?>