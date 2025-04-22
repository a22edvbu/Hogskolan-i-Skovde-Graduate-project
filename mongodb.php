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
            echo "Connected to MongoDB successfully!";

            // Fetches email from emails collection in examensDB
            $collection = $client->examensDB->emails;
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        $method = "AES-256-CBC";
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
                    echo "<th> _id</th>";
                    echo "<th> ID</th>";
                    echo "<th> Date </th>";
                    echo "<th> From </th>";
                    echo "<th> To </th>";  
                    echo "<th> Subject </th>";  
                    echo "<th> Body </th>";  
                echo "</tr>";             
            echo "</thead>";

            // Finds all documents in emails
            $document = $collection->find([]);
            //$document = $collection->find(['projection' => ['_id' => 0], ]);

            // Clears file for each test
            clearFile("mdbDecrypt");

            // For each Document, print out in row
            foreach ($document as $doc) {
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