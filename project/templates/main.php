<!-- Главная страница -->
<div class="page__main" id="page-main">
    <!-- Предложение -->
    <div class="offer">
        <div class="offer__wrapper container">
            <a class="offer__button" href="./menu.php">
                Меню
            </a>

            <a class="offer__button" href="./constructor-poke.php">
                Создать поке
            </a>
        </div>
    </div>


    <!-- Баннер -->
    <div class="banner">
        <div class="banner__wrapper">
            <img src="./img/assets/banner-1.jpg" alt="" class="banner__img">
        </div>
    </div>


    <!-- Меню -->
    <div class="menu">
        <div class="menu__wrapper container">
            <h2 class="title menu__title">
                Популярные блюда
            </h2>


            <!-- Меню -->
            <?php if (!is_null($products)) : ?>
                <ul class="menu__list">
                    <?php foreach ($products as $product) : ?>
                        <?php $productId = $product['id']; ?>
                        <?php $categoryId = $product['category_id']; ?>

                        <?php
                        foreach ($productsData as $productData) {
                            if ($productData['productId'] == $productId) {
                                $uniqueId = $productData['uniqueId'];

                                $hiddenButton = isset($productsData['uniqueId']) ? 'hidden' : '';
                                $hiddenCounter = !isset($productsData['uniqueId']) ? 'hidden' : '';

                                break; // Завершаем цикл, если нашли совпадение
                            }
                        }
                        ?>

                        <li class="menu__item" data-product-id="<?= $productId ?>" data-table-name="menu" data-category-id="<?= $categoryId; ?>" data-unique-id="<?= $uniqueId; ?>">
                            <?php $hiddenButton = isset($productsData[$productId]) ? 'hidden' : ''; ?>
                            <?php $hiddenCounter = !isset($productsData[$productId]) ? 'hidden' : ''; ?>

                            <!-- <img src="getAbsoluteImgPath($product['img']); ?>" alt="" class="menu__item-img"> -->
                            <img src="<?= $product['img']; ?>" alt="" class="menu__item-img">

                            <div class="menu__item-content">
                                <h3 class="sub-title menu__item-title">
                                    <?= $product['title']; ?>
                                </h3>

                                <p class="text menu__item-text">
                                    <?= $product['description']; ?>
                                </p>

                                <div class="menu__item-info">
                                    <p class="text menu__item-price">
                                        <?= $product['price']; ?>
                                    </p>

                                    <div class="product-item__counter">
                                        <button class="product-item__counter-button <?= $hiddenButton; ?>" type="button">
                                            В корзину
                                        </button>

                                        <div class="product-item__counter-number-wrapper <?= $hiddenCounter; ?>">
                                            <?php
                                            foreach ($productsData as $productData) {
                                                if ($productData['productId'] == $productId) {
                                                    $quantity = $productData['quantity'] ? $productData['quantity'] : null;
                                                    break; // Завершаем цикл, если нашли совпадение
                                                }
                                            }
                                            ?>
                                            <input class="product-item__counter-input" type="hidden" name="productId" value="<?= isset($quantity) ? $quantity : '0'; ?>">

                                            <span class="product-item__counter-action product-item__counter-action--minus">
                                                –
                                            </span>

                                            <p class="product-item__counter-number">
                                                <?= isset($quantity) ? $quantity : '0'; ?>
                                            </p>

                                            <span class="product-item__counter-action product-item__counter-action--plus">
                                                +
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <nav class="menu__nav">
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
                    </ul>

                    <button class="menu__nav-button menu__nav-button--next">
                        <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                            <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                        </svg>
                    </button>
                </nav>
            <?php else : ?>
                <div class="error">
                    <p class="error__text">
                        Список популярных блюд пуст
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <!-- О компании -->
    <div class="about">
        <div class="about__wrapper container">
            <img src="../../img/assets/about-1.png" alt="" class="about__img">

            <h2 class="title about__title">
                О компании
            </h2>

            <p class="text about__text">
                Здоровый и вкусный завтрак, быстрый обед или ужин со свежими и экзотическими ароматами. В "Много
                Рыбы" у нас есть все! Ознакомьтесь с нашим меню, разработанным искусными шеф-поварами с
                использованием настоящих сезонных ингредиентов, и полюбите вкуснейший Poke Bowl. Либо выберите
                одно из наших фирменных блюд, либо создайте свой идеальный Poke!
            </p>
        </div>
    </div>


    <!-- Ссылка на корзину -->
    <!-- <a href="./basket.php" class="action__basket hidden">
        <svg class="action__basket-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
            <path d="M28 8h-4a1 1 0 0 0 0 2h4c.551 0 1 .449 1 1v2c0 .551-.449 1-1 1H4c-.551 0-1-.449-1-1v-2c0-.551.449-1 1-1h4a1 1 0 0 0 0-2H4c-1.654 0-3 1.346-3 3v2c0 1.34.889 2.464 2.104 2.848l1.64 12.301C4.957 29.748 6.387 31 8 31h16c1.613 0 3.043-1.252 3.255-2.85l1.64-12.302A2.994 2.994 0 0 0 31 13v-2c0-1.654-1.346-3-3-3zm-2.727 19.886C25.194 28.479 24.599 29 24 29H8c-.599 0-1.194-.521-1.273-1.115L5.142 16h21.716l-1.585 11.886z" />
            <path d="M9.628 12.929a1.001 1.001 0 0 0 1.301-.558l4-10a1 1 0 1 0-1.857-.743l-4 10a1 1 0 0 0 .556 1.301zm11.443-.557a1.003 1.003 0 0 0 1.3.556 1 1 0 0 0 .557-1.3l-4-10a1 1 0 0 0-1.857.743l4 10.001zM16 26a1 1 0 0 0 1-1v-5a1 1 0 0 0-2 0v5a1 1 0 0 0 1 1zm5 0a1 1 0 0 0 1-1v-5a1 1 0 0 0-2 0v5a1 1 0 0 0 1 1zm-10 0a1 1 0 0 0 1-1v-5a1 1 0 0 0-2 0v5a1 1 0 0 0 1 1z" />
        </svg>
    </a> -->
</div>