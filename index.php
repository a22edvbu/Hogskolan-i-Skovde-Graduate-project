<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GaZoline Database Services</title>
</head>
<body>
    <?php
    require 'decryptText.php';
    require 'encryptText.php';
    require 'getPrivateKey.php';

    $method = "AES-256-CBC";

    function decryptCSV($method) {
        $row = 0;
        
        if (($handle = fopen("structuredEmails8k.csv", "r")) !== FALSE) {
            $output = fopen("./Measurements/emails8k-edit.csv", "w"); // New file for encrypted data
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { 
                if ($row === 0) {
                    // Write out other columns as normal
                    fputcsv($output, $data);
                } else {
                    // Encrypt the BODY column
                    if (isset($data[5])) {
                        $data[5] = decryptText($data[5], $method);
                    }
                    fputcsv($output, $data); // Write modified row to file
                }
                $row++;
            }
        
            fclose($handle);
            fclose($output);
            echo "CSV file DeCrypted! saved to emails8K-edit.csv";
        } else {
            echo "Error opening testEmails.csv.";
        }
    }
    function writeToCSV($method) {
        $row = 0;
        
        if (($handle = fopen("structuredEmails8k.csv", "r")) !== FALSE) {
            $output = fopen("emails8k-edit.csv", "w"); // New file for encrypted data
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $iv = openssl_random_pseudo_bytes(16); 
                if ($row === 0) {
                    // Write out other columns as normal
                    fputcsv($output, $data);
                } else {
                    // Encrypt the BODY column
                    if (isset($data[5])) {
                        $data[5] = encryptText($data[5], $method);
                    }
                    fputcsv($output, $data); // Write modified row to file
                }
                $row++;
            }
        
            fclose($handle);
            fclose($output);
            echo "CSV file encrypted! saved to EmailsEncrypted.csv";
        } else {
            echo "Error opening testEmails.csv.";
        }
    }
    // Scans for button to be pressed before running writeToCSV()
    if (isset($_POST['encryptCSV'])) {
        writeToCSV($method);
    }

    ?>
    <h1> GaZoline Database Services </h1>
    <p> De coolaste databaserna known to man!</p>
    <div class="mongoDB"><a href="mongodb.php">MongoDB</a></div>
    <div class="MySQL"><a href="mysql.php">MySQL</a></div>
    <p>
    
    <label for="measureToggle">Mätläge </label>
    <input name="measureToggle" id="measureToggle" type="checkbox">
    
        <form action="index.php" method="POST">
            <input type="submit" name="encryptCSV" value="Encrypt CSV">
        </form>
    </p>
</body>
</html>