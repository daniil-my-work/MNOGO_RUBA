<!-- Страница Меню -->
<div class="page__menu" id="page-menu">
    <!-- Вкладка -->
    <div class="account__orders-group page__menu-group container">
        <?php if (!is_null($categoryList)) : ?>
            <a class="button--basic account__orders-group-link" href="./constructor-poke.php">
                конструктор поке
            </a>

            <?php foreach ($categoryList as $category) : ?>
                <?php $isActive = $category['category_title'] === $activeCategory ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $isActive; ?>" href="./menu.php?category=<?= $category['category_title']; ?>">
                    <?= $category['category_name']; ?>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>


    <!-- Меню -->
    <div class="menu">
        <div class="menu__wrapper container">
            <h2 class="title menu__title">
                <?php if (!empty($products)) : ?>
                    <?= formatFirstLetter($categoryName['category_name'] ?? ''); ?>
                <?php else : ?>
                    <div class="error">
                        <p class="error__text">
                            В выбранной категории нет предложений
                        </p>
                    </div>
                <?php endif; ?>
            </h2>

            <ul class="menu__list">
                <?php if (!empty($products)) : ?>
                    <!-- Список продуктов -->
                    <?php foreach ($products as $product) : ?>
                        <?php $productId = $product['id']; ?>
                        <li class="menu__item" data-product-id="<?= $productId; ?>">
                            <?php $hiddenButton = isset($productsData[$productId]) ? 'hidden' : ''; ?>
                            <?php $hiddenCounter = !isset($productsData[$productId]) ? 'hidden' : ''; ?>

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
                                            <input class="product-item__counter-input" type="hidden" name="productId" value="<?= isset($productsData[$productId]) ? $productsData[$productId] : '0'; ?>">

                                            <span class="product-item__counter-action product-item__counter-action--minus">
                                                –
                                            </span>

                                            <p class="product-item__counter-number">
                                                <?= isset($productsData[$productId]) ? $productsData[$productId] : '0'; ?>
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
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Ссылка на корзину -->
    <!-- <a href="./basket.php" class="action__basket hidden">
        <svg class="action__basket-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
            <path d="M28 8h-4a1 1 0 0 0 0 2h4c.551 0 1 .449 1 1v2c0 .551-.449 1-1 1H4c-.551 0-1-.449-1-1v-2c0-.551.449-1 1-1h4a1 1 0 0 0 0-2H4c-1.654 0-3 1.346-3 3v2c0 1.34.889 2.464 2.104 2.848l1.64 12.301C4.957 29.748 6.387 31 8 31h16c1.613 0 3.043-1.252 3.255-2.85l1.64-12.302A2.994 2.994 0 0 0 31 13v-2c0-1.654-1.346-3-3-3zm-2.727 19.886C25.194 28.479 24.599 29 24 29H8c-.599 0-1.194-.521-1.273-1.115L5.142 16h21.716l-1.585 11.886z"></path>
            <path d="M9.628 12.929a1.001 1.001 0 0 0 1.301-.558l4-10a1 1 0 1 0-1.857-.743l-4 10a1 1 0 0 0 .556 1.301zm11.443-.557a1.003 1.003 0 0 0 1.3.556 1 1 0 0 0 .557-1.3l-4-10a1 1 0 0 0-1.857.743l4 10.001zM16 26a1 1 0 0 0 1-1v-5a1 1 0 0 0-2 0v5a1 1 0 0 0 1 1zm5 0a1 1 0 0 0 1-1v-5a1 1 0 0 0-2 0v5a1 1 0 0 0 1 1zm-10 0a1 1 0 0 0 1-1v-5a1 1 0 0 0-2 0v5a1 1 0 0 0 1 1z"></path>
        </svg>
    </a> -->
</div>