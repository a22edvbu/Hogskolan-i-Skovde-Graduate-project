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
        require 'decryptText.php';
        require 'encryptText.php';
        require 'getPrivateKey.php';
        require 'logTime.php';



        // Connects to MongoDB through XAMPP server with feedback
        try {
            $client = new MongoDB\Client("mongodb://localhost:27017");
            echo "Connected to MongoDB successfully! <br>";

            // Fetches email from emails collection in examensDB
            $collection = $client->examensDB->emails;
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        $method = "AES-256-CBC";
        
        // 500, 1000, 2000, 4000, 8000
        $queryLimit = "";

        // declares the measurment variables before logging.
        $id = null;
        $measuredTime1 = null;
        $measuredTime2 = null;
        $measuredTime3 = null;
        $measureArr = [];

        $fetchedResults = [];

        if (isset($_POST['mdbID'])) {        
            if ($_POST['mdbOperation'] == 'insert') {
                echo "Insert selected";
        
                // Encrypt the email body
                $mdbBody = $_POST['mdbBody'];
                $encryptedBody = encryptText($mdbBody, $method);
        
                $doc = [
                    'ID' => $_POST['mdbID'],
                    'Date' => $_POST['mdbDate'],
                    'Mail_From' => $_POST['mdbFrom'],
                    'Mail_To' => $_POST['mdbTo'],
                    'Subject' => $_POST['mdbSubject'],
                    'Body' => $encryptedBody
                ];
                $startmeasure3 = microtime(true);
                $collection->insertOne($doc);
                echo "Document inserted.";
        
            } elseif ($_POST['mdbOperation'] == 'select') {
                $filter = [];

                if (!empty($_POST['mdbID'])) {
                    $filter['ID'] = (int)$_POST['mdbID'];           // ID
                }                
                if (!empty($_POST['mdbDate'])) {                    // Date
                    // fetch all documents that fit within mdbDate timeline
                    $mdbDate = $_POST['mdbDate'];
                
                    // Convert from yyyy-mm-dd to MongoDB UTCDateTime for filter
                    $startDate = new MongoDB\BSON\UTCDateTime((new DateTime($mdbDate . ' 00:00:00'))->getTimestamp() * 1000);
                    $endDate = new MongoDB\BSON\UTCDateTime((new DateTime($mdbDate . ' 23:59:59'))->getTimestamp() * 1000);
                
                    $filter['Date'] = [
                        '$gte' => $startDate,
                        '$lte' => $endDate
                    ];
                }
                
                if (!empty($_POST['mdbFrom'])) {
                    $filter['From'] = $_POST['mdbFrom'];            // From
                }
                if (!empty($_POST['mdbTo'])) {
                    $filter['To'] = $_POST['mdbTo'];                // To
                }
                if (!empty($_POST['mdbSubject'])) {
                    $filter['Subject'] = $_POST['mdbSubject'];      // Subject
                }

                echo "<pre>";
                    print_r($filter);
                echo "</pre>";

                // Run the query
                $startmeasure3 = microtime(true);
                $fetchedResults = $collection->find($filter);


            } elseif ($_POST['mdbOperation'] == 'delete') {
                echo "Delete selected";
            } else {
                echo "Default selected";
                $startmeasure3 = microtime(true);

                echo "<pre>";
                    print_r($fetchedResults);
                echo "</pre>";

                $fetchedResults = $collection->find();
            }
            $stopmeasure3 = microtime(true);
            $measuredTime3 = $stopmeasure3 - $startmeasure3;
        } 
        // else {
        //     echo "Default selected";
        //     $fetchedResults = $collection->find();
        // }
        
    ?>
    <h1 class="title">MongoDB</h1>
    <p>
        <div class="homeBtn"><a href="index.php">Home</a></div>
    </p>
    <h2>
        MongoDB Database:
    </h2>
    <h3>Operations:</h3>
    <form action='mongodb.php' method='POST' id="mdbSearch">
        <input type="radio" name="mdbOperation" id="mdbInsert" class="insertRadio" value="insert">
        <label for="mdbInsert">INSERT</label>
        <input type="radio" name="mdbOperation" id="mdbSelect" class="selectRadio" value="select">
        <label for="mdbSelect">SELECT</label>
        <input type="radio" name="mdbOperation" id="mdbDelete" class="deleteRadio" value="delete">
        <label for="mdbDelete">DELETE</label>
        <input type="radio" name="mdbOperation" id="mdbDefault" class="defaultRadio" value="default">
        <label for="mdbDefault">Show All</label><br>


        <label for=mdblID">ID: </label>
        <input type="text" name="mdbID" id="mdbID">

        <label for="mdbDate">Date: </label>
        <input type="text" name="mdbDate" id="mdbDate">
        
        <label for="mdbFrom">From: </label>
        <input type="text" name="mdbFrom" id="mdbFrom">
        
        <label for="mdbTo">To: </label>
        <input type="text" name="mdbTo" id="mdbTo">
        
        <label for="mdbSubject">Subject: </label>
        <input type="text" name="mdbSubject" id="mdbSubject">
        
        <label for="mdbBody">Body (only for INSERT!): </label>
        <input type="text" name="mdbBody" id="mdbBody">
        
        <input type="submit" class="submitBtn" value="GO">
    </form>
    <?php
    // prints out the contents of a table
        //echo count($fetchedResults) . " Rows fetched" ?: "0 Rows fetched"; 
        echo "<Table>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th style='width: 200px;'> _id</th>";
                    echo "<th style='width: 20px;'> ID</th>";
                    echo "<th style='width: 45px' > Date </th>";
                    echo "<th style='width: 200px;'> From </th>";
                    echo "<th style='width: 200px;'> To </th>";  
                    echo "<th style='width: 200px;'> Subject </th>";  
                    echo "<th> Body </th>";  
                echo "</tr>";             
            echo "</thead>";

            // Clears file for each test
            //clearFile("mdb");

            // For each Document, print out in row
            foreach ($fetchedResults as $doc) {
                echo "<tr>";
                $startmeasure1 = microtime(true);   

                // Save ID for current row
                //$id = "";

                // For each field in document, print out in cell
                foreach ($doc as $field => $atr) {
                    if ($field == 'Body') {  
                        echo "<td style='text-align: justify;'>";
                        // Starts timer for measure
                        $startmeasure2 = microtime(true);
                        
                        $decrypted = decryptText($atr, $method);
                        
                        // Stops timer for measure
                        $stopMeasure = microtime(true);  
                        
                        // Subtract startMeasure form StopMeasure to get difference
                        $stopmeasure2 = microtime(true);  
                        $measuredTime2 = ($stopmeasure2 - $startmeasure2);
                        
                        // Print error if decryption fails
                        echo htmlspecialchars($decrypted) ?: "[ERROR: Not Decrypted]";
                        //echo $decrypted ?: "[ERROR: Not Decrypted]";   
                        echo "</td>"; 
                    } else if ($field == 'ID') {
                        echo "<td>";
                        // Highlights ID
                        $id = $atr;
                        echo "<b>" . htmlspecialchars($atr) . "</b>";
                        echo "</td>"; 
                    } else if ($field == 'Date') {
                        echo "<td>";
                        // Reformats the date in db to readable YEAR-MONTH-DAY
                        
                        echo htmlspecialchars($atr->toDateTime()->format('Y-m-d'));
                        echo "</td>"; 
                    } else {
                        echo "<td>";
                        echo htmlspecialchars($atr);
                        echo "</td>"; 
                    }
                }
                // Subtract startMeasure form StopMeasure to get difference
                $stopmeasure1 = microtime(true);
                $measuredTime1 = ($stopmeasure1 - $startmeasure1); 
                
                $measureArr[] = [
                    'id' => $id,
                    'decrypt' => $measuredTime2,
                    'row' => $measuredTime1,
                    'table' => $measuredTime3
                ];
                               
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</Table>";
            // Only logs time when there is something new to add.
            // Sends ID and measured Time to be inserted into CSV data
            if (!empty($measureArr)) {
                //logTime("mdb", $measureArr);
            }    
    ?>
</body>
</html>