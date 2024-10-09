<?php

// Максимальное кол-во строк в таблице
define("MAX_ROW", 5);

// Длина пагинации 
define("PAGINATION_LENGTH", 3);

// Список городов 
$optionCity = [
    'cities' => [
        [
            'name' => 'Ярославль',
            'class' => 'btn btn-primary btn-sm'
        ],
        [
            'name' => 'Рыбинск',
            'class' => 'btn btn-secondary btn-sm'
        ],
    ]
];

// Описание полей для проверки Логин/Авторизация
$fieldDescriptions = [
    'user_name' => 'Имя',
    'email' => 'E-mail',
    'phone' => 'Телефон',
    'user_password' => 'Пароль',
];

// Номера телефонов: Админ
$adminPhone = [
    '+79807057008',
    '+79807057009'
];

// Номера телефонов: Собственник 
$ownerPhone = [
    '+79807057005'
];

// Названия столбцов Меню
$columnNameMenu = [
    'title' => 'Название',
    'img' => 'Фото (ссылка)',
    'description' => 'Описание',
    'price' => 'Цена',
    'cooking_time' => 'Время приготовления',
    'category_id' => 'Айди категории: (поке – 1, роллы – 2, супы – 3, горячее – 4, вок – 5, закуски – 6, сэндвичи – 7, десерты – 8, напитки – 9, соус – 10, авторский поке – 11)'
];

// Названия столбцов Поке
$columnNamePoke = [
    'title' => 'Название',
    'img' => 'Фото (ссылка)',
    'price' => 'Цена',
    'component_type' => 'Тип компонента: (protein, protein-add, base, filler, topping, sauce, crunch)',
    'component_name' => 'Название компонента: (протеин, протеин-добавка, основа, наполнитель, топпинг, соус, хруст)',
    'component_poke_type' => 'Заполняется для типа протеин. Поке с ...'
];


// Данные приложения
$appData = [
    // Роли для работы в сервисе
    'userRoles' => [
        'client' => 'CLIENT',
        'admin' => 'ADMIN',
        'owner' => 'OWNER',
    ],
    // Список телефонов для собственников и админов 
    'telephones' => [
        'admin' => $adminPhone,
        'owner' => $ownerPhone,
    ],
    // Конфигурация SQL запросов и названий колонок в зависимости от таблицы
    'queries' => [
        'menu' => [
            'query' => "SELECT menu.title, menu.img, menu.description, menu.price, menu.cooking_time, menu.category_id FROM menu",
            'columns' => $columnNameMenu
        ],
        'poke' => [
            'query' => "SELECT components.title, components.img, components.price, components.component_type, components.component_name, components.component_poke_type FROM components",
            'columns' => $columnNamePoke
        ]
    ]
];
