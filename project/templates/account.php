<!-- Страница Аккаунт -->
<div class="page__account">
    <div class="account container">
        <!-- Общая инфа для всех кабинетов -->
        <div class="account__info">
            <img class="account__info-img" src="<?= $userInfo['user_img'] ?? ''; ?>" alt="">

            <h3 class="title account__info-name">
                <?= $userInfo['user_name'] ?? ''; ?>
            </h3>

            <!-- Таблица с данными -->
            <table class="table mb-0">
                <tbody>
                    <tr class="text">
                        <th scope="row">
                            Почта:
                        </th>
                        <td>
                            <?= $userInfo['user_email'] ?? ''; ?>
                        </td>
                    </tr>

                    <tr class="text">
                        <th scope="row">
                            Номер телефона:
                        </th>
                        <td>
                            <?= $userInfo['user_telephone'] ?? ''; ?>
                        </td>
                    </tr>
                    <!-- <tr>
                        <th scope="row">
                            Адрес:
                        </th>
                        <td>
                            Таким образом постоянный количественный рост и сфера нашей активности требуют
                        </td>
                    </tr> -->
                </tbody>
            </table>
        </div>

        <!-- Инфа для клиентов: права доступа клиент -->
        <div class="account__orders">
            <h3 class="sub-title account__orders-title">
                История заказов:
            </h3>

            <form class="account__orders-calendar" action="./account.php" method="POST">
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
            </form>


            <!-- Таблица заказов пользователя -->
            <table class="table table-striped table-hover account__orders-table account__orders-table--text">
                <thead>
                    <tr>
                        <th scope="col">
                            Дата
                        </th>
                        <th scope="col">
                            Номер
                        </th>
                        <th scope="col">
                            Состав заказа
                        </th>
                        <th scope="col">
                            Сумма заказа
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Проходится по массиву заказов -->
                    <?php if (!empty($orderList)) : ?>
                        <?php foreach ($keys as $key) : ?>
                            <?php if (count($orderList[$key]) == 1) : ?>
                                <?php $groupedItemFirst = $orderList[$key][0]; ?>
                                <tr>
                                    <th scope="row">
                                        <?= $groupedItemFirst['order_date']; ?>
                                    </th>
                                    <td>
                                        <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                            <?= $groupedItemFirst['order_id']; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= $groupedItemFirst['title']; ?>
                                        *
                                        <?= $groupedItemFirst['quantity']; ?>
                                    </td>
                                    <td>
                                        <?= $groupedItemFirst['total_amount']; ?>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php $groupedItemFirst = $orderList[$key][0]; ?>
                                <tr>
                                    <th scope="row">
                                        <?= $groupedItemFirst['order_date']; ?>
                                    </th>
                                    <td>
                                        <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                            <?= $groupedItemFirst['order_id']; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php foreach ($orderList[$key] as $groupedSubItem) : ?>
                                            <?= $groupedSubItem['title']; ?>
                                            *
                                            <?= $groupedSubItem['quantity']; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?= $groupedItemFirst['total_amount']; ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>


            <!-- Навигация для таблицы заказов -->
            <?php if (count($pagination) > 1) : ?>
                <nav class="menu__nav account__orders-nav">
                    <?php $prevPageNumber = $currentPage - 1; ?>
                    <?php $nextPageNumber = $currentPage + 1; ?>

                    <?php if ($currentPage > 1) : ?>
                        <a href="./account.php?page=<?= $prevPageNumber; ?>" class="menu__nav-button menu__nav-button--prev">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </a>
                    <?php endif; ?>

                    <ul class="menu__nav-list">
                        <?php if ($currentPage == count($pagination) && $prevPageNumber != 1) : ?>
                            <a href="./account.php?page=<?= $prevPageNumber - 1; ?>" class="menu__nav-item">
                                <?= $prevPageNumber - 1; ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($prevPageNumber != 0) : ?>
                            <a href="./account.php?page=<?= $prevPageNumber; ?>" class="menu__nav-item">
                                <?= $prevPageNumber; ?>
                            </a>
                        <?php endif; ?>

                        <p class="menu__nav-item menu__nav-item--current">
                            <?= $currentPage; ?>
                        </p>

                        <?php if ($currentPage != count($pagination)) : ?>
                            <a href="./account.php?page=<?= $nextPageNumber; ?>" class="menu__nav-item">
                                <?= $nextPageNumber; ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($prevPageNumber == 0 && $nextPageNumber != count($pagination)) : ?>
                            <a href="./account.php?page=<?= $nextPageNumber + 1; ?>" class="menu__nav-item">
                                <?= $nextPageNumber + 1; ?>
                            </a>
                        <?php endif; ?>
                    </ul>

                    <?php if ($currentPage != count($pagination)) : ?>
                        <a href="./account.php?page=<?= $nextPageNumber; ?>" class="menu__nav-button menu__nav-button--next">
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