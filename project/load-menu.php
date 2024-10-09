<?php

require_once('./functions/init.php');
require_once('./functions/helpers.php');
require_once('./functions/models.php');
require_once('./functions/db.php');
require_once('./data/data.php');



// Список ролей
$userRole = $appData['userRoles'];

// Список категорий меню
$categoryList = getCategories($con);
// $categoryList = null;

// Определяет вкладку
$tabGroup = $_GET['tabGroup'] ?? 'menu';
$activeGroup = $tabGroup === 'menu' ? 'меню' : 'поке';

$page_modal = null;

$page_body = include_template('load-menu.php', [
    'tabGroup' => $tabGroup,
    'activeGroup' => $activeGroup,
]);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $uploadDir = './uploads/';
    $validExtensions = ['csv'];
    $columnName = $tabGroup === 'menu' ? array_values($columnNameMenu) : array_values($columnNamePoke);
    $tableName = $tabGroup === 'menu' ? 'menu' : 'components';
    $expectedColumns = implode(',', $columnName);

    // Проверяет загруженный файл
    $uploadResult = handleFileUpload('file', $uploadDir, $validExtensions);

    // Проверяем наличие ошибок
    if ($uploadResult['error']) {
        $errors['file'] = $uploadResult['error'];
    } else {
        $uploadedFileName = $uploadResult['fileName'];
        $importResult = importCsvData($con, $uploadDir . $uploadedFileName, $expectedColumns, $tableName);

        if ($importResult['error']) {
            $errors['file'] = $importResult['error'];
        }
    }

    if (!empty($errors['file'])) {
        $page_body = include_template(
            'load-menu.php',
            [
                'errors' => $errors,
                'tabGroup' => $tabGroup,
                'activeGroup' => $activeGroup,
            ]
        );
    } else {
        // Записывает сообщение в сессию:
        $option = [
            'title' => "Таблица {$activeGroup} обновлена",
            'text' => "Записи в таблице {$activeGroup} были успешно добавлены или обновлены."
        ];

        $toast = getModalToast('message', $option);

        if (!is_null($toast)) {
            $_SESSION['toasts'][] = $toast;
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
