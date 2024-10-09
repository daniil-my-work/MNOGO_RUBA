<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');

// Получение общей длины объединенного массива
$totalLength = getBasketList();

// Отправка данных о общей длине в формате JSON
header("Content-type: application/json");
echo json_encode(array('totalLength' => $totalLength));
