<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GaZoline Database Services</title>
</head>
<body>
    <?php
    function writeToCSV($method) {
        $row = 0;
        
        if (($handle = fopen("testEmails.csv", "r")) !== FALSE) {
            $output = fopen("testEmailsEncrypt.csv", "w"); // New file for encrypted data
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $iv = openssl_random_pseudo_bytes(16); // Useless because IV is generated in encryptText() anyway
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
            echo "CSV file encrypted! saved to testEmailsEncrypt.csv";
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
        <form action="mysql.php" method="POST">
            <input type="submit" name="encryptCSV" value="Encrypt CSV">
        </form>
    </p>
</body>
</html>