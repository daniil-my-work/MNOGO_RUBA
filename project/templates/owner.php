<!-- Страница Аккаунт -->
<div class="page__account">
    <div class="account">
        <!-- Общая инфа для всех кабинетов -->
        <div class="account__info">
            <img class="account__info-img" src="<?= $userInfo['user_img'] ?? ''; ?>" alt="">

            <h3 class="account__info-name">
                <?= $userInfo['user_name'] ?? ''; ?>
            </h3>

            <!-- Таблица с данными -->
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <th scope="row">
                            Почта:
                        </th>
                        <td>
                            <?= $userInfo['email'] ?? ''; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Номер телефона:
                        </th>
                        <td>
                            <?= $userInfo['telephone'] ?? ''; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Инфа для собственника: права доступа собственника -->
        <div class="account__orders-wrapper">
            <!-- Вкладка -->
            <div class="account__orders-group">
                <?php $isActiveGroupOrders = $statisticGroup == 'orders' ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $isActiveGroupOrders; ?>" href="./owner.php?group=orders">
                    Заказы
                </a>

                <?php $isActiveGroupClients = $statisticGroup == 'clients' ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $isActiveGroupClients; ?>" href="./owner.php?group=clients">
                    Клиенты
                </a>

                <?php $isActiveGroupAnalytics = $statisticGroup == 'analytics' ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $isActiveGroupAnalytics; ?>" href="./owner.php?group=analytics">
                    Аналитика
                </a>
            </div>


            <!-- Заказы -->
            <?php $isHiddenOrders = $statisticGroup != 'orders' ? 'hidden' : ''; ?>
            <div class="account__orders-order <?= $isHiddenOrders; ?>">
                <!-- Календарь -->
                <div class="account__orders-time">
                    <h3 class="sub-title">
                        Статистика:
                    </h3>

                    <input class="account__orders-input" type="date" name="" id="">
                </div>

                <!-- Таблица с данными -->
                <div class="account__orders account__orders--active">
                    <h3 class="account__orders-title">
                        Список заказов:
                    </h3>

                    <table class="table table-striped table-hover account__orders-table ">
                        <thead>
                            <tr>
                                <th scope="col">
                                    Время заказа
                                </th>
                                <th scope="col">
                                    Состав заказа
                                </th>
                                <th scope="col">
                                    Сумма заказа
                                </th>
                                <th scope="col">
                                    Адрес
                                </th>
                                <th scope="col">
                                    Рейтинг клиента
                                </th>
                                <th scope="col">
                                    Статус
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Навигация для таблицы заказов -->
                    <nav class="menu__nav account__orders-nav">
                        <button class="menu__nav-button menu__nav-button--prev">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>

                        <ul class="menu__nav-list">
                            <li class="menu__nav-item">
                                1
                            </li>

                            <li class="menu__nav-item">
                                2
                            </li>

                            <li class="menu__nav-item">
                                3
                            </li>

                            <li class="menu__nav-item">
                                ...
                            </li>
                        </ul>

                        <button class="menu__nav-button menu__nav-button--next">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>
                    </nav>
                </div>

                <!-- Таблица с данными -->
                <div class="account__orders">
                    <h3 class="account__orders-title">
                        Обратить внимание:
                    </h3>

                    <table class="table table-striped table-hover account__orders-table ">
                        <thead>
                            <tr>
                                <th scope="col">
                                    Время заказа
                                </th>
                                <th scope="col">
                                    Состав заказа
                                </th>
                                <th scope="col">
                                    Сумма заказа
                                </th>
                                <th scope="col">
                                    Адрес
                                </th>
                                <th scope="col">
                                    Рейтинг клиента
                                </th>
                                <th scope="col">
                                    Статус
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Навигация для таблицы заказов -->
                    <nav class="menu__nav account__orders-nav">
                        <button class="menu__nav-button menu__nav-button--prev">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>

                        <ul class="menu__nav-list">
                            <li class="menu__nav-item">
                                1
                            </li>

                            <li class="menu__nav-item">
                                2
                            </li>

                            <li class="menu__nav-item">
                                3
                            </li>

                            <li class="menu__nav-item">
                                ...
                            </li>
                        </ul>

                        <button class="menu__nav-button menu__nav-button--next">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Клиенты -->
            <?php $isHiddenClients = $statisticGroup != 'clients' ? 'hidden' : ''; ?>
            <div class="account__orders-client <?= $isHiddenClients; ?>">
                <!-- Календарь -->
                <div class="account__orders-time">
                    <h3 class="sub-title">
                        Поиск:
                    </h3>

                    <input class="account__orders-input" type="text" name="" id="">
                </div>

                <!-- Таблица с данными -->
                <div class="account__orders">
                    <h3 class="account__orders-title">
                        Список клиентов:
                    </h3>

                    <table class="table table-striped table-hover account__orders-table ">
                        <thead>
                            <tr>
                                <th scope="col">
                                    id пользователя
                                </th>
                                <th scope="col">
                                    Имя
                                </th>
                                <th scope="col">
                                    Контакты
                                </th>
                                <th scope="col">
                                    Адрес
                                </th>
                                <th scope="col">
                                    Рейтинг клиента
                                </th>
                                <th scope="col">
                                    Ср. чек
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Навигация для таблицы заказов -->
                    <nav class="menu__nav account__orders-nav">
                        <button class="menu__nav-button menu__nav-button--prev">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>

                        <ul class="menu__nav-list">
                            <li class="menu__nav-item">
                                1
                            </li>

                            <li class="menu__nav-item">
                                2
                            </li>

                            <li class="menu__nav-item">
                                3
                            </li>

                            <li class="menu__nav-item">
                                ...
                            </li>
                        </ul>

                        <button class="menu__nav-button menu__nav-button--next">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Аналитика -->
            <?php $isHiddenAnalytics = $statisticGroup != 'analytics' ? 'hidden' : ''; ?>
            <div class="account__orders-analytic <?= $isHiddenAnalytics; ?>">
                <div class="account__orders-analytic-download">
                    <div class="account__orders-analytic-download-time">
                        <p class="text">
                            Статистика за период:
                        </p>

                        <input type="date" name="" id="">
                        <input type="date" name="" id="">
                    </div>

                    <button class="button--basic" type="button">
                        Скачать
                    </button>
                </div>


                <div class="account__orders-analytic-content">
                    <h3 class="sub-title">
                        Статистика:
                    </h3>

                    <table class="table table-striped table-hover mb-0">
                        <tbody>
                            <tr>
                                <th scope="row">Ср. чек</th>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">Кол-во заказов</th>
                                <td>Mark</td>
                            </tr>
                            <tr>
                                <th scope="row">Ср. время приготовления</th>
                                <td>Mark</td>
                            </tr>
                            <tr>
                                <th scope="row">Ср. время доставки</th>
                                <td>Mark</td>
                            </tr>
                            <tr>
                                <th scope="row">Выручка</th>
                                <td>Mark</td>
                            </tr>
                            <tr>
                                <th scope="row">Топ популярных блюд</th>
                                <td>Mark</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>