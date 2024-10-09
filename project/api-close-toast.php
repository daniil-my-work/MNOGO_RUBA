<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получите данные из запроса
    $toastId = $_POST['toastId'] ?? null;

    // Удаляет тост
    deleteToastFromSession($toastId);

    // Удаляет данные из сессии
    if (count($_SESSION['toasts']) === 0) {
        unset($_SESSION['toasts']);
    }
}
