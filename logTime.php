<?php
function logTime($type, $id, $time) {
    $csvFile = fopen($type ."Data.csv", "a");
    
    // Optionally write a header row
    fputcsv($csvFile, [$id, $time]);
    fclose($csvFile);
}
function clearFile($type) {
    file_put_contents($type ."Data.csv", "");
}
?>