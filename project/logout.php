<?php

require_once('./functions/init.php');

// Очищает сессию
$_SESSION = [];

// Пользователь не авторизован
$isAuth = null;

// Перенаправление на страницу входа
header("Location: ./index.php");
exit;