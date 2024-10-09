<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./functions/formatter.php');
require_once('./data/data.php');



// Список ролей
$userRole = $appData['userRoles'];

// Проверка прав доступа
// $sessionRole = $_SESSION['user_role'] ?? null;
// $allowedRoles = [$userRole['client']];
// checkAccess($isAuth, $sessionRole, $allowedRoles);

// Список категорий меню
$categoryList = getCategories($con);
// $categoryList = null;

// Cписок адресов кафе
$cafeList = getCafeList($con);
// $cafeList = null;

// Город пользователя
$userCity = getUserCity();
// $userCity = null;


// Получение данных из сессии
$productsData = isset($_SESSION['order']) ? $_SESSION['order'] : array();
// $productsData = [];


// Массив с основой
$noodleComponents = array(
    'noodle_1' => 'лапша Харусаме',
    'noodle_2' => 'Киноа',
    'noodle_3' => 'Рисовая лапша',
    'noodle_4' => 'Удон',
    'noodle_5' => 'Яичная лапша',
    'noodle_6' => 'Рис на пару',
    'noodle_7' => 'Гречневая лапша',
);

// Массив с соусами
$sauceComponents = array(
    'sauce_1' => 'Соус Перечный',
    'sauce_2' => 'Соус Кисло-сладкий (острый)',
    'sauce_3' => 'Соус Устричный',
    'sauce_4' => 'Соус Сливочно-перечный',
    'sauce_5' => 'Соус Сливочный',
    'sauce_6' => 'Соус Терияки',
    'sauce_7' => 'БЕЗ СОУСА',
    'sauce_8' => 'Соус Карри',
    'sauce_9' => 'Соус Кимчи (острый)',
);


// Cписок выбранных блюд 
$productList = getProductListInBasket($con, $productsData);
$productLength = count($productList);

// print_r($_SESSION['order']);
// unset($_SESSION['order']);
// print_r($productList);

// Инициализируем переменную для хранения общей стоимости
$fullPrice = 0;

// Подсчитываем общую стоимость товаров в корзине
foreach ($productList as $product) {
    print_r($product);
    // $fullPrice += $product['item']['price'] 
    $fullPrice = 1 * $product['quantity']['quantity'];
}


$page_body = include_template(
    'basket.php',
    [
        'productsData' => $productsData,
        'products' => $productList,
        'productLength' => $productLength,
        'fullPrice' => $fullPrice,
        'cafeList' => $cafeList,
        'address' => [],
        'userCity' => $userCity,
        'noodleComponents' => $noodleComponents,
        'sauceComponents' => $sauceComponents,
    ]
);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Генерируем уникальный идентификатор
    do {
        $order_id = uniqid();
        // Проверяем, существует ли уже такой идентификатор в базе данных
    } while (!checkUniquenessValue($con, $order_id, 'orders', 'order_id'));


    // Получает айди пользователя, совершившего заказ
    $userEmail = $_SESSION['user_email'] ?? null;
    $userId = null;

    if (!is_null($userEmail)) {
        $sql = "SELECT user.id FROM user WHERE user.user_email = '$userEmail'";
        $result = mysqli_query($con, $sql);
        $userId = get_arrow($result)['id'] ?? null;
    }

    // Формируем объект заказа
    $order = [
        'customer_id' => $userId,
        'total_amount' => $fullPrice,
        'order_id' => $order_id
    ];

    // Определяем обязательные поля в зависимости от типа доставки
    $required = ($_POST['delivery-type'] === 'delivery') ? ['user_address', 'entrance', 'apartment', 'floor'] : ['cafe_address'];

    // Создаем массив с фильтрами по умолчанию
    $filters = array_merge(['delivery-type' => FILTER_SANITIZE_SPECIAL_CHARS], array_fill_keys($required, FILTER_DEFAULT));

    // Добавляем поле order_comment-user или order_comment-cafe в массив фильтров
    if ($_POST['delivery-type'] === 'delivery') {
        $filters['order_comment-user'] = FILTER_DEFAULT;
    } else {
        $filters['order_comment-cafe'] = FILTER_DEFAULT;
    }

    // Garnir
    if (isset($_POST['garnir']) ? $_POST['garnir'] : null) {
        $filters['garnir'] = FILTER_DEFAULT;
    }

    // Получаем данные из формы с применением фильтров
    $address = filter_input_array(INPUT_POST, $filters, true);

    // Ошибки
    $errors = [];

    // Валидация полей
    $rules = [
        'is_number' => function ($value) {
            return is_numeric($value) ? '' : 'Укажите число';
        },
        'is_text' => function ($value) {
            return is_string($value) ? '' : 'Укажите число';
        },
        'delivery-type' => function ($value) {
            return in_array($value, ['pickup', 'delivery'], true) ? '' : 'Неверный тип доставки';
        },
        'cafe_address' => function ($value) {
            return $value !== 'default' ? '' : 'Укажите адресс кафе';
        },
        'user_address' => function ($value) {
            return $value === (isset($_SESSION['userAddress']) ? $_SESSION['userAddress'] : '') ? '' : 'Выберите адресс из предложенных вариантов';
        }
    ];

    // Заполняет массив с ошибками
    foreach ($address as $key => $value) {
        // Проверка на незаполненное поле
        if (in_array($key, $required) && empty($value)) {
            $field = $fieldDescriptions[$key] ?? '';

            $errors[$key] = 'Поле ' . $field . ' должно быть заполнено.';
        }

        if ($key == 'user_address') {
            $rule = $rules['user_address'];
            $errors['user_address'] = $rule($value);
            continue;
        }

        if ($key == 'cafe_address') {
            $rule = $rules['cafe_address'];
            $errors['cafe_address'] = $rule($value);
            continue;
        }

        if ($key == 'entrance' || $key == 'apartment' || $key == 'floor') {
            $rule = $rules['is_number'];
            $errors[$key] = $rule($value);
            continue;
        }
    }

    $errors = array_filter($errors);


    if (!empty($errors)) {
        $page_body = include_template(
            'basket.php',
            [
                'productsData' => $productsData,
                'products' => $productList,
                'productLength' => $productLength,
                'fullPrice' => $fullPrice,
                'cafeList' => $cafeList,
                'address' => $address,
                'errors' => $errors,
                'userCity' => $userCity,
                'noodleComponents' => $noodleComponents,
                'sauceComponents' => $sauceComponents,
            ]
        );
    } else {
        // Формирует адрес заказа
        if ($address['delivery-type'] === 'delivery') {
            // Формирует адресс заказа
            $order['order_address'] = "{$address['user_address']}, кв. {$address['apartment']}, подъезд {$address['entrance']}, этаж {$address['floor']}";

            // Добавляет гарнир для вока
            $order['order_comment'] = $address['garnir'] ? ("Гарнир для вока:" . $address['garnir']) : '';

            // Добавляет комментарий в объект для записи в БД
            $order['order_comment'] = $address['order_comment-user'];
        } else {
            $sql = "SELECT address_name FROM cafe_address WHERE cafe_address.id = '{$address['cafe_address']}'";
            $result = mysqli_query($con, $sql);
            $address_name = get_arrow($result)['address_name'] ?? '';

            // Формирует адресс заказа
            $order['order_address'] = $address_name;

            // Добавляет гарнир для вока
            $order['order_comment'] = $address['garnir'] ? ("Гарнир для вока:" . $address['garnir']) : '';

            // Добавляет комментарий в объект для записи в БД
            $order['order_comment'] = $address['order_comment-cafe'];
        }

        // Добавляет запись в базу с заказами 
        $insertOrderNum = createNewOrder($con, $order);

        // SQL код для добавление записи в базу с состовляющими заказа 
        $createNewOrderItem = get_query_create_order_item();

        // Флаг успешности всех операций
        $allOperationsSuccessful = true;

        // Записывает в базу товары лежащие в корзине
        foreach ($productList as $product) {
            // Получаем данные о продукте и количестве
            $productItem = $product['item'];
            $quantity = $product['quantity'];

            // Формирует объект для отправки
            $data = array(
                "product_id" => $productItem['id'],
                "quantity" => $quantity,
                "unit_price" => $productItem['price'],
                "tableName" => $product['table'],
                "order_id" => $order_id,
            );

            // Подготавливаем и выполняем запрос
            $stmt = db_get_prepare_stmt($con, $createNewOrderItem, $data);
            $res = mysqli_stmt_execute($stmt);

            // Если хотя бы одна операция не выполнена успешно, изменяем флаг
            if (!$res) {
                $allOperationsSuccessful = false;
                break; // Прерываем цикл, так как дальнейшие операции не нужны
            }
        }


        if ($allOperationsSuccessful) {
            // Получает айди оформленного заказа
            $orderId = getOrderId($con, $insertOrderNum);

            if (!$orderId) {
                return;
            }

            echo "Запись успешно добавлена в базу данных.";

            // Удаляет данные из сессии и перенапрвляет на страницу аккаунт
            unset($_SESSION['order']);
            unset($_SESSION['userAddress']);

            header("Location: ./order.php?orderId=$order_id&prevLink=basket");
        } else {
            echo "Ошибка при выполнении запроса: " . mysqli_error($con);
            echo "Номер ошибки: " . mysqli_errno($con);
        }
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

// Записывает ошибку в сессию: Не удалось загрузить ...
// $cafeList = null;
if (is_null($cafeList)) {
    $option = ['value' => 'список кафе'];
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

// print($layout_content);
