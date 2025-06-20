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
        
        // The encryption method used
        $method = "AES-256-CBC";

        // Limits the amount of rows fetched
        // 500, 1000, 2000, 4000, 8000
        $queryLimit = 8000;
        
        // declares the measurment variables before logging.
        $id = null;
        $measuredTime1 = null;
        $measuredTime2 = null;
        $measuredTime3 = null;
        $avgDecrypt;
        $measureArr = [];
        $measureFetchArr = [];
        $encryptionTimeArr = [];
        $fetchedResults = [];
        
        // DB connect
        try {
            $pdo = new PDO("mysql:host=$servername;dbname=examensdb", $username, $password);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully <br>";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        // Recieves the POST from form and identify type of operation
        if (isset($_POST['sqlID'])) {            
            if($_POST['sqlOperation'] == 'insert') {
                // // Insert function
                echo "Insert selected";
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
                $startmeasure3 = microtime(true);
                $stmt->execute();
                $fetchedResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            } else if ($_POST['sqlOperation'] == 'select'){
                
                echo "Select selected";
                
                $queryArr = [];
                
                if (!empty($_POST['sqlID'])) {                  // ID
                    $id = $_POST['sqlID'];
                    $queryArr[] = "ID = $id";
                }
                if (!empty($_POST['sqlDate'])) {                // Date
                    $date = $_POST['sqlDate'];
                    $queryArr[] = "Date = '$date'";
                }
                if (!empty($_POST['sqlFrom'])) {                // Mail_From
                    $mail_from = $_POST['sqlFrom'];
                    $queryArr[] = "Mail_From = '$mail_from'";
                }
                if (!empty($_POST['sqlTo'])) {                  // Mail_To
                    $mail_to = $_POST['sqlTo'];
                    $queryArr[] = "Mail_To = '$mail_to'";
                }
                if (!empty($_POST['sqlSubject'])) {             // Subject
                    $subject = $_POST['sqlSubject'];
                    $queryArr[] = "Subject = '$subject'";
                }

                
                echo "<pre>";
                    print_r($queryArr);
                echo "</pre>";
            
                // If array has item, delete AND from first item string 
                if (!empty($queryArr)) {
                    $qWhere = 'WHERE ' . implode(' AND ', $queryArr);
                }
                
                $querystring = 'SELECT * FROM emails ' . $qWhere . ' LIMIT ' . $queryLimit;
                
                //echo $querystring;

                $stmt = $pdo->prepare($querystring);
                $startmeasure3 = microtime(true);
                $stmt->execute();
                $fetchedResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else if ($_POST['sqlOperation'] == 'delete') {
                
                echo "Delete selected";

                // Default function
            } else if ($_POST['sqlOperation'] == 'insertAll') {
                if (($handle = fopen('structuredEmails8k.csv', "r")) !== false) {
                    $header = fgetcsv($handle); // Skip the header

                    $querystring = 'INSERT INTO emails (ID, Date, Mail_From, Mail_To, Subject, Body) 
                                    VALUES (:ID, :DATE, :MAIL_FROM, :MAIL_TO, :SUBJECT, :BODY)';
                    $stmt = $pdo->prepare($querystring);

                    $measureArrInsert = [];

                    $startMeasure4 = microtime(true);
                    while (($row = fgetcsv($handle)) !== false) {
                        // Encrypt the Body column
                        $sqlBody = $row[5];
                        $startMeasure5 = microtime(true);
                        $encryptedBody = encryptText($sqlBody, $method);
                        $stopMeasure5 = microtime(true);
                        
                        $measureTime5 = $stopMeasure5 - $startMeasure5;
                        
                        $stmt->bindValue(':ID', $row[0]);
                        $stmt->bindValue(':DATE', $row[1]);
                        $stmt->bindValue(':MAIL_FROM', $row[2]);
                        $stmt->bindValue(':MAIL_TO', $row[3]);
                        $stmt->bindValue(':SUBJECT', $row[4]);
                        $stmt->bindValue(':BODY', $encryptedBody);
                        
                        $startMeasureInsert = microtime(true);
                        $stmt->execute();
                        $stopMeasureInsert = microtime(true);

                        $measureTimeInsert = $stopMeasureInsert - $startMeasureInsert;
                        $encryptionTimeArr[] = [
                            'insertTime' => $measureTimeInsert,
                            'encrypt' => $measureTime5
                        ];
                    }
                    $stopMeasure4 = microtime(true);
                    

                    $measureTime4 = $stopMeasure4 - $startMeasure4;
                    
                    $insertTimes = array_column($encryptionTimeArr, 'insertTime');
                    $encryptTimes = array_column($encryptionTimeArr, 'encrypt');

                    $totalEncryptTime = array_sum($encryptTimes);

                    $avgEncrypt = array_sum($encryptTimes) / count($encryptTimes);
                    $avgInsert = array_sum($insertTimes) / count($insertTimes);

                    $measureArrInsert[] = [
                        'insert' => $measureTime4 - $totalEncryptTime,
                        'amount' => count($encryptionTimeArr),
                        'avgEncrypt' => $avgEncrypt,
                        'avgInsert' => $avgInsert
                    ];

                    fclose($handle);
                    echo "Data inserted successfully.";

                    logTime("sqlInsert", $measureArrInsert);
                    
                    $querystring2 = 'DELETE FROM emails'; 
                    $stmt2 = $pdo->prepare($querystring2);
                    $stmt2->execute();

                } else {
                    echo "Failed to open file.";
                }
            } else {
                echo "Default selected";
                $querystring = 'SELECT * FROM emails' . ' LIMIT ' . $queryLimit;
                $stmt = $pdo->prepare($querystring);
                $startmeasure3 = microtime(true);
                $stmt->execute();
                $fetchedResults = $stmt->fetchAll(PDO::FETCH_ASSOC);        
            }
            $stopmeasure3 = microtime(true);
            $measuredTime3 = $stopmeasure3 - $startmeasure3;
        }
    ?>
    <h1 class="title">MySQL</h1>
    <p>
        <div class="homeBtn"><a href="index.php">Home</a></div>
    </p>
    <h2>
        MySQL Database:
    </h2>
    <h3>Operation:</h3>
    <form action='mysql.php' method='POST' id="sqlSearch">
        <input type="radio" name="sqlOperation" id="sqlInsert" class="insertRadio" value="insert">
        <label for="sqlInsert">INSERT</label>
        <input type="radio" name="sqlOperation" id="sqlSelect" class="selectRadio" value="select">
        <label for="sqlSelect">SELECT</label>
        <input type="radio" name="sqlOperation" id="sqlDelete" class="deleteRadio" value="delete">
        <label for="sqlDelete">DELETE</label>
        <input type="radio" name="sqlOperation" id="sqlDefault" class="defaultRadio" value="default">
        <label for="sqlDefault">Show All</label>
        <input type="radio" name="sqlOperation" id="sqlInsertAll" class="insertAllRadio" value="insertAll">
        <label for="sqlInsertAll">Insert All</label><br>


        <label for="sqlID">ID: </label>
        <input type="text" class="idInput" name="sqlID" id="sqlID">

        <label for="sqlDate">Date: </label>
        <input type="text" class="dateInput" name="sqlDate" id="sqlDate">
        
        <label for="sqlFrom">From: </label>
        <input type="text" class="fromInput" name="sqlFrom" id="sqlFrom">
        
        <label for="sqlTo">To: </label>
        <input type="text" class="toInput" name="sqlTo" id="sqlTo">
        
        <label for="sqlSubject">Subject: </label>
        <input type="text" class="subjectInput" name="sqlSubject" id="sqlSubject">
        
        <label for="sqlBody">Body (only for INSERT!): </label>
        <input type="text" class="bodyInput" name="sqlBody" id="sqlBody">
        
        <input type="submit" class="submitBtn" value="GO">
    </form>
    <?php
    // prints out the contents of a table
        //echo count($fetchedResults) . " Rows fetched" ?: "0 Rows fetched"; 
        echo "<Table>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th style='width: 35px;'> ID</th>";
                    echo "<th style='width: 45px' > Date </th>";
                    echo "<th style='width: 200px;'> From </th>";
                    echo "<th style='width: 200px;'> To </th>";  
                    echo "<th style='width: 200px;'> Subject </th>"; 
                    echo "<th> Body </th>"; 
                echo "</tr>";             
            echo "</thead>";
            echo "<tbody>";
            
            //clearFile("sql");

            echo "<p class='resultNr'>" . count($fetchedResults) . " Rows fetched </p>";
            if (!empty($fetchedResults)) {
                foreach($fetchedResults as $row) {
                    echo "<tr>";
                    $startmeasure1 = microtime(true);   
                    
                    foreach ($row as $col=>$val) {
                        // Only decrypts the Body column
                        if ($col == 'Body') {  
                            echo "<td>";
                            // Starts timer for measure
                            $startmeasure2 = microtime(true);
                            
                            $decrypted = decryptText($val, $method);
                            
                            // Stops timer for measure
                            $stopmeasure2 = microtime(true);  
                            
                            // Subtract startMeasure form StopMeasure to get difference
                            $measuredTime2 = ($stopmeasure2 - $startmeasure2);
                            
                            // Print error if decryption fails
                            echo $decrypted ?: "[ERROR: Not Decrypted]";                    
                            echo "</td>";
                        } else if ($col == 'ID') {
                            echo "<td>";
                            // Highlights ID
                            $id = $val;
                            echo "<b>" . $val . "</b>";
                            $idArr[] = $id;
                            echo "</td>";
                        } else {
                            echo "<td>";
                            echo $val;
                            echo "</td>";
                        }
                    }
                    $stopmeasure1 = microtime(true);
                    
                    // Subtract startMeasure form StopMeasure to get difference
                    $measuredTime1 = ($stopmeasure1 - $startmeasure1); 
                    
                    $measureArr[] = [
                        'id' => $id,
                        'decrypt' => $measuredTime2,
                        'row' => $measuredTime1
                    ];
                    echo "</tr>";
                }
                $decryptTimes = array_column($measureArr, 'decrypt');
                $avgDecrypt = array_sum($decryptTimes) / count($decryptTimes);
            } 
            echo "</tbody>";
            echo "</Table>";

            $measureFetchArr[] = [
                'table' => $measuredTime3,
                'matches' => count($fetchedResults),
                'avgDecrypt' => $avgDecrypt
            ];
            // Only logs time when there is something new to add.
            // Sends ID and measured Time to be inserted into CSV data
            if (!empty($measureArr)) {
                // !- REMOVE COMMENTS TO MEASURE -!
                // --------------------------------
                // logTime("sqlFilteredAll" . $queryLimit, $measureArr);
                // logTime("sqlFilteredFetchALL" . $queryLimit, $measureFetchArr);
            }    
?>
</body>
</html>