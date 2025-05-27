<?php
function csvInsert() {
    
        $csvFile = fopen('eCrypted8k-edit.csv', 'r');
        
        fgetcsv($csvFile);
        
        while (($row = fgetcsv($csvFile)) !== FALSE) {
        // Map CSV columns to variables
        $id = $row[0];
        $date = $row[1];
        $from = $row[2];
        $to = $row[3];
        $subject = $row[4];
        $body = $row[5];
        
        $stmt = $pdo->prepare("INSERT INTO emails (ID, Date, Mail_From, Mail_To, Subject, Body) 
                               VALUES (:id, :date, :from, :to, :subject, :body)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':to', $to);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':body', $body);
        $stmt->execute();
    }
    
    fclose($csvFile);
    echo "Data import completed.";
}
?>