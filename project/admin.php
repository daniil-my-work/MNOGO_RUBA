<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');


// Проверка прав доступа
$sessionRole = $_SESSION['user_role'] ?? null;

// Список ролей
$userRole = $appData['userRoles'];
$allowedRoles = [$userRole['admin']];
checkAccess($isAuth, $sessionRole, $allowedRoles);

// Список категорий меню
$categoryList = getCategories($con);
// $categoryList = null;

// Получает данные о пользователе
$userInfo = getUserInfo($con);
// $userInfo = null;


// Определяет вкладку
$statisticGroup = isset($_GET['group']) ? $_GET['group'] : 'orders';

// Значение по умолчанию
// -- Вкладка: Поиск заказа
$searchValue = null;
$paginationSearch = null;
$currentPageSearch = null;
$orderListSearch = null;
$keysSearch = null;

// -- Вкладка: Клиенты
$phoneValue = null;
$paginationUser = null;
$currentPageUser = null;
$userListFormatted = null;
$userListLength = null;

// -- Вкладка: Заказы
$dateFirst = null;
$dateSecond = null;
$paginationActive = null;
$paginationСomplete = null;
$currentPageActive = null;
$currentPageСomplete = null;
$orderListActive = null;
$orderListСomplete = null;
$keysActive = null;
$keysСomplete = null;


if ($statisticGroup === 'search') {

    // Получает данные: айди заказа для поиска в базе
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $searchValue = isset($_POST['order-id']) ? $_POST['order-id'] : null;

        // Присвоение данных сессии
        $_SESSION['searchValue'] = $searchValue;
    } elseif (isset($_SESSION['searchValue'])) {
        $searchValue = $_SESSION['searchValue'];
    } else {
        $searchValue = null;
    }

    // Формирует SQL-запрос для поиска заказа по Айди 
    $sql = get_query_search_order_by_id($searchValue);

    // Выполнение запроса и обработка результата
    $groupedItems = getGroupOrderItems($con, $sql);
    // $groupedItems = [];

    // Создание пагинации
    $paginationSearch = generatePagination($groupedItems);

    // Определение текущей страницы
    $currentPageSearch = isset($_GET['pageSearch']) ? $_GET['pageSearch'] : 1;

    // Вычисление начального индекса для пагинации
    $startIndexSearch = ($currentPageSearch - 1) * MAX_ROW;

    // Получение списка заказов для отрисовки
    $orderListSearch = array_slice($groupedItems, $startIndexSearch, MAX_ROW);

    // Формирование списка ключей для итерации
    $keysSearch = array_keys($orderListSearch);
} elseif ($statisticGroup === 'clients') {

    // Получает данные: номер телефона для поиска в базе
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $phoneValue = isset($_POST['user-phone']) ? $_POST['user-phone'] : null;

        // Присвоение данных сессии
        $_SESSION['phoneValue'] = $phoneValue;
    } elseif (isset($_SESSION['phoneValue'])) {
        $phoneValue = $_SESSION['phoneValue'];
    } else {
        $phoneValue = null;
    }

    // Выполнение запроса и обработка результата
    $usersInfo = getUsersInfo($con, $phoneValue);
    // $usersInfo = null;

    // Список юзеров
    $userList = $usersInfo['list'] ?? [];
    $userListLength = $usersInfo['length'] ?? [];

    // Создание пагинации
    $paginationSearch = generatePagination($userList);

    // Определение текущей страницы
    $currentPageSearch = isset($_GET['pageSearch']) ? $_GET['pageSearch'] : 1;

    // Вычисление начального индекса для пагинации
    $startIndexSearch = ($currentPageSearch - 1) * MAX_ROW;

    // Получение списка заказов для отрисовки
    $userListFormatted = array_slice($userList, $startIndexSearch, MAX_ROW);

    // Формирование списка ключей для итерации
    $keysSearch = array_keys($userListFormatted);
} else {

    // Определение значений $dateFirst и $dateSecond на основе сессии или POST-запроса
    $dateFirst = isset($_SESSION['orderTime']['start']) ? $_SESSION['orderTime']['start'] : (isset($_POST['date-first']) ? $_POST['date-first'] : null);
    $dateSecond = isset($_SESSION['orderTime']['end']) ? $_SESSION['orderTime']['end'] : (isset($_POST['date-second']) ? $_POST['date-second'] : null);

    // Формирование SQL запроса
    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title 
        FROM orders 
        LEFT JOIN order_items ON orders.order_id = order_items.order_id 
        LEFT JOIN menu ON order_items.product_id = menu.id";

    // Добавление условия по дате, если она задана
    if ($dateFirst && $dateSecond) {
        $sql .= " WHERE orders.order_date BETWEEN '$dateFirst 00:00:00' AND '$dateSecond 23:59:59'";
    }

    $sql .= " ORDER BY orders.id DESC;";

    // Получает все записи из таблицы Состовляющие заказа и группирует их по айди 
    $groupedItems = getGroupOrderItems($con, $sql);
    // $groupedItems = [];

    // Создаем массив для хранения активных и завершенных заказов
    $filteredList = ['active' => [], 'complete' => []];

    // Размещаем заказы в соответствующих разделах массива
    foreach ($groupedItems as $orderId => $orderItems) {
        foreach ($orderItems as $item) {
            // Проверяем, завершен ли заказ
            if ($item['date_end'] != null) {
                $filteredList['complete'][$orderId][] = $item;
            } else {
                $filteredList['active'][$orderId][] = $item;
            }
        }
    }

    // Создание пагинации
    $paginationActive = generatePagination($filteredList['active']);
    $paginationСomplete = generatePagination($filteredList['complete']);

    // Текущая страница для таблиц
    $currentPageActive = isset($_GET['pageActive']) ? $_GET['pageActive'] : 1;
    $currentPageСomplete = isset($_GET['pageСomplete']) ? $_GET['pageСomplete'] : 1;

    // Вычисляем начальный индекс для активных заказов
    $startIndexActive = ($currentPageActive - 1) * MAX_ROW;

    // Вычисляем начальный индекс для завершенных заказов
    $startIndexСomplete = ($currentPageСomplete - 1) * MAX_ROW;

    // Получает список заказов пользователя для отрисовки
    $orderListActive = array_slice($filteredList['active'], $startIndexActive, MAX_ROW);
    $orderListСomplete = array_slice($filteredList['complete'], $startIndexСomplete, MAX_ROW);

    // Формирует список ключей для итерации 
    $keysActive = array_keys($orderListActive);
    $keysСomplete = array_keys($orderListСomplete);
}


// ==== Вывод ошибок ====
// Записывает ошибку в сессию: Не удалось загрузить ...
// $userInfo = null;
if (is_null($userInfo)) {
    $option = ['value' => 'данные пользователя'];
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
    'admin.php',
    [
        'userInfo' => $userInfo,
        'statisticGroup' => $statisticGroup,
        // Поиск
        'searchValue' => $searchValue,
        'paginationSearch' => $paginationSearch,
        'currentPageSearch' => $currentPageSearch,
        'orderListSearch' => $orderListSearch,
        'keysSearch' => $keysSearch,
        // Заказы
        'dateFirst' => $dateFirst,
        'dateSecond' => $dateSecond,
        'paginationActive' => $paginationActive,
        'paginationСomplete' => $paginationСomplete,
        'currentPageActive' => $currentPageActive,
        'currentPageСomplete' => $currentPageСomplete,
        'orderListActive' => $orderListActive,
        'orderListСomplete' => $orderListСomplete,
        'keysActive' => $keysActive,
        'keysСomplete' => $keysСomplete,
        // Поиск
        'phoneValue' => $phoneValue,
        'paginationUser' => $paginationUser,
        'currentPageUser' => $currentPageUser,
        'userListFormatted' => $userListFormatted,
        'userListLength' => $userListLength,
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
