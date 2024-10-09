<!-- Страница Аккаунт -->
<div class="page__account" id="page-admin">
    <div class="account account--admin container">
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
                            <?= $userInfo['user_email'] ?? ''; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Номер телефона:
                        </th>
                        <td>
                            <?= $userInfo['user_telephone'] ?? ''; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Инфа для админа: права доступа админ -->
        <div class="account__orders-wrapper">
            <!-- Вкладка -->
            <div class="account__orders-group">
                <?php $isActiveGroupOrders = $statisticGroup == 'search' ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $isActiveGroupOrders; ?>" href="./admin.php?group=search">
                    Поиск
                </a>

                <?php $isActiveGroupOrders = $statisticGroup == 'orders' ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $isActiveGroupOrders; ?>" href="./admin.php?group=orders">
                    Заказы
                </a>

                <?php $isActiveGroupClients = $statisticGroup == 'clients' ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $isActiveGroupClients; ?>" href="./admin.php?group=clients">
                    Клиенты
                </a>
            </div>


            <!-- Поиск -->
            <?php $isHiddenSearch = $statisticGroup != 'search' ? 'hidden' : ''; ?>
            <div class="account__orders-order <?= $isHiddenSearch; ?>">
                <form class="account__orders-calendar account__orders-calendar--second" action="./admin.php?group=search" method="POST">
                    <h3 class="sub-title">
                        Поиск заказа:
                    </h3>

                    <div class="account__orders-time account__orders-time--order">
                        <input class="mb-0" type="text" id="order-id" name="order-id" placeholder="Айди заказа" value="<?= $searchValue ? $searchValue : ''; ?>">

                        <button class="button--basic">
                            Поиск
                        </button>
                    </div>
                </form>


                <!-- Таблица с данными -->
                <div class="account__orders">
                    <div class="account__orders-wrapper">
                        <table class="table table-striped table-hover account__orders-table">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        Время заказа
                                    </th>
                                    <th scope="col">
                                        Id заказа
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
                                        Имя пользователя
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Проходится по массиву заказов -->
                                <?php if (!empty($orderListSearch)) : ?>
                                    <?php foreach ($keysSearch as $key) : ?>
                                        <?php if (count($orderListSearch[$key]) == 1) : ?>
                                            <?php $groupedItemFirst = $orderListSearch[$key][0]; ?>
                                            <tr>
                                                <th scope="row" class="align-middle">
                                                    <?= $groupedItemFirst['order_date']; ?>
                                                </th>
                                                <td class="text-center align-middle">
                                                    <input type="hidden" name="order-id" value="<?= $groupedItemFirst['order_id']; ?>">

                                                    <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                                        <?= $groupedItemFirst['order_id']; ?>
                                                    </a>
                                                </td>
                                                <td class="account__orders-col--big align-middle">
                                                    <?= $groupedItemFirst['title']; ?>
                                                    *
                                                    <?= $groupedItemFirst['quantity']; ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <?= $groupedItemFirst['total_amount']; ?>
                                                </td>
                                                <td class="account__orders-col--middle align-middle">
                                                    <?= $groupedItemFirst['order_address']; ?>
                                                </td>
                                                <td class="account__orders-check align-middle">
                                                    <?= $groupedItemFirst['user_name']; ?>
                                                </td>
                                            </tr>
                                        <?php else : ?>
                                            <?php $groupedItemFirst = $orderListSearch[$key][0]; ?>
                                            <tr>
                                                <th scope="row" class="align-middle">
                                                    <?= $groupedItemFirst['order_date']; ?>
                                                </th>
                                                <td class="text-center align-middle">
                                                    <input type="hidden" name="order-id" value="<?= $groupedItemFirst['order_id']; ?>">

                                                    <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                                        <?= $groupedItemFirst['order_id']; ?>
                                                    </a>
                                                </td>
                                                <td class="account__orders-col--big align-middle">
                                                    <?php foreach ($orderListSearch[$key] as $groupedSubItem) : ?>
                                                        <?= $groupedSubItem['title']; ?>
                                                        *
                                                        <?= $groupedSubItem['quantity']; ?>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <?= $groupedItemFirst['total_amount']; ?>
                                                </td>
                                                <td class="account__orders-col--middle align-middle">
                                                    <?= $groupedItemFirst['order_address']; ?>
                                                </td>
                                                <td class="account__orders-check align-middle">
                                                    <?= $groupedItemFirst['user_name']; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>


                    <!-- Активные заказы -->
                    <?php $prevPageNumberSearch = $currentPageSearch - 1; ?>
                    <?php $nextPageNumberSearch = $currentPageSearch + 1; ?>

                    <!-- Навигация для таблицы заказов -->
                    <?php if ($paginationSearch && count($paginationSearch) > 1) : ?>
                        <nav class="menu__nav account__orders-nav">

                            <?php if ($currentPageSearch > 1) : ?>
                                <a href="./admin.php?group=search&pageSearch=<?= $prevPageNumberSearch; ?>" class="menu__nav-button menu__nav-button--prev">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>

                            <ul class="menu__nav-list">
                                <?php if ($currentPageSearch == count($paginationSearch) && $prevPageNumberSearch != 1) : ?>
                                    <a href="./admin.php?group=search&pageSearch=<?= $prevPageNumberSearch - 1; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberSearch - 1; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberSearch != 0) : ?>
                                    <a href="./admin.php?group=search&pageSearch=<?= $prevPageNumberSearch; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberSearch; ?>
                                    </a>
                                <?php endif; ?>

                                <p class="menu__nav-item menu__nav-item--current">
                                    <?= $currentPageSearch; ?>
                                </p>

                                <?php if ($currentPageSearch != count($paginationSearch)) : ?>
                                    <a href="./admin.php?group=search&pageSearch=<?= $nextPageNumberSearch; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberSearch; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberSearch == 0 && $nextPageNumberSearch != count($paginationSearch)) : ?>
                                    <a href="./admin.php?group=search&pageSearch=<?= $nextPageNumberSearch + 1; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberSearch + 1; ?>
                                    </a>
                                <?php endif; ?>
                            </ul>

                            <?php if ($currentPageSearch != count($paginationSearch)) : ?>
                                <a href="./admin.php?group=search&pageSearch=<?= $nextPageNumberSearch; ?>" class="menu__nav-button menu__nav-button--next">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Заказы -->
            <?php $isHiddenOrders = $statisticGroup != 'orders' ? 'hidden' : ''; ?>
            <div class="account__orders-order <?= $isHiddenOrders; ?>">
                <form class="account__orders-calendar account__orders-calendar--second" action="./admin.php?group=orders" method="POST">
                    <h3 class="sub-title">
                        Статистика:
                    </h3>

                    <div class="account__orders-time">
                        <!-- Календарь -->
                        <div class="input__date-wrapper">
                            <input class="" type="hidden" id="date-first" name="date-first" placeholder="Дата начала" value="<?= is_null($dateFirst) ? '' : $dateFirst; ?>">
                            <input class="" type="hidden" id="date-second" name="date-second" placeholder="Дата окончания" value="<?= is_null($dateSecond) ? '' : $dateSecond; ?>">

                            <input class="input__date account__orders-input" type="text" id="datepicker" placeholder="Выберите дату">

                            <svg class="input__date-icon" xmlns="http://www.w3.org/2000/svg" width="1792" height="1792" viewBox="0 0 1792 1792">
                                <path d="M192 1664h1408V640H192v1024zM576 448V160q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v288q0 14 9 23t23 9h64q14 0 23-9t9-23zm768 0V160q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v288q0 14 9 23t23 9h64q14 0 23-9t9-23zm384-64v1280q0 52-38 90t-90 38H192q-52 0-90-38t-38-90V384q0-52 38-90t90-38h128v-96q0-66 47-113T480 0h64q66 0 113 47t47 113v96h384v-96q0-66 47-113t113-47h64q66 0 113 47t47 113v96h128q52 0 90 38t38 90z" />
                            </svg>
                        </div>

                        <button class="button--basic">
                            Загрузить
                        </button>
                    </div>
                </form>


                <!-- Таблица с данными -->
                <div class="account__orders account__orders--active">
                    <h3 class="account__orders-title">
                        Активные заказы:
                    </h3>

                    <div class="account__orders-wrapper">
                        <table class="table table-striped table-hover account__orders-table " id="account__orders-table--active">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        Время заказа
                                    </th>
                                    <th scope="col">
                                        Id заказа
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
                                        Выполнен
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Проходится по массиву заказов -->
                                <?php if (!empty($orderListActive)) : ?>
                                    <?php foreach ($keysActive as $key) : ?>
                                        <?php if (count($orderListActive[$key]) == 1) : ?>
                                            <?php $groupedItemFirst = $orderListActive[$key][0]; ?>
                                            <tr>
                                                <th scope="row" class="align-middle">
                                                    <?= $groupedItemFirst['order_date']; ?>
                                                </th>
                                                <td class="text-center align-middle">
                                                    <input type="hidden" name="order-id" value="<?= $groupedItemFirst['order_id']; ?>">

                                                    <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                                        <?= $groupedItemFirst['order_id']; ?>
                                                    </a>
                                                </td>
                                                <td class="account__orders-col--big align-middle">
                                                    <?= $groupedItemFirst['title']; ?>
                                                    *
                                                    <?= $groupedItemFirst['quantity']; ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <?= $groupedItemFirst['total_amount']; ?>
                                                </td>
                                                <td class="account__orders-col--middle align-middle">
                                                    <?= $groupedItemFirst['order_address']; ?>
                                                </td>
                                                <td class="account__orders-check align-middle">
                                                    <input class="form-check-input account__orders-checkbox" type="checkbox" value="" id="flexCheckDefault">
                                                </td>
                                            </tr>
                                        <?php else : ?>
                                            <?php $groupedItemFirst = $orderListActive[$key][0]; ?>
                                            <tr>
                                                <th scope="row" class="align-middle">
                                                    <?= $groupedItemFirst['order_date']; ?>
                                                </th>
                                                <td class="text-center align-middle">
                                                    <input type="hidden" name="order-id" value="<?= $groupedItemFirst['order_id']; ?>">

                                                    <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                                        <?= $groupedItemFirst['order_id']; ?>
                                                    </a>
                                                </td>
                                                <td class="account__orders-col--big align-middle">
                                                    <?php foreach ($orderListActive[$key] as $groupedSubItem) : ?>
                                                        <?= $groupedSubItem['title']; ?>
                                                        *
                                                        <?= $groupedSubItem['quantity']; ?>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <?= $groupedItemFirst['total_amount']; ?>
                                                </td>
                                                <td class="account__orders-col--middle align-middle">
                                                    <?= $groupedItemFirst['order_address']; ?>
                                                </td>
                                                <td class="account__orders-check align-middle">
                                                    <input class="form-check-input account__orders-checkbox" type="checkbox" value="" id="flexCheckDefault">
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>


                    <!-- Активные заказы -->
                    <?php $prevPageNumberActive = $currentPageActive - 1; ?>
                    <?php $nextPageNumberActive = $currentPageActive + 1; ?>

                    <!-- Навигация для таблицы заказов -->
                    <?php if ($paginationActive && count($paginationActive) > 1) : ?>
                        <nav class="menu__nav account__orders-nav">

                            <?php if ($currentPageActive > 1) : ?>
                                <a href="./admin.php?group=orders&pageActive=<?= $prevPageNumberActive; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-button menu__nav-button--prev">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>

                            <ul class="menu__nav-list">
                                <?php if ($currentPageActive == count($paginationActive) && $prevPageNumberActive != 1) : ?>
                                    <a href="./admin.php?group=orders&pageActive=<?= $prevPageNumberActive - 1; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberActive - 1; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberActive != 0) : ?>
                                    <a href="./admin.php?group=orders&pageActive=<?= $prevPageNumberActive; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberActive; ?>
                                    </a>
                                <?php endif; ?>

                                <p class="menu__nav-item menu__nav-item--current">
                                    <?= $currentPageActive; ?>
                                </p>

                                <?php if ($currentPageActive != count($paginationActive)) : ?>
                                    <a href="./admin.php?group=orders&pageActive=<?= $nextPageNumberActive; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberActive; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberActive == 0 && $nextPageNumberActive != count($paginationActive)) : ?>
                                    <a href="./admin.php?group=orders&pageActive=<?= $nextPageNumberActive + 1; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberActive + 1; ?>
                                    </a>
                                <?php endif; ?>
                            </ul>

                            <?php if ($currentPageActive != count($paginationActive)) : ?>
                                <a href="./admin.php?group=orders&pageActive=<?= $nextPageNumberActive; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-button menu__nav-button--next">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                </div>

                <!-- Таблица с данными -->
                <div class="account__orders account__orders--complete">
                    <h3 class="account__orders-title">
                        Завершенные заказы:
                    </h3>

                    <div class="account__orders-wrapper">
                        <table class="table table-striped table-hover account__orders-table" id="account__orders-table--complete">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        Время заказа
                                    </th>
                                    <th scope="col">
                                        Id заказа
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
                                        Выполнен
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Проходится по массиву заказов -->
                                <?php if (!empty($orderListСomplete)) : ?>
                                    <?php foreach ($keysСomplete as $key) : ?>
                                        <?php if (count($orderListСomplete[$key]) == 1) : ?>
                                            <?php $groupedItemFirst = $orderListСomplete[$key][0]; ?>
                                            <tr>
                                                <th scope="row" class="align-middle">
                                                    <?= $groupedItemFirst['order_date']; ?>
                                                </th>
                                                <td class="text-center align-middle">
                                                    <input type="hidden" name="order-id" value="<?= $groupedItemFirst['order_id']; ?>">

                                                    <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                                        <?= $groupedItemFirst['order_id']; ?>
                                                    </a>
                                                </td>
                                                <td class="account__orders-col--big align-middle">
                                                    <?= $groupedItemFirst['title']; ?>
                                                    *
                                                    <?= $groupedItemFirst['quantity']; ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <?= $groupedItemFirst['total_amount']; ?>
                                                </td>
                                                <td class="account__orders-col--middle align-middle">
                                                    <?= $groupedItemFirst['order_address']; ?>
                                                </td>
                                                <td class="account__orders-check align-middle">
                                                    <input class="form-check-input account__orders-checkbox" type="checkbox" value="" id="flexCheckDefault" checked>
                                                </td>
                                            </tr>
                                        <?php else : ?>
                                            <?php $groupedItemFirst = $orderListСomplete[$key][0]; ?>
                                            <tr>
                                                <th scope="row" class="align-middle">
                                                    <?= $groupedItemFirst['order_date']; ?>
                                                </th>
                                                <td class="text-center align-middle">
                                                    <input type="hidden" name="order-id" value="<?= $groupedItemFirst['order_id']; ?>">

                                                    <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                                        <?= $groupedItemFirst['order_id']; ?>
                                                    </a>
                                                </td>
                                                <td class="account__orders-col--big align-middle">
                                                    <?php foreach ($orderListСomplete[$key] as $groupedSubItem) : ?>
                                                        <?= $groupedSubItem['title']; ?>
                                                        *
                                                        <?= $groupedSubItem['quantity']; ?>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <?= $groupedItemFirst['total_amount']; ?>
                                                </td>
                                                <td class="account__orders-col--middle align-middle">
                                                    <?= $groupedItemFirst['order_address']; ?>
                                                </td>
                                                <td class="account__orders-check align-middle">
                                                    <input class="form-check-input account__orders-checkbox" type="checkbox" value="" id="flexCheckDefault" checked>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>


                    <!-- Заверешенные заказы -->
                    <?php $prevPageNumberСomplete = $currentPageСomplete - 1; ?>
                    <?php $nextPageNumberСomplete = $currentPageСomplete + 1; ?>

                    <!-- Навигация для таблицы заказов -->
                    <?php if ($paginationСomplete && count($paginationСomplete) > 1) : ?>
                        <nav class="menu__nav account__orders-nav">

                            <?php if ($currentPageСomplete > 1) : ?>
                                <a href="./admin.php?group=orders&pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $prevPageNumberСomplete; ?>" class="menu__nav-button menu__nav-button--prev">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>

                            <ul class="menu__nav-list">
                                <?php if ($currentPageСomplete == count($paginationСomplete) && $prevPageNumberСomplete != 1) : ?>
                                    <a href="./admin.php?group=orders&pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $prevPageNumberСomplete - 1; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberСomplete - 1; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberСomplete != 0) : ?>
                                    <a href="./admin.php?group=orders&pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $prevPageNumberСomplete; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberСomplete; ?>
                                    </a>
                                <?php endif; ?>

                                <p class="menu__nav-item menu__nav-item--current">
                                    <?= $currentPageСomplete; ?>
                                </p>

                                <?php if ($currentPageСomplete != count($paginationСomplete)) : ?>
                                    <a href="./admin.php?group=orders&pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $nextPageNumberСomplete; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberСomplete; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberСomplete == 0 && $nextPageNumberСomplete != count($paginationСomplete)) : ?>
                                    <a href="./admin.php?group=orders&pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $nextPageNumberСomplete + 1; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberСomplete + 1; ?>
                                    </a>
                                <?php endif; ?>
                            </ul>

                            <?php if ($currentPageСomplete != count($paginationСomplete)) : ?>
                                <a href="./admin.php?group=orders&pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $nextPageNumberСomplete; ?>" class="menu__nav-button menu__nav-button--next">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Клиенты -->
            <?php $isHiddenClients = $statisticGroup != 'clients' ? 'hidden' : ''; ?>
            <div class="account__orders-client <?= $isHiddenClients; ?>">
                <form class="account__orders-calendar account__orders-calendar--second" action="./admin.php?group=clients" method="POST">
                    <h3 class="sub-title">
                        Поиск клиента:
                    </h3>

                    <div class="account__orders-time account__orders-time--order">
                        <input class="mb-0" type="text" id="user-phone" name="user-phone" placeholder="Введите номер телефона +7.." value="<?= $phoneValue ? $phoneValue : ''; ?>">

                        <button class="button--basic">
                            Поиск
                        </button>
                    </div>
                </form>


                <!-- Таблица с данными -->
                <div class="account__orders">
                    <h3 class="account__orders-title">
                        Список клиентов:
                    </h3>

                    <div class="account__orders-wrapper">
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
                                    <!-- <th scope="col">
                                        Адрес
                                    </th> -->
                                    <th scope="col">
                                        Рейтинг клиента
                                    </th>
                                    <th scope="col">
                                        Ср. чек
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Проходится по массиву заказов -->
                                <?php if (!empty($userListLength)) : ?>
                                    <?php foreach ($userListFormatted as $userItem) : ?>
                                        <tr>
                                            <th scope="row" class="align-middle">
                                                <?= $userItem['id']; ?>
                                            </th>
                                            <td class="text-center align-middle">
                                                <?= $userItem['user_name']; ?>
                                            </td>
                                            <td class=" align-middle">
                                                <?= $userItem['user_telephone']; ?>
                                            </td>
                                            <td class="align-middle">
                                                <?= $userItem['user_rating']; ?>
                                            </td>
                                            <td class="align-middle">
                                                <?= $userItem['average_order_amount']; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Активные заказы -->
                    <?php $prevPageNumberUser = $currentPageUser - 1; ?>
                    <?php $nextPageNumberUser = $currentPageUser + 1; ?>

                    <!-- Навигация для таблицы заказов -->
                    <?php if ($paginationUser && count($paginationUser) > 1) : ?>
                        <nav class="menu__nav account__orders-nav">

                            <?php if ($currentPageUser > 1) : ?>
                                <a href="./admin.php?group=clients&pageUser=<?= $prevPageNumberUser; ?>" class="menu__nav-button menu__nav-button--prev">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>

                            <ul class="menu__nav-list">
                                <?php if ($currentPageUser == count($paginationUser) && $prevPageNumberUser != 1) : ?>
                                    <a href="./admin.php?group=clients&pageUser=<?= $prevPageNumberUser - 1; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberUser - 1; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberUser != 0) : ?>
                                    <a href="./admin.php?group=clients&pageUser=<?= $prevPageNumberUser; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberUser; ?>
                                    </a>
                                <?php endif; ?>

                                <p class="menu__nav-item menu__nav-item--current">
                                    <?= $currentPageUser; ?>
                                </p>

                                <?php if ($currentPageUser != count($paginationUser)) : ?>
                                    <a href="./admin.php?group=clients&pageUser=<?= $nextPageNumberUser; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberUser; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberUser == 0 && $nextPageNumberUser != count($paginationUser)) : ?>
                                    <a href="./admin.php?group=clients&pageUser=<?= $nextPageNumberUser + 1; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberUser + 1; ?>
                                    </a>
                                <?php endif; ?>
                            </ul>

                            <?php if ($currentPageUser != count($paginationUser)) : ?>
                                <a href="./admin.php?group=clients&pageUser=<?= $nextPageNumberUser; ?>" class="menu__nav-button menu__nav-button--next">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>