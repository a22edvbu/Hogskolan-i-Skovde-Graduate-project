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
        $servername = "localhost";
        $username = "root";
        $password = "";

        // DB connect
        try {
            $pdo = new PDO("mysql:host=$servername;dbname=examensdb", $username, $password);
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
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
        <label for="sqlName">Name: </label>
        <input type="text" name="sqlName" id="sqlName">

        <label for="sqlID">ID: </label>
        <input type="text" name="sqlID" id="sqlID">
        
        <label for="sqlBday">Birthday: </label>
        <input type="text" name="sqlBday" id="sqlBday">
        
        <label for="sqlNotes">Notes: </label>
        <input type="text" name="sqlNotes" id="sqlNotes">

        <input type="submit" value="Insert">
    </form>
    <?php
    // prints out the contents of a table
        echo "<Table>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th> Namn</th>";
                    echo "<th> PersonNr </th>";
                    echo "<th> Född </th>";
                    echo "<th> Notes </th>";  
                echo "</tr>";             
            echo "</thead>";
            foreach($pdo->query('select * from test_table', PDO::FETCH_ASSOC) AS $row) {
                echo "<tr>";
                foreach ($row as $col=>$val) {
                    echo "<td>";
                    echo $val;
                    echo "</td>";
                }
                echo "</tr>";
            } 
        echo "</Table>";
    ?>
</body>
</html>