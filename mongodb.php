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
</body>
</html>