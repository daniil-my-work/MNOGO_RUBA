<?php

/**
 * Проверяет валидность электронной почты.
 *
 * @param string $email Адрес электронной почты для проверки.
 *
 * @return string|null Возвращает сообщение об ошибке, если адрес электронной почты неверен, в противном случае null.
 */
function validate_email($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "E-mail должен быть корректным";
    }
};

/**
 * Проверяет валидность номера телефона.
 *
 * @param string|null $phone Номер телефона для проверки.
 *
 * @return string|null Возвращает сообщение об ошибке, если номер телефона неверен, в противном случае null.
 */
function validate_phone($phone)
{
    if ($phone === null) {
        return 'Введите номер телефона';
    }

    // Удаление всех символов, кроме цифр
    $phone = preg_replace("/[^0-9]/", "", $phone);

    // Проверка, что телефон состоит из 11 цифр
    if (strlen($phone) !== 11) {
        return 'Неверная длина номера телефона';
    }

    // Проверка, что телефон начинается с кода страны "+7"
    if (substr($phone, 0, 2) !== '79') {
        return 'Телефон должен начинаться с кода страны +7';
    }

    // Все проверки пройдены, номер телефона валиден
    return '';
}

/**
 * Проверяет длину строки.
 *
 * @param string|null $value Строка для проверки длины.
 * @param int $min Минимальная длина строки.
 * @param int $max Максимальная длина строки.
 *
 * @return string|null Возвращает сообщение об ошибке, если длина строки неверна, в противном случае null.
 */
function validate_length($value, $min, $max)
{
    if ($value) {
        $len = strlen($value);
        if ($len < $min or $len > $max) {
            return "Пароль должен быть от $min до $max символов";
        }
    }
}

/**
 * Проверяет наличие компонента.
 *
 * @param mysqli $con Объект соединения с базой данных.
 * @param string $componentType Тип компонента для проверки.
 * @param mixed $value Значение компонента или массив значений.
 *
 * @return string|null Возвращает сообщение об ошибке, если компонент не существует, в противном случае null.
 */
function validate_component($con, $componentType, $value)
{
    $sql = get_query_component_types();
    $res = mysqli_query($con, $sql);

    if (!$res) {
        return 'Ошибка запроса';
    }

    $componentNames = get_arrow($res);

    $newData = [];
    // Проходимся по исходным данным и добавляем в новый массив
    foreach ($componentNames as $item) {
        $newData[$item['component_type']] = $item['component_name'];
    }

    $componentName = $newData[$componentType];

    if (is_array($value)) {
        foreach ($value as $id) {
            $sql = get_query_check_component($id, $componentType);
            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result) == 0) {
                return "Ошибка при выборе $componentName. Вы выбрали несуществующий компонент.";
            }
        }

        return null;
    } else {
        $sql = get_query_check_component($value, $componentType);
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) == 0) {
            return "Ошибка при выборе $componentName. Вы выбрали несуществующий компонент.";
        }

        return null;
    }
}


/**
 * Проверяет длину компонента в соответствии с выбранной схемой.
 *
 * @param string $name Имя компонента.
 * @param array $value Массив значений компонента.
 * @param int|null $shema Выбранная схема.
 *
 * @return string|null Возвращает сообщение об ошибке, если длина компонента не соответствует схеме, в противном случае null.
 */
function validate_component_length($name, $value, $shema)
{
    // Если $shema равно null, вернуть сообщение об ошибке
    if ($shema === null) {
        return "Схема не определена";
    }

    $len = count($value);

    if ($shema == 1) {
        if ($name === 'filler') {
            if ($len != 5) {
                return "Выберите 5 наполнителей";
            }
        }

        if ($name === 'topping') {
            if ($len != 1) {
                return "Выберите 1 топпинг";
            }
        }
    } else {
        if ($name === 'filler') {
            if ($len != 3) {
                return "Выберите 3 наполнителя";
            }
        }

        if ($name === 'topping') {
            if ($len != 2) {
                return "Выберите 2 топпинга";
            }
        }
    }

    // Если не было обнаружено ошибок, вернуть null
    return null;
}
