<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./functions/formatter.php');
require_once('./data/data.php');


// Список ролей
$userRole = $appData['userRoles'];

// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();

$productsDataMenu = array();
if (isset($productsData['menu'])) {
    $productsDataMenu = $productsData['menu'];
}

// Список категорий меню
$categoryList = getCategories($con);
// $categoryList = null;

// Город пользователя
$userCity = getUserCity();
// $userCity = null;


// Получает активную категорию
$activeCategory = isset($_GET['category']) ? $_GET['category'] : 'rolls';

// Получает данные о выбранной категории
$categoryName = fetchCategoryName($con, $activeCategory);
// $categoryName = null;

// Получает список продуктов по выбранной категории 
$productList = getProductsByCategory($con, $activeCategory);


// ==== Вывод ошибок ====
// Записывает ошибку в сессию: Не удалось загрузить ...
// $categoryList = null;
if (is_null($categoryName)) {
    $option = ['value' => 'название активной категории'];
    $toast = getModalToast(null, $option);

    if (!is_null($toast)) {
        $_SESSION['toasts'][] = $toast;
    }
}

// Записывает ошибку в сессию: Не удалось загрузить ...
// $categoryList = null;
if (is_null($categoryList)) {
    $option = ['value' => 'категорий меню'];
    $toast = getModalToast(null, $option);

    if (!is_null($toast)) {
        $_SESSION['toasts'][] = $toast;
    }
}

// Записывает город в сессию 
// $userCity = null;
if (is_null($userCity)) {
    $toast = getModalToast('city', $optionCity);

    if (!is_null($toast)) {
        $_SESSION['toasts'][] = $toast;
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
    'menu.php',
    [
        'productsData' => $productsDataMenu,
        'products' => $productList,
        'categoryList' => $categoryList,
        'activeCategory' => $activeCategory,
        'categoryName' => $categoryName,
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
