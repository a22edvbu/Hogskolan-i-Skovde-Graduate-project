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
        $queryLimit = " LIMIT " . 500;
        
        // declares the measurment variables before logging.
        $id = null;
        $measuredTime1 = null;
        $measuredTime2 = null;
        $measuredTime3 = null;
        $measureArr = [];
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
                
                // Insert function
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
                
                $querystring = 'SELECT * FROM emails ' . $qWhere . $queryLimit;
                
                //echo $querystring;

                $stmt = $pdo->prepare($querystring);
                $startmeasure3 = microtime(true);
                $stmt->execute();
                $fetchedResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else if ($_POST['sqlOperation'] == 'delete') {
                echo "Delete selected";

            // Default function
            } else {
                echo "Default selected";
                $querystring = 'SELECT * FROM emails' . $queryLimit;
                $stmt = $pdo->prepare($querystring);
                $startmeasure3 = microtime(true);
                $stmt->execute();
                $fetchedResults = $stmt->fetchAll(PDO::FETCH_ASSOC);        
            }
            $stopmeasure3 = microtime(true);
            $measuredTime3 = $stopmeasure3 - $startmeasure3;
        }
        $_POST = [];
        // else {
        //     echo "Default selected";
        //     $querystring = 'SELECT * FROM emails';
        //     $stmt = $pdo->prepare($querystring);
        //     $stmt->execute();
        //     $fetchedResults = $stmt->fetchAll(PDO::FETCH_ASSOC);     
        // }
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
        <label for="sqlDefault">Show All</label><br>


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
        
        <label for="sqlBody">Body (only for INSERT!): </label>
        <input type="text" name="sqlBody" id="sqlBody">
        
        <input type="submit" class="submitBtn" value="GO">
    </form>
    <?php
    // prints out the contents of a table
        echo count($fetchedResults) . " Rows fetched" ?: "0 Rows fetched"; 
        echo "<Table>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th style='width: 20px;'> ID</th>";
                    echo "<th style='width: 45px' > Date </th>";
                    echo "<th style='width: 200px;'> From </th>";
                    echo "<th style='width: 200px;'> To </th>";  
                    echo "<th style='width: 200px;'> Subject </th>"; 
                    echo "<th> Body </th>"; 
                echo "</tr>";             
            echo "</thead>";
            echo "<tbody>";
            
            //clearFile("sql");

            // foreach($pdo->query('select * from emails', PDO::FETCH_ASSOC) AS $row) {
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
                        'row' => $measuredTime1,
                        'table' => $measuredTime3
                    ];
                    echo "</tr>";
                }
            } 
            echo "</tbody>";
            echo "</Table>";
            
            // Only logs time when there is something new to add.
            // Sends ID and measured Time to be inserted into CSV data
            if (!empty($measureArr)) {
                //logTime("sql", $measureArr);
            }    
?>
</body>
</html>