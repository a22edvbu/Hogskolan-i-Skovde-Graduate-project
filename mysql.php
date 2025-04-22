<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL Database</title>
</head>
<style>
    
</style>
<body>
    <?php
        require 'decryptText.php';
        require 'encryptText.php';
        require 'getPrivateKey.php';
        require 'logTime.php';

        $servername = "localhost";
        $username = "root";
        $password = "";
        
        // Key used for encrypting and decrypting data
        // $keyFile = fopen("encryptionKey.txt", "r") or die("Unable to open file!");
        // $encryptionKey = fread($keyFile,filesize("encryptionKey.txt"));
        // fclose($keyFile);
        // The encryption method used
        $method = "AES-256-CBC";
        
        // DB connect
        try {
            $pdo = new PDO("mysql:host=$servername;dbname=examensdb", $username, $password);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully <br>";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        // Insert function
        // Takes the inputs from the form and connects them to the table attributes
        if (isset($_POST['sqlID'])) {
            // Encrypt the email body
            $sqlBody = $_POST['sqlBody'];
            $encryptedBody = encryptText($sqlBody, $method);

            $querystring = 'INSERT INTO emails (ID, Date, Mail_From, Mail_To, Subject, Body) VALUES (:ID, :DATE, :MAIL_FROM, :MAIL_TO, :SUBJECT, :BODY);';
            $stmt = $pdo->prepare($querystring);
            $stmt->bindParam(':ID', $_POST['sqlID']);
            $stmt->bindParam(':DATE', $_POST['sqlDate']);
            $stmt->bindParam(':MAIL_FROM', $_POST['sqlFrom']);
            $stmt->bindParam(':MAIL_TO', $_POST['sqlTo']);
            $stmt->bindParam(':SUBJECT', $_POST['sqlSubject']);
            $stmt->bindParam(':BODY', $encryptedBody);
            $stmt->execute();
        } 
    ?>
    <h1>MySQL</h1>
    <p>
        <div class="homeBtn"><a href="index.php">Home</a></div>
    </p>
    <h2>
        MySQL Database:
    </h2>
    <h3>Insert:</h3>
    <form action='mysql.php' method='POST' id="sqlPostForm">
        <label for="sqlID">ID: </label>
        <input type="text" name="sqlID" id="sqlID">

        <label for="sqlDate">Date: </label>
        <input type="text" name="sqlDate" id="sqlDate">
        
        <label for="sqlFrom">From: </label>
        <input type="text" name="sqlFrom" id="sqlFrom">
        
        <label for="sqlTo">To: </label>
        <input type="text" name="sqlTo" id="sqlTo">
        
        <label for="sqlSubject">Subject: </label>
        <input type="text" name="sqlSubject" id="sqlSubject">
        
        <label for="sqlBody">Content: </label>
        <input type="text" name="sqlBody" id="sqlBody">

        <input type="submit" value="Insert">
    </form>
    <?php
    // prints out the contents of a table
        echo "<Table>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th> ID</th>";
                    echo "<th> Date </th>";
                    echo "<th> From </th>";
                    echo "<th> To </th>";  
                    echo "<th> Subject </th>";  
                    echo "<th> Body </th>";  
                echo "</tr>";             
            echo "</thead>";
            echo "<tbody>";
            
            clearFile("sqlDecrypt");

            foreach($pdo->query('select * from emails', PDO::FETCH_ASSOC) AS $row) {
                echo "<tr>";
                foreach ($row as $col=>$val) {
                    echo "<td>";
                    // Only decrypts the Body column
                    if ($col == 'Body') {  
                        // Starts timer for measure
                        $startMeasure = microtime(true);
                        
                        $decrypted = decryptText($val, $method);
                        // Print error if decryption fails
                        
                        // Stops timer for measure
                        $stopMeasure = microtime(true);  

                        // Subtract startMeasure form StopMeasure to get difference
                        $measuredTime = ($stopMeasure - $startMeasure);    
                        echo $decrypted ?: "[ERROR: Not Decrypted]";                    
                    } else if ($col == 'ID') {
                        // Highlights ID
                        $id = $val;
                        echo "<b>" . $val . "</b>";
                    } else {
                        echo $val;
                    }
                    echo "</td>";
                }
                // Sends ID and measured Time to be inserted into CSV data
                logTime("sqlDecrypt",$id, $measuredTime);
                echo "</tr>";
            } 
            echo "</tbody>";
        echo "</Table>";
    ?>
</body>
</html>