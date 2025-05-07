<?php
function logTime($type, $dataArr) {
    $csvFile = fopen($type ."Data1.csv", "a");

    foreach ($dataArr as $row) {
        fputcsv($csvFile, [$row['id'], $row['decrypt'], $row['row'], $row['table']]);
    }

    fclose($csvFile);
}
function clearFile($type) {
    file_put_contents($type ."Data.csv", "");
}
?>