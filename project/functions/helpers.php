<?php

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Проверяет уникальность значения в указанной таблице базы данных.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param int $order_id Идентификатор заказа, значение которого требуется проверить на уникальность.
 * @param string $table Имя таблицы, в которой требуется проверить уникальность значения.
 * @param string $value Название столбца, значение которого требуется проверить на уникальность.
 *
 * @return bool Возвращает true, если значение уникально, и false, если значение не уникально.
 */
function checkUniquenessValue($con, $order_id, $table, $value)
{
    // SQl код для проверки уникальности идентификатора заказа
    $sql = "SELECT COUNT(*) AS count FROM $table WHERE $value = '$order_id'";

    $result = mysqli_query($con, $sql);
    $idList = get_arrow($result);
    $count = $idList['count'];

    return $count == 0;
}

/**
 * Добавляет информацию о выбранном продукте в сессию корзины.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param string $tableName Имя таблицы, куда добавляется продукт.
 * @param int $productId Идентификатор продукта.
 * @param int $quantity Количество продукта.
 *
 * @return void
 */
function addProductInSession($con, $tableName, $uniqueId, $productId, $quantity, $categoryId)
{
    // Инициализируйте или обновите данные корзины в сессии
    if (!isset($_SESSION['order'][$tableName])) {
        $_SESSION['order'][$tableName] = array();
    }

    // Добавьте товар в корзину
    // if (isset($_SESSION['order'][$tableName][$productId])) {
    // Удаление конкретного элемента из сессии
    if ($quantity <= 0) {
        if ($tableName == 'poke') {
            $sql = get_query_poke_unique_id($productId);
            $result = mysqli_query($con, $sql);
            $pokeUniqueId = get_arrow($result);
            $pokeUniqueId = $pokeUniqueId['poke_id'];

            $deletePoke = get_query_delete_poke($productId);
            $resultDeletePoke = mysqli_query($con, $deletePoke);

            $deletePokeConsists = get_query_delete_poke_consists($pokeUniqueId);
            $resultDeletePokeConsists = mysqli_query($con, $deletePokeConsists);

            if ($resultDeletePoke && $resultDeletePokeConsists) {
                // Успешно удалено, теперь удаляем из сессии
                unset($_SESSION['order'][$tableName][$productId]);
            } else {
                // Обработка ошибки, если удаление не удалось
                echo "Ошибка при удалении записей из базы данных.";
            }

            return;
        }

        unset($_SESSION['order'][$tableName][$productId]);
        return;
    }

    // if ($categoryId == '4') {

    // }

    // TODO: Создается новый элемент а не обновляется старый

    if (isset($_SESSION['order'][$tableName][$uniqueId])) {
        $_SESSION['order'][$tableName][$uniqueId]['quantity'] = $quantity;
    } else {
        $uniqueId = uniqid();

        $_SESSION['order'][$tableName][] = array(
            'uniqueId' => $uniqueId,
            'productId' => $productId,
            'quantity' => $quantity,
            'categoryId' => $categoryId
        );
    }
}

// [$productId] = 1;
// Array ( [307] => 2 [308] => 3 [309] => 2 ) 

// New
// [0] => [$uniqueId, $productId, $quantity, $categoryId];
// Array ( [0] => [dsdsnj1, 307, 2, 2])


/**
 * Возвращает список заказов, сгруппированных по идентификатору заказа.
 *
 * @param object $con mysqli Объект подключения к базе данных.
 * @param string $sql SQL-запрос для получения списка заказов.
 *
 * @return array Массив, содержащий список заказов, сгруппированных по идентификатору заказа.
 */
function getGroupOrderItems($con, $sql)
{
    $result = mysqli_query($con, $sql);

    if ($result === false) {
        // Обработка ошибки выполнения запроса
        echo "Ошибка выполнения запроса: " . mysqli_error($con);
        return [];
    } else {
        $groupedItems = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $orderId = $row['order_id'];
            $groupedItems[$orderId][] = $row;
        }

        return $groupedItems;
    }
}

/**
 * Извлекает результат запроса в виде массива.
 *
 * @param object $result Результат запроса mysqli.
 *
 * @return array Возвращает массив данных из результата запроса.
 */
function fetchResultAsArray($result)
{
    $data = [];

    if ($result !== false) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}

/**
 * Генерирует пагинацию на основе сгруппированных элементов.
 *
 * @param array $groupedItems Массив, содержащий сгруппированные элементы.
 *
 * @return array Возвращает массив страниц пагинации.
 */
function generatePagination($groupedItems)
{
    $paginationLength = ceil(count($groupedItems) / MAX_ROW);

    // Создаем массив чисел от 1 до $paginationLength
    return $paginationLength > 1 ? range(1, $paginationLength) : [];
}

/**
 * Создает CSV файл и записывает в него данные. Функция открывает файл для записи, добавляет BOM для поддержки UTF-8,
 * записывает заголовки столбцов и последовательно добавляет данные из массива.
 *
 * @param string $filename Путь к файлу, который будет создан или перезаписан.
 * @param array $data Массив массивов, содержащий строки данных, которые будут записаны в CSV файл.
 * @param array $columns Массив, содержащий заголовки столбцов для CSV файла.
 * @return void Функция не возвращает значение.
 */
function createCsvFile($filename, $data, $columns)
{
    $fp = fopen($filename, 'w');
    fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Добавляем BOM для UTF-8

    // Добавляет хедер
    fputcsv($fp, $columns);

    foreach ($data as $row) {
        fputcsv($fp, $row);
    }

    fclose($fp);
}

/**
 * Извлекает данные из базы данных по заданному SQL запросу. Функция выполняет SQL запрос и возвращает результаты 
 * в виде массива ассоциативных массивов, где каждый ассоциативный массив представляет одну строку результата запроса.
 *
 * @param mysqli $con Объект соединения с базой данных, через который выполняется запрос.
 * @param string $sql Строка SQL запроса, который будет выполнен для извлечения данных.
 * @return array Массив ассоциативных массивов, содержащий результаты запроса. Каждый элемент массива 
 * представляет собой строку из базы данных в виде ассоциативного массива.
 */
function fetchDataFromDb($con, $sql)
{
    $result = mysqli_query($con, $sql);
    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

/**
 * Проверяет авторизацию пользователя и его роль.
 *
 * @param bool $isAuth Флаг, указывающий на состояние авторизации пользователя.
 * @param string $currentRole Текущая роль пользователя в сессии.
 * @param array $allowedRoles Массив допустимых ролей для доступа к ресурсу.
 * @param string $redirectUrl URL для перенаправления в случае неудачной проверки.
 */
function checkAccess($isAuth, $currentRole, $allowedRoles, $redirectUrl = './auth.php')
{
    // Проверка авторизации и наличия роли в списке разрешенных
    if (!$isAuth || !in_array($currentRole, $allowedRoles)) {
        // Если проверка не прошла, перенаправление на страницу аутентификации
        header("Location: $redirectUrl");
        exit;
    }
}

/**
 * Загружает файл, отправленный через форму, в указанную директорию.
 * Проверяет наличие файла, его тип и обрабатывает загрузку.
 *
 * @param string $fieldName Имя поля в форме, через которое был отправлен файл.
 * @param string $uploadDir Директория, куда файл будет загружен.
 * @param array $validExtensions Массив допустимых расширений файла.
 * @return array Ассоциативный массив с ключами 'error' и 'fileName'. 
 *               'error' содержит сообщение об ошибке (если ошибка произошла) и 'fileName' содержит имя файла, если файл был успешно загружен.
 */
function handleFileUpload($fieldName, $uploadDir, $validExtensions)
{
    // Инициализация массива для хранения результатов
    $result = [
        'error' => null,
        'fileName' => null
    ];

    // Проверка наличия файла и ошибок загрузки
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        $result['error'] = 'Файл не был загружен.';

        return $result;
    }

    // Проверка расширения файла
    $fileExtension = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $validExtensions)) {
        $result['error'] = 'Недопустимое расширение файла.';

        return $result;
    }

    // Перемещение файла и обновление имени файла в результате
    $fileName = uniqid("file_", true) . '.' . $fileExtension;
    $uploadFilePath = $uploadDir . $fileName;
    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadFilePath)) {
        $result['error'] = 'Ошибка при сохранении файла.';

        return $result;
    }

    // Обновляем имя файла в результате
    $result['fileName'] = $fileName;

    // Возвращаем результат
    return $result;
}


/**
 * Импортирует данные из CSV файла в указанную таблицу базы данных.
 * Функция открывает файл, проверяет соответствие заголовков ожидаемым, и осуществляет вставку данных в таблицу.
 *
 * @param mysqli $con Соединение с базой данных.
 * @param string $filePath Путь к файлу CSV.
 * @param string $expectedColumns Строка с ожидаемыми заголовками столбцов, разделёнными точкой с запятой.
 * @param string $tableName Имя таблицы в базе данных, куда будут импортироваться данные.
 * @return array Массив с ключом 'error', содержащий сообщение об ошибке, если ошибка произошла, или null, если данные были успешно обработаны.
 *
 * Примечание: функция также удаляет файл после завершения импорта, независимо от того, произошла ошибка или нет.
 */
function importCsvData($con, $filePath, $expectedColumns, $tableName)
{
    // Инициализация массива для хранения результатов
    $result = [
        'error' => null,
    ];

    $file = fopen($filePath, 'r');
    if (!$file) {
        $result['error'] = 'Ошибка при открытии файла.';

        return $result;
    }

    // Получаем первую строку (заголовки столбцов)
    $headersArray = fgetcsv($file, 0, ";");
    if (!$headersArray) {
        fclose($file);
        unlink($filePath);
        $result['error'] = 'Не удалось прочитать заголовки из файла.';

        return $result;
    }

    // Проверяем заголовки и удаляем BOM если он есть
    $headers = array_map(function ($headersArray) {
        return trim(str_replace('"', '', $headersArray), "\xEF\xBB\xBF");
    }, $headersArray);

    $headersString = implode(',', $headers); // Используем точку с запятой в качестве разделителя

    if ($tableName === 'menu') {
        $headersColumn = fgetcsv($file, 0, ";");
    } else {
        $headersColumn = fgetcsv($file, 0, ",");
    }


    if ($headersString !== $expectedColumns) {
        fclose($file);
        unlink($filePath);
        $result['error'] = 'Названия столбцов в файле не соответствуют ожидаемым.';
        return $result;
    }

    // Очистка таблицы перед вставкой новых данных
    clearTable($con, $tableName);

    // Проходится по строкам таблицы
    if ($tableName === 'menu') {
        while (($row = fgetcsv($file, 0, ";")) !== false) {
            if (count($row) == count($headersColumn)) {
                $row[3] = intval($row[3]);

                if (!insertData($con, $tableName, $row)) {
                    $result['error'] = 'Ошибка при вставке данных.';
                    break;
                }
            } else {
                $result['error'] = 'Количество элементов в строке не соответствует количеству столбцов.';
                break;
            }
        }
    } else {
        while (($row = fgetcsv($file, 0, ",")) !== false) {

            if (count($row) == count($headersColumn)) {
                $row[2] = intval($row[2]);

                if (!insertData($con, $tableName, $row)) {
                    $result['error'] = 'Ошибка при вставке данных.';
                    break;
                }
            } else {
                $result['error'] = 'Количество элементов в строке не соответствует количеству столбцов.';
                break;
            }
        }
    }

    // Закрытие файла после завершения чтения
    fclose($file);

    // Проверка существования файла перед удалением
    if (file_exists($filePath)) {
        if (!unlink($filePath)) {
            $result['error'] = 'Не удалось удалить файл после обработки.';
        }
    } else {
        $result['error'] = 'Файл не существует после обработки.';
    }

    return $result;
}



// Модальное окно: Контент для вставки
// $modalList = [
//     [
//         'id' => '',
//         'title' => 'Выберите ваш город',
//         'button' => [
//             ['text' => 'Ярославль', 'class' => 'btn btn-primary btn-sm'],
//             ['text' => 'Рыбинск', 'class' => 'btn btn-secondary btn-sm']
//         ],
//         'category' => 'city',
//     ],
//     [
// 'id' => '',
//         'title' => 'Заголовок ошибки',
//         'text' => 'Текст ошибки',
//         'category' => 'error',
//     ],
//     [
// 'id' => '',
//         'title' => 'Зарегистрируетесь, чтобы получить бонусы',
//         'link' => [
//             'linkTitle' => 'Создать личный кабинет',
//             'address' => './dsdsds',
//         ],
//         'category' => 'link',
//     ],
// ];


/**
 * Генерирует объект сообщения для модального окна на основе указанной категории.
 * Функция поддерживает создание различных типов сообщений в модальном окне, включая ссылки, выбор города и сообщения об ошибках.
 *
 * @param string $category Категория сообщения, которая определяет тип возвращаемого объекта ('city', 'link', или по умолчанию для ошибок).
 * @param array $options Массив параметров, необходимых для создания сообщения. В зависимости от категории это могут быть названия, адреса ссылок или описания ошибок.
 * @return array Массив, представляющий модальное сообщение соответствующего типа. Возвращает null, если категория не распознана.
 */
function getModalToast($category, $options)
{
    $id = uniqid();

    switch ($category) {
        case 'city':
            if (isset($_SESSION['toasts'])) {
                foreach ($_SESSION['toasts'] as $toast) {
                    if ($toast['category'] === 'city') {
                        // Тост с категорией 'city' уже существует
                        return null;
                    }
                }
            }

            return getModalCity($id, $options['cities']);

        case 'link':
            return getModalLink($id, $options['title'], $options['linkTitle'], $options['linkAdress']);

        case 'message':
            return getModalMessage($id, $options['title'], $options['text']);

        default:
            return getModalError($id, $options['value']);
    }

    return null;
}

/**
 * Создаёт объект сообщения для модального окна с ссылкой.
 * Функция формирует сообщение, содержащее ссылку с заданными параметрами.
 *
 * @param string $id Уникальный идентификатор сообщения.
 * @param string $title Заголовок сообщения.
 * @param string $linkTitle Название ссылки.
 * @param string $linkAdress URL адрес ссылки.
 * @return array Массив, представляющий модальное сообщение с ссылкой.
 */
function getModalLink($id, $title, $linkTitle, $linkAdress)
{
    $message =  [
        'id' => $id,
        'title' => $title,
        'link' => ['linkTitle' => $linkTitle, 'address' => $linkAdress],
        'category' => 'link',
    ];

    return $message;
}

/**
 * Создаёт объект сообщения для модального окна с выбором города.
 * Функция формирует сообщение, предлагающее пользователю выбор города из списка.
 *
 * @param string $id Уникальный идентификатор сообщения.
 * @param array $cities Массив городов, каждый элемент которого содержит имя города и CSS класс для кнопки.
 * @return array Массив, представляющий модальное сообщение с выбором города.
 */
function getModalCity($id, $cities)
{
    $buttons = array_map(function ($city) {
        return ['text' => $city['name'], 'class' => $city['class']];
    }, $cities);

    $message = [
        'id' => $id,
        'title' => 'Выберите ваш город',
        'button' => $buttons,
        'category' => 'city',
    ];

    return $message;
}

/**
 * Создаёт объект сообщения об ошибке для модального окна.
 * Функция формирует сообщение об ошибке, связанной с загрузкой определённого элемента.
 *
 * @param string $id Уникальный идентификатор сообщения.
 * @param string $value Название элемента, при загрузке которого произошла ошибка.
 * @return array Массив, представляющий модальное сообщение об ошибке.
 */
function getModalError($id, $value)
{
    $message = [
        'id' => $id,
        'title' => "Ошибка при загрузке {$value}",
        'text' => "Не удалось загрузить {$value} из-за сбоя в интернет-соединении. Пожалуйста, проверьте ваше подключение и перезагрузите страницу. Если проблема сохранилась обратитесь в поддержку.",
        'category' => 'error',
    ];

    return $message;
}

/**
 * Создаёт объект сообщения об ошибке для модального окна.
 * Функция формирует сообщение об ошибке, связанной с загрузкой определённого элемента.
 *
 * @param string $id Уникальный идентификатор сообщения.
 * @param string $value Название элемента, при загрузке которого произошла ошибка.
 * @return array Массив, представляющий модальное сообщение об ошибке.
 */
function getModalMessage($id, $title, $text)
{
    $message = [
        'id' => $id,
        'title' => $title,
        'text' => $text,
        'category' => 'message',
    ];

    return $message;
}

/**
 * Удаляет сообщение с определённым идентификатором из сессии.
 * Функция удаляет сообщение об ошибке из массива 'toasts' в сессии, предотвращая его повторное отображение.
 *
 * @param string $toastId Идентификатор сообщения, которое нужно удалить.
 */
function deleteToastFromSession($toastId)
{
    // Инициализируйте или обновите данные корзины в сессии
    if (isset($_SESSION['toasts']) && is_array($_SESSION['toasts'])) {
        foreach ($_SESSION['toasts'] as $key => $toast) {
            if ($toast['id'] === $toastId) {
                unset($_SESSION['toasts'][$key]);
                // Переиндексация массива после удаления элемента
                $_SESSION['toasts'] = array_values($_SESSION['toasts']);
                break;
            }
        }
    }
}


// Функция для получения города пользователя
function getUserCity()
{
    // Проверяем, установлен ли город в сессии и возвращаем его
    return isset($_SESSION['city']) ? $_SESSION['city'] : null;
}


function getBasketList()
{
    $sessionData = $_SESSION['order'] ?? [];
    $menuArr = $sessionData['menu'] ?? [];
    $pokeArr = $sessionData['poke'] ?? [];

    // Объединение двух подмассивов
    $combinedArray = array_merge($menuArr, $pokeArr);

    // Получение общей длины объединенного массива
    $totalLength = count($combinedArray);

    return $totalLength;
}


function getAbsoluteImgPath($imgPathInDb)
{
    // Абсолютный URL
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];

    // Путь до файла
    return $protocol . $host . '/' . $imgPathInDb;
}
