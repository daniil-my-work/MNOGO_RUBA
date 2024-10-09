<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');


// Определяет вкладку
$tabGroup = isset($_GET['tabGroup']) ? trim($_GET['tabGroup']) : 'menu';

// Получает данные для скачивания файла 
$queryConfig = $appData['queries'];

// Получение SQL запроса и названий колонок для заданного типа данных
$sql = $queryConfig[$tabGroup]['query'];
$columns = array_values($queryConfig[$tabGroup]['columns']);

// Получение данных из базы данных
$data = fetchDataFromDb($con, $sql);

// Создание CSV файла
$filename = "{$tabGroup}.csv";
createCsvFile($filename, $data, $columns);

// Устанавливаем заголовки для скачивания файла
header('Content-Encoding: UTF-8');
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($filename));

// Читаем содержимое файла и передаем его клиенту
readfile($filename);

// Удаляем временный файл
unlink($filename);
