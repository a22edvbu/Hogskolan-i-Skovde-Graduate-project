<?PHP
    function encryptText($plaintext, $method) {
        // Random number
        $iv = openssl_random_pseudo_bytes(16);
        return base64_encode($iv . openssl_encrypt($plaintext, $method, getPrivateKey(), 0, $iv));
    }
?>