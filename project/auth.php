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

$page_body = include_template(
    'auth.php',
    []
);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обязательные поля для заполненения 
    $required = ['user_email', 'user_password'];
    $errors = [];

    $rules = [
        'user_email' => function ($value) {
            return validate_email($value);
        },
        'user_password' => function ($value) {
            return validate_length($value, 8, 12);
        }
    ];

    // Получает данные из формы
    $user = filter_input_array(INPUT_POST, ['user_email' => FILTER_DEFAULT, 'user_password' => FILTER_DEFAULT], true);

    // Заполняет массив с ошибками
    foreach ($user as $key => $value) {
        if (in_array($key, $required) && empty($value)) {
            $fieldName = $fieldDescriptions[$key];
            $errors[$key] = 'Поле ' . $fieldName . ' должно быть заполнено.';
        }

        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }
    }

    $errors = array_filter($errors);


    // Проверяет на наличие ошибок
    if (!empty($errors)) {
        $page_body = include_template(
            'auth.php',
            [
                'errors' => $errors,
                'user' => $user,
            ]
        );
    } else {
        // Формирует sql запрос для проверки наличия юзера в таблицу User
        $sql = get_query_user_auth($user['user_email']);
        $result = mysqli_query($con, $sql);

        if (!$result || mysqli_num_rows($result) == 0) {
            $errors['user_email'] = 'Вы ввели неправильный email';

            $page_body = include_template(
                'auth.php',
                [
                    'errors' => $errors,
                    'user' => $user,
                ]
            );
        } else {
            $userInfo = get_arrow($result);

            // Проверка совпадения пароля
            $isValidPassword = password_verify($user['user_password'], $userInfo['user_password']);

            if (!$isValidPassword) {
                $errors['user_password'] = 'Вы ввели неправильный пароль';

                $page_body = include_template(
                    'auth.php',
                    [
                        'errors' => $errors,
                        'user' => $user,
                    ]
                );
            } else {
                // Добавление данных в сессию
                $_SESSION['user_id'] = $userInfo['id'];
                $_SESSION['user_name'] = $userInfo['user_name'];
                $_SESSION['user_email'] = $userInfo['user_email'];
                $_SESSION['user_role'] = $userInfo['user_role'];

                header("Location: /index.php");
                exit;
            };
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
