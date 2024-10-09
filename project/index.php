<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');


// Список ролей
$userRole = $appData['userRoles'];

// Получение данных из сессии о добавленных в корзину блюд
$productsDataMenu = $_SESSION['order']['menu'] ?? [];

// unset($_SESSION['order']);
// print_r($_SESSION['user_role']);

// Получает список блюд
$productList = getProductList($con);
// $productList = null;

// Список категорий меню
$categoryList = getCategories($con);
// $categoryList = null;

// Город пользователя
$userCity = getUserCity();
// $userCity = null;


// ==== Вывод ошибок ====
// Записывает ошибку в сессию: Не загрузился список продуктов 
// $productList = null;
if (is_null($productList)) {
    $option = ['value' => 'меню'];
    $toast = getModalToast(null, $option);

    if (!is_null($toast)) {
        if (!is_null($toast)) {
            $_SESSION['toasts'][] = $toast;
        }
    }
}

// Записывает ошибку в сессию: Не загрузились категории меню 
// $categoryList = null;
if (is_null($categoryList)) {
    $option = ['value' => 'категорий меню'];
    $toast = getModalToast(null, $option);

    if (!is_null($toast)) {
        if (!is_null($toast)) {
            $_SESSION['toasts'][] = $toast;
        }
    }
}

// Записывает город в сессию 
// $userCity = null;
if (is_null($userCity)) {
    $toast = getModalToast('city', $optionCity);

    if (!is_null($toast)) {
        if (!is_null($toast)) {
            $_SESSION['toasts'][] = $toast;
        }
    }
}

// Модальное окно со списком ошибок
$modalList = $_SESSION['toasts'] ?? [];
// print_r($_SESSION);


// ==== ШАБЛОНЫ ====
$page_modal = include_template(
    'modal.php',
    [
        'modalList' => $modalList,
    ]
);

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
    'main.php',
    [
        'productsData' => $productsDataMenu,
        'products' => $productList,
    ]
);

$page_footer = include_template(
    'footer.php',
    [
        'categoryList' => $categoryList,
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'head' => $page_head,
        'modal' => $page_modal,
        'header' => $page_header,
        'main' => $page_body,
        'footer' => $page_footer,
    ]
);

print($layout_content);
