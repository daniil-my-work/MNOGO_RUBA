<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');



// Проверка прав доступа
$sessionRole = $_SESSION['user_role'] ?? null;
$allowedRoles = [$userRole['owner']];
checkAccess($isAuth, $sessionRole, $allowedRoles);

// Список ролей
$userRole = $appData['userRoles'];

// Получает данные о пользователе
$userInfo = getUserInfo($con);

$statisticGroup = isset($_GET['group']) ? $_GET['group'] : 'orders';


// ==== ШАБЛОНЫ ====
$page_head = include_template(
    'head.php',
    [
        'title' => 'poke-room «Много рыбы»',
    ]
);

$page_header = include_template(
    'header.php',
    [
        'isAuth' => $isAuth,
        'userRole' => $userRole,
    ]
);

$page_body = include_template(
    'owner.php',
    [
        'userInfo' => $userInfo,
        'statisticGroup' => $statisticGroup,
    ]
);

$page_footer = include_template(
    'footer.php',
    []
);

$layout_content = include_template(
    'layout.php',
    [
        'head' => $page_head,
        'header' => $page_header,
        'main' => $page_body,
        'footer' => $page_footer,
    ]
);


print($layout_content);
