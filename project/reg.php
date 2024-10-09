<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./functions/validators.php');
require_once('./data/data.php');


// Список ролей
$userRole = $appData['userRoles'];

// Список категорий меню
$categoryList = getCategories($con);
// $categoryList = null;

$page_body = include_template('reg.php', []);


// Проверка на отправку формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обязательные поля для заполненения 
    $required = ['user_name', 'user_phone', 'user_email', 'user_password'];
    $errors = [];

    // Валидация полей
    $rules = [
        'user_email' => function ($value) {
            return validate_email($value);
        },
        'user_phone' => function ($value) {
            return validate_phone($value);
        },
        'user_password' => function ($value) {
            return validate_length($value, 8, 12);
        }
    ];

    // Получает данные из формы
    $user = filter_input_array(INPUT_POST, ['user_name' => FILTER_DEFAULT, 'user_email' => FILTER_DEFAULT, 'user_phone' => FILTER_DEFAULT, 'user_password' => FILTER_DEFAULT], true);

    // Заполняет массив с ошибками
    foreach ($user as $key => $value) {
        // Проверка на незаполненное поле
        if (in_array($key, $required) && empty($value)) {
            $field = $fieldDescriptions[$key] ?? '';
            $errors[$key] = 'Поле ' . $field . ' должно быть заполнено.';
        }

        // Проверка по самописным правилам
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }


    $errors = array_filter($errors);

    // Проверяет на наличие ошибок
    if (!empty($errors)) {
        $page_body = include_template(
            'reg.php',
            [
                'errors' => $errors,
            ]
        );
    } else {
        // Получаем реальный IP-адрес пользователя, учитывая прокси
        $userIP = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $user['user_ip'] = $userIP;

        // Формирует SQL запрос для добавления записи в таблицу User
        $sql = get_query_create_user();

        // Установка начальной роли пользователя
        $user['user_role'] = 'client';

        // Номера телефонов для Админа или Собственника
        $adminTelephone = $appData['telephones']['admin'];
        $ownerTelephone = $appData['telephones']['owner'];

        // Устанавливает роль: Админ или Собственник
        if (in_array($user['user_phone'], $adminTelephone)) {
            $user['user_role'] = $userRole['admin'];
        } elseif (in_array($user['user_phone'], $ownerTelephone)) {
            $user['user_role'] = $userRole['owner'];
        }

        // Хеширует пароль
        $user['user_password'] = password_hash($user['user_password'], PASSWORD_BCRYPT);

        $stmt = db_get_prepare_stmt($con, $sql, $user);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            // echo "Запись успешно добавлена в базу данных.";

            header("Location: /auth.php");
            exit;
        } else {

            // Модальное окно: Контент для вставки
            $modalList = [
                [
                    'title' => 'Ошибка при регистрации',
                    'error' => 'Ошибка при выполнении запроса',
                    'category' => 'error',
                ],
            ];

            $page_modal = include_template(
                'modal.php',
                [
                    'modalList' => $modalList,
                ]
            );
        }

        mysqli_stmt_close($stmt);
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

print($layout_content);
