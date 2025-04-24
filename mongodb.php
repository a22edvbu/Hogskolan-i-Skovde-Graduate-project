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
        $fetchedResults = [];

        if (isset($_POST['mdbID'])) {
            $mdbOperation = $_POST['mdbOperation'];
        
            if ($mdbOperation == 'insert') {
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
        
                $collection->insertOne($doc);
                echo "Document inserted.";
        
            } elseif ($mdbOperation == 'select') {
                echo "Select selected";
        
                $filter = [
                    'ID' => $_POST['mdbID'],
                    'Date' => $_POST['mdbDate'],
                    'Mail_From' => $_POST['mdbFrom'],
                    'Mail_To' => $_POST['mdbTo'],
                    'Subject' => $_POST['mdbSubject'],
                ];
        
                // Remove empty fields from filter
                $filter = array_filter($filter);
        
                $fetchedResults = $collection->find($filter);
        
            } elseif ($mdbOperation == 'delete') {
                echo "Delete selected";
            } else {
                echo "Default selected";
                $fetchedResults = $collection->find();
            }
        } 
        // else {
        //     echo "Default selected";
        //     $fetchedResults = $collection->find();
        // }
        
    ?>
    <h1>MongoDB</h1>
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
        echo "<Table>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th> _id</th>";
                    echo "<th> ID</th>";
                    echo "<th> Date </th>";
                    echo "<th> From </th>";
                    echo "<th> To </th>";  
                    echo "<th> Subject </th>";  
                    echo "<th> Body </th>";  
                echo "</tr>";             
            echo "</thead>";

            // Clears file for each test
            clearFile("mdbDecrypt");

            // For each Document, print out in row
            foreach ($fetchedResults as $doc) {
                echo "<tr>";
                // Save ID for current row
                $id = "";
                // For each field in document, print out in cell
                foreach ($doc as $field => $atr) {
                    echo "<td>";
                    if ($field == 'Body') {  
                        // Starts timer for measure
                        $startMeasure = microtime(true);

                        $decrypted = decryptText($atr, $method);
                        
                        // Stops timer for measure
                        $stopMeasure = microtime(true);  

                        // Subtract startMeasure form StopMeasure to get difference
                        $measuredTime = ($stopMeasure - $startMeasure);                  
                        
                        // Print error if decryption fails
                        echo $decrypted ?: "[ERROR: Not Decrypted]";    
                    } else if ($field == 'ID') {
                        // Highlights ID
                        $id = $atr;
                        echo "<b>" . $atr . "</b>";
                    } else if ($field == 'Date') {
                        // Reformats the date in db to readable YEAR-MONTH-DAY
                        echo $atr->toDateTime()->format('Y-m-d');
                    } else {
                        echo $atr;
                    }
                    echo "</td>";
                }
                // Sends ID and measured Time to be inserted into CSV data
                logTime("mdbDecrypt",$id, $measuredTime);
                echo "</tr>";
            }
            echo "</Table>";
    ?>
</body>
</html>