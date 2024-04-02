<?php

function prompt($message) {
    echo $message;
    $handle = fopen("php://stdin", "r");
    $input = fgets($handle);
    return trim($input);
}

function csvToArray($filename, $delimiter = ';') {
    $array = [];
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            $array[] = $data;
        }
        fclose($handle);
    }
    return $array;
}

function csvSaver($tempArray, $newCsv) {
    $data = implode(PHP_EOL, $tempArray);
    // Преобразуем кодировку из UTF-8 в Windows-1251
    $data = iconv('UTF-8', 'Windows-1251', $data);
    file_put_contents($newCsv, $data);
}

echo "Консольное окошко операций\n";

$start = microtime(true); // Начало измерения времени

$filename = prompt("1 - Введите путь к файлу, который преобразуется в массив: ");
$groupFolder = prompt("2 - Задайте \$groupFolder: ");
$newCsv = prompt("3 - Введите название файла для сохранения: ");
$name = 'name';
$groupName = 'group_name';
$groupFolderPath = 'group_folder_path';
$target = 'target';
$key1 = 'купить';
$key2 = 'цена';

$csvArray = csvToArray($filename);

$tempArray = [];
array_push($tempArray, $name . ';' . $groupName .';' .$groupFolderPath .';' .$target);
for ($i = 1; $i < count($csvArray); $i++) {
    array_push($tempArray, $csvArray[$i][1] . ';' . $csvArray[$i][0] . ' - /k/'.';' .$groupFolder.$csvArray[$i][1] .';' .$csvArray[$i][2]);
    array_push($tempArray, $csvArray[$i][1]. ' ' . $key1. ';' . $csvArray[$i][0] . ' - /k/'.';' .$groupFolder.$csvArray[$i][1].';' .$csvArray[$i][2]);
    array_push($tempArray, $csvArray[$i][1]. ' ' .$key2. ';' . $csvArray[$i][0] . ' - /k/'.';' .$groupFolder.$csvArray[$i][1].';' .$csvArray[$i][2]);
}

csvSaver($tempArray, $newCsv);

$end = microtime(true); // Конец измерения времени
$executionTime = ($end - $start) * 1000; // Время выполнения в миллисекундах

echo "\nОперация завершена за " . round($executionTime, 2) . " ms, файл сохранен как " . $newCsv . "\n";
