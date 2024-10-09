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

// Список компонентов Поке
$componentList = getComponentList($con) ?? [];
// $componentList = [];

// Город пользователя
$userCity = getUserCity();
// $userCity = null;

// Кол-во блюд в корзине
$totalLength = getBasketList();


// Извлекаем уникальные значения ключа component_type, только если список не пуст
if (!is_null($componentList)) {
    $uniqueComponentTypes = array_unique(array_column($componentList, 'component_type'));

    // Создаем массив для хранения отфильтрованных компонентов
    $sortComponentList = array_reduce($uniqueComponentTypes, function ($carry, $uniqueType) use ($componentList) {
        // Фильтруем массив $componentList для текущего уникального типа
        $filteredComponents = array_filter($componentList, function ($item) use ($uniqueType) {
            return $item['component_type'] === $uniqueType;
        });

        // Добавляем отфильтрованные компоненты в итоговый массив, используя тип как ключ
        $carry[$uniqueType] = $filteredComponents;

        return $carry;
    }, []);
} else {
    // Если список компонентов пуст, возвращаем пустой массив или другое подходящее значение
    $sortComponentList = [];
}


// Списки компонентов
$proteinList = $sortComponentList['protein'] ?? null;
$baseList = $sortComponentList['base'] ?? null;
$fillerList = $sortComponentList['filler'] ?? null;
$toppingList = $sortComponentList['topping'] ?? null;
$sauceList = $sortComponentList['sauce'] ?? null;
$crunchList = $sortComponentList['crunch'] ?? null;
$proteinAddList = $sortComponentList['protein-add'] ?? null;


// Массив для хранения уникальных компонентов
$uniqueComponentNames = array_reduce($componentList, function ($carry, $item) {
    if (!isset($carry[$item['component_type']])) {
        $carry[$item['component_type']] = $item['component_type'];
    }

    return $carry;
}, []);


$page_body = include_template(
    'constructor-poke.php',
    [
        'proteinList' => $proteinList,
        'proteinAddList' => $proteinAddList,
        'baseList' => $baseList,
        'fillerList' => $fillerList,
        'toppingList' => $toppingList,
        'sauceList' => $sauceList,
        'crunchList' => $crunchList,
        'totalLength' => $totalLength,
    ]
);



if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // Обязательные поля для заполненения 
    $required = ['shema', 'protein', 'base', 'filler', 'topping', 'sauce', 'crunch', 'total-price'];
    $errors = [];

    $rules = [
        'component' => fn ($key, $value) => validate_component($con, $key, $value),
        'shema' => fn ($value) => !in_array($value, [1, 2]) ? 'Указана неверная схема для наполнителя и топпинга' : null,
        'total-price' => fn ($value) => $value < 0 ? 'Вы ничего не выбрали' : null,
        'component-length' => fn ($name, $value, $shema) => validate_component_length($name, $value, $shema),
    ];

    $createdPoke = filter_input_array(INPUT_POST, ['protein' => FILTER_DEFAULT, 'base' => FILTER_DEFAULT, 'shema' => FILTER_DEFAULT, 'filler' => FILTER_DEFAULT, 'topping' => FILTER_DEFAULT, 'sauce' => FILTER_DEFAULT, 'crunch' => FILTER_DEFAULT, 'total-price' => FILTER_DEFAULT, 'proteinAdd' => FILTER_DEFAULT, 'fillerAdd' => FILTER_DEFAULT, 'toppingAdd' => FILTER_DEFAULT, 'sauceAdd' => FILTER_DEFAULT, 'crunchAdd' => FILTER_DEFAULT], true);

    // Список полей содеражащих массив
    $inputList = ['filler', 'topping', 'fillerAdd', 'toppingAdd'];


    // var_dump($createdPoke['protein']);

    // Форматирует поля в объекте $createdPoke
    foreach ($createdPoke as $key => $value) {
        if (in_array($key, $inputList)) {
            $createdPokeItem = $_POST[$key] ?? null;

            // Если значение отсутствует, удаляем поле из $createdPoke
            if (is_null($createdPokeItem) && ($key == 'fillerAdd' || $key == 'toppingAdd')) {
                unset($createdPoke[$key]);
            } else if (is_null($createdPokeItem) && ($key == 'filler' || $key == 'topping')) {
                $createdPoke[$key] = [];
            } else {
                // Иначе, заменяем значение в $createdPoke на значение из $_POST
                $createdPoke[$key] = $createdPokeItem;
            }
        } else {
            // Если значение пустое, удаляем поле из $createdPoke
            if ($value === '' && $key !== 'protein' && $key !== 'base' && $key !== 'sauce' && $key !== 'crunch') {
                unset($createdPoke[$key]);
            }
        }
    }

    // Проверка на валидность полей формы 
    foreach ($createdPoke as $key => $value) {
        if (in_array($key, $required) && empty($value)) {
            $fieldName = $uniqueComponentNames[$key] ?? null;

            if ($fieldName) {
                $errors[$key] = "Поле . $fieldName . должно быть заполено";
            }
        }

        switch ($key) {
            case 'shema':
                $errors['shema'] = $rules['shema']((int)$value);
                break;

            case 'filler':
            case 'topping':
                $errors[$key] = $rules['component-length']($key, $value, $createdPoke['shema'] ?? null);
                if (!$errors[$key]) {
                    $errors[$key] = $rules['component']($key, $value);
                }
                break;

            case 'total-price':
                $errors['total-price'] = $rules['total-price']($value);
                break;

            case 'proteinAdd':
            case 'fillerAdd':
            case 'toppingAdd':
            case 'sauceAdd':
            case 'crunchAdd':
                if ($value !== '') {
                    $newKey = ($key === 'proteinAdd') ? 'protein-add' : (($key === 'fillerAdd') ? 'filler' : (($key === 'toppingAdd') ? 'topping' : (($key === 'sauceAdd') ? 'sauce' : 'crunch')));
                    $errors[$key] = $rules['component']($newKey, $value);
                }
                break;

            default:
                $errors[$key] = $rules['component']($key, $value);
                break;
        }
    }

    // Фильтрует массив ошибок
    $errors = array_filter($errors);

    // Проверяет на наличие ошибок
    if (!empty($errors)) {
        $page_body = include_template(
            'constructor-poke.php',
            [
                'proteinList' => $proteinList,
                'proteinAddList' => $proteinAddList,
                'baseList' => $baseList,
                'fillerList' => $fillerList,
                'toppingList' => $toppingList,
                'sauceList' => $sauceList,
                'crunchList' => $crunchList,
                'errors' => $errors,
                'createdPoke' => $createdPoke,
            ]
        );
    } else {
        // Генерируем уникальный идентификатор
        do {
            $order_id = uniqid();
            // Проверяем, существует ли уже такой идентификатор в базе данных
        } while (!checkUniquenessValue($con, $order_id, 'poke', 'poke_id'));


        // Получает название Поке
        $pokeTitle = get_poke_title($con, $createdPoke['protein']);

        // Получает данные из таблицы Категория
        $categoryInfo = getCategoryInfo($con, 'constructor-poke');

        // Словарь для конструктора Поке
        $pokeDictionary = array();

        // Исключаем лишние поля
        $excludeFields = ['shema', 'fillerAdd', 'toppingAdd', 'proteinAdd', 'sauceAdd', 'crunchAdd', 'total-price'];

        // Заполняет словарь данными из каких компонентов собрано Поке 
        foreach ($createdPoke as $key => $value) {
            // Пропускает лишние поля
            if (in_array($key, $excludeFields)) {
                continue;
            }

            if (in_array($key, ['filler', 'topping'])) {
                $keyAdd = $key . 'Add';

                // Объединяет массивы со схожим типом filler => fillerAdd
                $combineArray = isset($createdPoke[$keyAdd]) ? array_merge($createdPoke[$key], $createdPoke[$keyAdd]) : $createdPoke[$key];

                // Добавляет данные в словарь
                $pokeDictionary[$key] = array_count_values($combineArray);
            } elseif (in_array($key, ['protein', 'sauce', 'crunch'])) {
                $keyAdd = $key . 'Add';

                // Объединяет массивы со схожим типом protein => proteinAdd
                $combineArray = isset($createdPoke[$keyAdd]) ? array_merge([$createdPoke[$key]], [$createdPoke[$keyAdd]]) : [$createdPoke[$key]];

                // Добавляет данные в словарь
                $pokeDictionary[$key] = array_count_values($combineArray);
            } else {
                // Добавляет данные в словарь
                $pokeDictionary[$key] = array_count_values([$value]);
            }
        }

        // Формирует начальную часть описания
        $pokeDescription = "Ваш Поке состоит из: ";

        // Перебираем каждый компонент в словаре
        foreach ($pokeDictionary as $component => $details) {
            foreach ($details as $item => $count) {
                // Получаем название компонента из базы данных
                $title = getComponentTitle($con, $item);

                // Добавляем детали компонента (название и количество)
                $pokeDescription .= "$title ($count), ";
            }
        }

        // Убираем последнюю запятую и добавляем точку только в конце всего описания
        $pokeDescription = rtrim($pokeDescription, ", ") . ".";

        // Формирует объект для записи в таблицу Poke
        $poke = array(
            'title' => $pokeTitle,
            'img' => './poke-my-1.jpg',
            'description' => $pokeDescription,
            'price' => $createdPoke['total-price'],
            'cooking_time' => 40,
            'category_id' => $categoryInfo['id'],
            'poke_id' => $order_id
        );

        // Добавляет запись в таблицу Poke и получает id вставленного записи 
        $insertId = insertPokeInDb($con, $poke);

        // Подготовка запроса для таблицы Poke contains
        $getPokeContains = get_query_create_poke_contains();
        $stmt = mysqli_prepare($con, $getPokeContains);

        // Проходим по словарю и добавляем данные в таблицу Poke contains
        foreach ($pokeDictionary as $pokeDictionaryItem) {
            foreach ($pokeDictionaryItem as $component_id => $quantity) {
                // Привязка параметров для подготовленного запроса
                mysqli_stmt_bind_param($stmt, 'iii', $order_id, $component_id, $quantity);
                // Выполнение подготовленного запроса
                $res = mysqli_stmt_execute($stmt);
            }
        }

        // Закрытие подготовленного запроса
        mysqli_stmt_close($stmt);


        if (!is_null($insertId)) {
            $tableName = 'poke';
            $quantity = 1;

            // Добавляет поке в сессию: показывается в Корзине
            addProductInSession($con, $tableName, $insertId, $quantity);
        } else {
            echo 'Заказ незавершен в базу';
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
// $componentList = [];
if (empty($componentList)) {
    $option = ['value' => 'список компонентов'];
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
        'title' => 'Конструктор «Много рыбы»',
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
