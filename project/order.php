<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/db.php');
require_once('./functions/models.php');
require_once('./data/data.php');


// Список ролей
$userRole = $appData['userRoles'];

// Список категорий меню
$categoryList = getCategories($con);
// $categoryList = null;

// Город пользователя
$userCity = getUserCity();
// $userCity = null;


// Данные об айди заказа
$orderId = $_GET['orderId'] ?? null;
$nameLink = $_GET['prevLink'] ?? null;

// Упрощение условия с помощью тернарного оператора
// $backLink = ($nameLink === 'account') ? './account.php' : './index.php';

// TODO: сделать динамическую ссылку
switch ($nameLink) {
    case 'account':
        $backLink = './account.php';
        break;
   
    case 'admin':
        $backLink = './admin.php';
        break;
    
    default:
     $backLink = './index.php';
        break;
}


$backLinkName = ($nameLink === 'account') ? 'личный кабинет' : 'на главную';

// Получает данные о наличии заказа 
$sql = get_query_order_info_by_id($orderId);
$result = mysqli_query($con, $sql);

if (is_null($orderId) || mysqli_num_rows($result) === 0) {
    header("Location: ./index.php");
    return;
}

// Получает данные о конкретном заказе по id заказа
$orderInfo = getOrderInfoById($con, $orderId);

if (is_null($orderInfo)) {
    header("Location: $backLink");
    exit;
}


// Данные о товарах в заказе
$orderId = $orderInfo['order_id'];
$orderItems = getOrderItems($con, $orderId);
// $orderItems = [];

$productList = [];
if (is_array($orderItems)) {
    foreach ($orderItems as $orderItem) {
        $productId = $orderItem['product_id'];
        $table = $orderItem['tableName'];

        if ($table == 'menu') {
            $sql = get_query_order_items_from_menu($productId, $orderId);
        } else {
            $sql = get_query_order_items_from_poke($productId, $orderId);
        }

        $result = mysqli_query($con, $sql);
        $newProducts = fetchResultAsArray($result);
        $productList = array_merge($productList, $newProducts);
    }
}


// ==== Вывод ошибок ====
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
    'order.php',
    [
        'orderInfo' => $orderInfo,
        'productList' => $productList,
        'backLink' => $backLink,
        'backLinkName' => $backLinkName,
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
