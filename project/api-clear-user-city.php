<?php

require_once('./functions/init.php');

unset($_SESSION['city']);

if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    // Запасной вариант, если HTTP_REFERER не доступен
    header('Location: ./index.php');
}
