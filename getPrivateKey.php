<?PHP
    function getPrivateKey() {
        $keyFile = fopen("encryptionKey.txt", "r") or die("Unable to open file!");
        $encryptionKey = fread($keyFile,filesize("encryptionKey.txt"));
        fclose($keyFile);
        return $encryptionKey;
    }
?>