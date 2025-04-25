<?php
function logTime($type, $dataArr) {
    $csvFile = fopen($type ."Data.csv", "w");
    fputcsv($csvFile, ['ID', 'Decryption', 'Rows']); // header row

    foreach ($dataArr as $row) {
        fputcsv($csvFile, [$row['id'], $row['decrypt'], $row['row']]);
    }

    fclose($csvFile);
}
function clearFile($type) {
    file_put_contents($type ."Data.csv", "");
}
?>