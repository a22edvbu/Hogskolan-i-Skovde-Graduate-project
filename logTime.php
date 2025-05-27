<?php
function logTime($type, $dataArr) {
    $csvFile = fopen("Measurements/Insert/8000 Limit/" . $type ."Data1.csv", "a");

    foreach ($dataArr as $row) {
        if ($type === "mdbFilteredAll8000" || $type === "sqlFilteredAll8000") {
            fputcsv($csvFile, [$row['id'], $row['decrypt'], $row['row']]);

        } else if ($type === "mdbInsert" || $type === "sqlInsert") {
            fputcsv($csvFile, [$row['insert'], $row['amount'], $row['avgEncrypt'], $row['avgInsert']]);

        } else {
            fputcsv($csvFile, [$row['table'], $row['matches'], $row['avgDecrypt']]);
        }
    }

    fclose($csvFile);
}
function clearFile($type) {
    file_put_contents($type ."Data.csv", "");
}
?>