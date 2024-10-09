<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $tableName = $_POST['tableName'] ?? null;
    $uniqueId = $_POST['uniqueId'] ?? null;
    $productId = $_POST['productId'] ?? null;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : null;
    $categoryId = $_POST['categoryId'] ?? null;

    // Добавляет блюдо в сессию
    addProductInSession($con, $tableName, $uniqueId, $productId, $quantity, $categoryId);
}
