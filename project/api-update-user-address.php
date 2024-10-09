<?php

require_once('./functions/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $userAddress = $_POST['userAddress'] ?? null;

    $_SESSION['userAddress'] = $userAddress;
}
