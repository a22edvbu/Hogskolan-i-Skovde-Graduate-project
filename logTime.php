<?php
function logTime($type, $dataArr) {
    $csvFile = fopen($type ."Data.csv", "a");
    //fputcsv($csvFile, ['ID', 'Decryption', 'Rows']); // header row

    foreach ($dataArr as $row) {
        fputcsv($csvFile, [$row['id'], $row['decrypt'], $row['row'], $row['table']]);
    }

    fclose($csvFile);
}
function clearFile($type) {
    file_put_contents($type ."Data.csv", "");
}
?>