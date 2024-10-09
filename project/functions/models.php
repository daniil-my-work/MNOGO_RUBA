<?php

/**
 * Формирует SQL-запрос для добавления записи в таблицу user
 * @return string SQL-запрос
 */
function get_query_create_user()
{
    return "INSERT INTO user (date_reg, user_name, user_email, user_telephone, user_password, user_ip, user_role) 
    VALUES (NOW(), ?, ?, ?, ?, ?, ?)";
}

/**
 * Формирует SQL-запрос для показа спика
 * @return string SQL-запрос
 */
function get_query_user_info($user_email)
{
    return "SELECT * FROM user WHERE user.user_email = '$user_email'";
};

/**
 * Формирует SQL-запрос для показа спика
 * @return string SQL-запрос
 */
function get_query_user_auth($user_email)
{
    return "SELECT user.id, user.user_name, user.user_email, user.user_password, user.user_role FROM user WHERE user.user_email = '$user_email'";
};

/**
 * Формирует SQL-запрос для показа списка продуктов по списку идентификаторов продуктов
 * @param int $productId Идентификатор продукта
 * @return string SQL-запрос
 */
function get_query_product_item(int $productId)
{
    return "SELECT * FROM menu WHERE menu.id = $productId";
}

/**
 * Формирует SQL-запрос для показа списка Поке по списку идентификаторов продуктов
 * @param int $productId Идентификатор продукта
 * @return string SQL-запрос
 */
function get_query_product_item_poke(int $productId)
{
    return "SELECT * FROM poke WHERE poke.id = $productId";
}

/**
 * Формирует SQL-запрос для показа списка продуктов 
 * @return string SQL-запрос
 */
function get_query_products()
{
    return "SELECT * FROM menu";
}

/**
 * Формирует SQL-запрос для показа списка продуктов 
 * @return string SQL-запрос
 */
function get_query_selected_products($activeCategory)
{
    return "SELECT menu.id, menu.title, menu.img, menu.description, menu.price, category_menu.category_name, category_menu.category_title FROM menu LEFT JOIN category_menu ON menu.category_id = category_menu.id WHERE category_menu.category_title = '$activeCategory'";
}

/**
 * Формирует SQL-запрос для показа списка категорий 
 * @return string SQL-запрос
 */
function get_query_categories()
{
    return "SELECT * FROM category_menu";
}

/**
 * Формирует SQL-запрос для показа активной категории
 * @return string SQL-запрос
 */
function get_query_selected_category($activeCategory)
{
    return "SELECT * FROM category_menu WHERE category_menu.category_title = '$activeCategory'";
}

/**
 * Формирует SQL-запрос для добавления записи в таблицу orders
 * @return string SQL-запрос
 */
function get_query_create_order()
{
    return "INSERT INTO orders (order_date, customer_id, total_amount, order_id, order_address, order_comment) 
    VALUES (NOW(), ?, ?, ?, ?, ?)";
}

/**
 * Формирует SQL-запрос для добавления записи в таблицу order_items
 * @return string SQL-запрос
 */
function get_query_create_order_item()
{
    return "INSERT INTO order_items (product_id, quantity, unit_price, tableName, order_id) 
    VALUES (?, ?, ?, ?, ?)";
}

/**
 * Формирует SQL-запрос для получения компонентов Поке
 * @return string SQL-запрос
 */
function get_query_components()
{
    return "SELECT * FROM components";
}

/**
 * Формирует SQL-запрос для получения названия компонента Поке
 * @return string SQL-запрос
 */
function get_query_component_poke_type($componentId)
{
    return "SELECT components.component_poke_type FROM components WHERE components.id = $componentId";
}

/**
 * Формирует SQL-запрос для получения названия компонента Поке
 * @return string SQL-запрос
 */
function get_query_component_names($componentId)
{
    return "SELECT components.title FROM components WHERE components.id = '$componentId'";
}

/**
 * Формирует SQL-запрос для проверки наличия компонента в Поке
 * @return string SQL-запрос
 */
function get_query_check_component($componentId, $componentType)
{
    return "SELECT * FROM components WHERE components.id = '$componentId' AND components.component_type = '$componentType'";
}

/**
 * Формирует SQL-запрос для получения Названия компонентов
 * @return string SQL-запрос
 */
function get_query_component_types()
{
    return "SELECT DISTINCT component_type, component_name FROM components";
}

/**
 * Формирует SQL-запрос для добавления записи в таблицу poke
 * @return string SQL-запрос
 */
function get_query_create_poke()
{
    return "INSERT INTO `poke`(
        title,
        img,
        description,
        price,
        cooking_time,
        category_id,
        poke_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?)";
}

/**
 * Формирует SQL-запрос для добавления состовляющих Поке в таблицу poke_consists
 * @return string SQL-запрос
 */
function get_query_create_poke_contains()
{
    return "INSERT INTO `poke_consists`(
        poke_id,
        component_id,
        quantity
    ) VALUES (?, ?, ?)";
}

/**
 * Формирует SQL-запрос для получения уникального айди Поке
 * @return string SQL-запрос
 */
function get_query_poke_unique_id($pokeId)
{
    return "SELECT poke_id FROM poke WHERE poke.id = '$pokeId'";
}

/**
 * Формирует SQL-запрос для удаления Поке из таблицы
 * @return string SQL-запрос
 */
function get_query_delete_poke($pokeId)
{
    return "DELETE FROM poke WHERE poke.id = '$pokeId'";
}

/**
 * Формирует SQL-запрос для удаления составляющих Поке из таблицы
 * @return string SQL-запрос
 */
function get_query_delete_poke_consists($pokeUniqueId)
{
    return "DELETE FROM poke_consists WHERE poke_consists.poke_id = '$pokeUniqueId'";
}

/**
 * Формирует SQL-запрос для показа списка адресов кафе 
 * @return string SQL-запрос
 */
function get_query_cafe_address()
{
    return "SELECT * FROM cafe_address";
}

/**
 * Формирует SQL-запрос для поиска заказа по Айди 
 * @return string SQL-запрос
 */
function get_query_search_order_by_id($searchValue)
{
    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title, user.user_name FROM orders 
           LEFT JOIN order_items ON orders.order_id = order_items.order_id 
           LEFT JOIN menu ON order_items.product_id = menu.id 
           LEFT JOIN user ON orders.customer_id = user.id
           WHERE orders.order_id LIKE '%$searchValue%'";

    return $sql;
}

/**
 * Формирует SQL-запрос для получения списка пользователей
 * @return string SQL-запрос
 */
function get_query_search_clients_by_phone($phoneValue)
{
    $sql = "SELECT user.id, user.user_name, user.user_telephone, user.user_rating, SUM(orders.total_amount) AS total_order_amount, COUNT(orders.id) AS total_orders_count, ROUND(AVG(orders.total_amount)) AS average_order_amount
        FROM 
            user
        LEFT JOIN 
            orders ON user.id = orders.customer_id
        WHERE 
            user.user_telephone LIKE '%$phoneValue%'
        GROUP BY 
            user.id, 
            user.user_name,
            user.user_telephone,
            user.user_rating;";

    return $sql;
}

/**
 * Формирует SQL-запрос для получения заказа 
 * @return string SQL-запрос
 */
function get_query_order_id($orderId)
{
    $sql = "SELECT orders.order_id FROM orders WHERE orders.id = '$orderId'";

    return $sql;
}

/**
 * Формирует SQL-запрос для получения данных о конкретном заказе по id заказа
 * @return string SQL-запрос
 */
function get_query_order_info_by_id($orderId)
{
    $sql = "SELECT * FROM orders WHERE orders.order_id = '$orderId'";

    return $sql;
}

/**
 * Формирует SQL-запрос для получения заказа 
 * @return string SQL-запрос
 */
function get_query_insert_data_from_file($tableName)
{
    if ($tableName === 'menu') {
        $sql = "INSERT INTO menu (`title`, `img`, `description`, `price`, `cooking_time`, `category_id`) VALUES (?, ?, ?, ?, ?, ?)";
    } else {
        $sql = "INSERT INTO components (`title`, `img`, `price`, `component_type`, `component_name`, `component_poke_type`) VALUES (?, ?, ?, ?, ?, ?)";
    }

    return $sql;
}

/**
 * Формирует SQL-запрос для получения данных о товарах в заказе 
 * @return string SQL-запрос
 */
function get_query_order_items($order_id)
{
    $sql = "SELECT order_items.product_id, order_items.tableName FROM order_items WHERE order_items.order_id = '$order_id'";

    return $sql;
}

/**
 * Формирует SQL-запрос для получения данных о товаре из таблицы Меню 
 * @return string SQL-запрос
 */
function get_query_order_items_from_menu($productId, $orderId)
{
    $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, menu.title, menu.img, menu.description, menu.category_id 
        FROM order_items 
        LEFT JOIN menu ON order_items.product_id = menu.id
        WHERE menu.id = '$productId' AND order_items.order_id = '$orderId'";

    return $sql;
}

/**
 * Формирует SQL-запрос для получения данных о товаре из таблицы Поке 
 * @return string SQL-запрос
 */
function get_query_order_items_from_poke($productId, $orderId)
{
    $sql = "SELECT order_items.product_id, order_items.quantity, order_items.unit_price, poke.title, poke.img, poke.description, poke.category_id 
        FROM order_items
        LEFT JOIN poke ON order_items.product_id = poke.id
        WHERE order_items.product_id = '$productId' AND order_items.order_id = '$orderId'";

    return $sql;
}

/**
 * Формирует SQL-запрос для получения заказов пользователя с учетом временного промежутка.
 *
 * @param string $userId Идентификатор пользователя.
 * @param string|null $dateFirst Начальная дата временного интервала.
 * @param string|null $dateSecond Конечная дата временного интервала.
 * @return string SQL-запрос.
 */
function get_query_user_order($userId, $dateFirst = null, $dateSecond = null)
{
    $sql = "SELECT orders.*, order_items.product_id, order_items.quantity, menu.title 
        FROM orders 
        LEFT JOIN order_items ON orders.order_id = order_items.order_id 
        LEFT JOIN menu ON order_items.product_id = menu.id 
        WHERE orders.customer_id = '$userId'";

    // Проверка наличия данных о временном промежутке
    if (!is_null($dateFirst) && !is_null($dateSecond)) {
        $sql .= " AND orders.order_date BETWEEN '$dateFirst 00:00:00' AND '$dateSecond 23:59:59'";
    }

    // Добавление сортировки к запросу
    $sql .= " ORDER BY orders.id DESC;";
    return $sql;
}
