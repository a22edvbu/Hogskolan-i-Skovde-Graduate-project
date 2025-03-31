<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MongoDB Database</title>
</head>
<body>
    <?php
        // Composer autoloader for php extensions and libraries 
        require 'vendor/autoload.php';

        // Connects to MongoDB through XAMPP server with feedback
        try {
            $client = new MongoDB\Client("mongodb://localhost:27017");
            echo "Connected to MongoDB successfully!";

            // Fetches email from emails collection in examensDB
            $collection = $client->examensDB->emails;
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        $method = "AES-256-CBC";

        function getPrivateKey() {
            $keyFile = fopen("encryptionKey.txt", "r") or die("Unable to open file!");
            $encryptionKey = fread($keyFile,filesize("encryptionKey.txt"));
            fclose($keyFile);
            return $encryptionKey;
        }
        function encryptText($plaintext, $iv, $method) {
            // Random number
            $iv = openssl_random_pseudo_bytes(16);
            return base64_encode($iv . openssl_encrypt($plaintext, $method, getPrivateKey(), 0, $iv));
        }
        function decryptText($encrypted_text, $method) {

            $data = base64_decode($encrypted_text);
            $iv = substr($data, 0, 16);
            $ciphertext = substr($data, 16);
            return openssl_decrypt($ciphertext, $method, getPrivateKey(), 0, $iv);
        }

        
        
    ?>
    <h1>MongoDB</h1>
    <p>
        <div class="homeBtn"><a href="index.php">Home</a></div>
    </p>
    <h2>
        MongoDB Database:
    </h2>
    <h3>Insert:</h3>
    <form action='mongodb.php' method='POST' id="mongoPostForm">
        <label for="mongoName">Name: </label>
        <input type="text" name="mongoName" id="mongoName">

        <label for="mongoID">ID: </label>
        <input type="text" name="mongoID" id="mongoID">
        
        <label for="mongoBday">Birthday: </label>
        <input type="text" name="mongoBday" id="mongoBday">
        
        <label for="mongoNotes">Notes: </label>
        <input type="text" name="mongoNotes" id="mongoNotes">

        <input type="submit" value="Insert">
    </form>
    <?php
    // prints out the contents of a table
        echo "<Table>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th> _ID</th>";
                    echo "<th> ID</th>";
                    echo "<th> Datum </th>";
                    echo "<th> Från </th>";
                    echo "<th> Till </th>";  
                    echo "<th> Titel </th>";  
                    echo "<th> Innehåll </th>";  
                echo "</tr>";             
            echo "</thead>";

            // Finds all documents in emails
            $document = $collection->find([]);
            //$document = $collection->find(['projection' => ['_id' => 0], ]);

            // For each Document, print out in row
            foreach ($document as $doc) {
                echo "<tr>";
                // For each attribute in document, print out in column
                foreach ($doc as $field => $atr) {
                    echo "<td>";
                    if ($field == 'Body') {  
                        $decrypted = decryptText($atr, $method);
                        // Print error if decryption fails
                        echo $decrypted ?: "[ERROR: Not Decrypted]";
                        //echo $atr;                    
                    } else if ($field == 'ID') {
                        // Highlights ID
                        echo "<b>" . $atr . "</b>";
                    } else if ($field == 'Date') {
                        // Reformats the date in db to readable YEAR-MONTH-DAY
                        echo $atr->toDateTime()->format('Y-m-d');
                    } else {
                        echo $atr;
                    }
                    echo "</td>";
                }                
                echo "</tr>";
            }
        echo "</Table>";
    ?>
</body>
</html>