<?php

require_once('./functions/init.php');
require_once('./functions/db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $orderId = $_POST['orderId'] ?? null;
    $status = $_POST['status'] ?? null;
    
    // Обновляет статус заказа активный / завершенный
    updateOrderStatus($con, $orderId, $status);
}