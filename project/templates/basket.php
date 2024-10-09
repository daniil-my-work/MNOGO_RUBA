<!-- Страница Корзина -->
<div class="page__basket" id="page-basket">
    <div class="page__basket-wrapper container">


        <!-- Форма заказа: Корзина -->
        <form class="page__basket-form" method="post" action="basket.php">
            <div>
                <a href="./menu.php" class="text basket__breadcrumbs">
                    <svg class="basket__breadcrumbs-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                        <path fill="currentColor" d="M4 15a1 1 0 0 0 1 1h19.586l-4.292 4.292a1 1 0 0 0 1.414 1.414l6-6a.99.99 0 0 0 .292-.702V15c0-.13-.026-.26-.078-.382a.99.99 0 0 0-.216-.324l-6-6a1 1 0 0 0-1.414 1.414L24.586 14H5a1 1 0 0 0-1 1z"></path>
                    </svg>

                    <span>
                        назад в меню
                    </span>
                </a>

                <img src="./img/svg/bag.svg" alt="" class="basket__icon">
            </div>

            <?php if ($productLength != 0) : ?>
                <div class="basket__selects">
                    <!-- Селект: способ доставки -->
                    <select class="form-select" id="delivery-type" name="delivery-type" aria-label="способ доставки">
                        <option value="default">Выберите способ доставки</option>
                        <option value="pickup" selected>Самовывоз</option>
                        <option value="delivery">Доставка</option>
                    </select>


                    <!-- Доставка -->
                    <div class="basket__delivery hidden" id="basket-delivery-list">
                        <div class="basket__cafe">
                            <span class="basket__delivery-name">
                                Адресс:
                            </span>

                            <div class="basket__delivery-wrapper w-100">
                                <?php $userCity = isset($address['user_address']) && $address['user_address'] !== null ? $address['user_address'] : ($userCity === null ? "" : "г " . $userCity); ?>
                                <input type="text" id="user_address" name="user_address" class="input basket__input mb-0" placeholder="Введите адрес" value="<?= $userCity; ?>" autocomplete="off">

                                <!-- Список адресов -->
                                <ul class="basket__delivery-list">
                                </ul>
                            </div>
                        </div>

                        <?php $classHidden = isset($errors['user_address']) ? '' : 'hidden'; ?>
                        <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                            <?= $errors['user_address'] ?? ''; ?>
                        </span>


                        <div class="basket__cafe basket__cafe--second">
                            <span class="basket__delivery-name">
                                Подъезд:
                            </span>

                            <?php $entrance = isset($address['entrance']) && $address['entrance'] !== null ? $address['entrance'] : ''; ?>
                            <input type="number" id="entrance" name="entrance" class="input basket__input basket__cafe-input mb-0" placeholder="4" value="<?= $entrance; ?>">
                        </div>

                        <?php $classHidden = isset($errors['entrance']) ? '' : 'hidden'; ?>
                        <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                            <?= $errors['entrance'] ?? ''; ?>
                        </span>


                        <div class="basket__cafe basket__cafe--second">
                            <span class="basket__delivery-name">
                                Номер квартиры:
                            </span>

                            <?php $apartment = isset($address['apartment']) && $address['apartment'] !== null ? $address['apartment'] : ''; ?>
                            <input type="number" id="apartment" name="apartment" class="input basket__input basket__cafe-input mb-0" placeholder="100" value="<?= $apartment; ?>">
                        </div>

                        <?php $classHidden = isset($errors['apartment']) ? '' : 'hidden'; ?>
                        <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                            <?= $errors['apartment'] ?? ''; ?>
                        </span>


                        <?php $classHidden = isset($errors['floor']) ? '' : 'hidden'; ?>
                        <div class="basket__cafe basket__cafe--second <?= $classHidden === 'hidden' ? 'mb-3' : 'mb-0'; ?>">
                            <span class="basket__delivery-name">
                                Этаж:
                            </span>

                            <?php $floor = isset($address['floor']) && $address['floor'] !== null ? $address['floor'] : ''; ?>
                            <input type="number" id="floor" name="floor" class="input basket__input basket__cafe-input mb-0" placeholder="6" value="<?= $floor; ?>">
                        </div>

                        <span class="reg__form-input-wrapper-error <?= $classHidden; ?> mb-3">
                            <?= $errors['floor'] ?? ''; ?>
                        </span>

                        <div class="form-floating">
                            <textarea class="form-control basket__cafe-textArea" placeholder="Комметарий" id="basket__cafe-textArea" name="order_comment-user"></textarea>
                            <label for="basket__cafe-textArea">Комметарий</label>
                        </div>
                    </div>


                    <!-- Самовывоз -->
                    <div class="basket__pickup hidden" id="basket-cafe-list">
                        <?php $classHidden = isset($errors['cafe_address']) ? '' : 'hidden'; ?>
                        <div class="basket__cafe <?= $classHidden === 'hidden' ? 'mb-3' : 'mb-0'; ?> ">
                            Адресс:

                            <select class="form-select basket__cafe-select" name="cafe_address" aria-label="ресторан получения">
                                <option value="default" selected>Выберите ресторан получения</option>

                                <?php if (!is_null($cafeList)) : ?>
                                    ''
                                <?php endif; ?>
                                <?php foreach ($cafeList as $cafe) : ?>
                                    <option value="<?= $cafe['id']; ?>">
                                        <?= $cafe['address_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <span class="reg__form-input-wrapper-error mb-3 <?= $classHidden; ?>">
                            <?= $errors['cafe_address'] ?? ''; ?>
                        </span>


                        <div class="form-floating">
                            <textarea class="form-control basket__cafe-textArea" placeholder="Комметарий" id="basket__cafe-textArea" name="order_comment-cafe"></textarea>
                            <label for="basket__cafe-textArea">Комметарий</label>
                        </div>
                    </div>
                </div>


                <!-- Список товаров -->
                <ul class="basket__list">
                    <?php if (!empty($products)) : ?>
                        <?php foreach ($products as $product) : ?>
                            <?php $productItem = $product['item']['quantity']; ?>
                            <?php $productId = $productItem['productId']; ?>
                            <?php $tableName = $product['table']; ?>
                            <?php $categoryId = $productItem['category_id']; ?>

                            <li class="basket__item" data-product-id="<?= $productId ?>" data-table-name="<?= $tableName ?>">
                                <div class="basket__item-content">
                                    <div class="basket__item-top">
                                        <img src="<?= $productItem['img']; ?>" alt="" class="basket__item-img">
                                    </div>

                                    <div class="product-item__counter">
                                        <div class="product-item__counter-number-wrapper">
                                            <input class="product-item__counter-input" type="hidden" name="productId" value="<?= $productsData[$tableName][$productId]; ?>">
                                            <span class="product-item__counter-action product-item__counter-action--minus">
                                                –
                                            </span>

                                            <p class="product-item__counter-number">
                                                <?= isset($product['quantity']) ? $product['quantity'] : '0'; ?>
                                            </p>

                                            <span class="product-item__counter-action product-item__counter-action--plus">
                                                +
                                            </span>
                                        </div>

                                        <button class="product-item__counter-button product-item__counter-button--basket" type="button">
                                            <svg class="product-item__counter-button-icon--basket" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                                                <path d="M56.521 10.182a3.522 3.522 0 0 0-3.518-3.518H42.3V3.008C42.3 1.35 41.048 0 39.509 0h-15.02C22.952 0 21.7 1.35 21.7 3.008v3.656H10.997a3.522 3.522 0 0 0-3.518 3.518c0 1.768 1.316 3.221 3.018 3.465l5.188 45.877A5.042 5.042 0 0 0 20.698 64h22.604a5.04 5.04 0 0 0 5.012-4.477l5.189-45.877c1.701-.244 3.018-1.697 3.018-3.464zM23.64 3.008c0-.578.389-1.068.85-1.068h15.02c.461 0 .852.49.852 1.068v3.656H23.64V3.008zm22.746 56.297a3.102 3.102 0 0 1-3.084 2.756H20.698a3.104 3.104 0 0 1-3.086-2.756l-5.156-45.607h39.088l-5.158 45.607zm6.617-47.547H10.997a1.578 1.578 0 0 1 0-3.154h42.006a1.58 1.58 0 0 1 1.578 1.578 1.58 1.58 0 0 1-1.578 1.576z" />
                                                <path d="M43.841 18.469a.96.96 0 0 0-1.049.883l-3.184 36.846a.972.972 0 0 0 .967 1.053.967.967 0 0 0 .965-.885l3.184-36.846a.97.97 0 0 0-.883-1.051zm-23.684 0a.97.97 0 0 0-.881 1.051l3.184 36.846a.967.967 0 0 0 .965.885c.027 0 .055 0 .084-.002a.973.973 0 0 0 .883-1.051l-3.184-36.846a.968.968 0 0 0-1.051-.883zm11.842-.004a.97.97 0 0 0-.969.971v36.846a.97.97 0 0 0 1.94 0V19.436a.97.97 0 0 0-.971-.971z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <p class="sub-title basket__item-title mb-2">
                                    <?= $productItem['title']; ?>
                                </p>

                                <?php if ($categoryId === '4') : ?>
                                    <div class="d-flex">
                                        <span class="text me-2">
                                            Выберите основу:
                                        </span>

                                        <!-- Основа для вока -->
                                        <select class="basket__item-component form-select mb-3" id="wok-base" name="garnir" aria-label="Основа для вок">
                                            <option value="default">
                                                Не выбрано
                                            </option>

                                            <?php foreach ($noodleComponents as $key => $value) : ?>
                                                <option value="<?= $key; ?>">
                                                    <?= $value; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="d-flex">
                                        <span class="text me-2">
                                            Выберите соус:
                                        </span>

                                        <!-- Соус для вока -->
                                        <select class="basket__item-component form-select mb-3" id="garnir" name="garnir" aria-label="Соус для вок">
                                            <option value="default">
                                                Не выбрано
                                            </option>

                                            <?php foreach ($sauceComponents as $key => $value) : ?>
                                                <option value="<?= $key; ?>">
                                                    <?= $value; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>


                                <div class="basket__item-info">
                                    <p class="text basket__item-description">
                                        <?= $productItem['description']; ?>
                                    </p>

                                    <p class="text basket__item-price">
                                        Цена: <?= formatSum($productItem['price']); ?>
                                    </p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>



            <div class="basket__order">
                <p class="basket__order-text">
                    <?php if ($productLength == 0) : ?>
                        Ваша корзина пуста
                    <?php else : ?>
                        Ваш заказ:

                        <span class="basket__order-number">
                            <?= formatSum($fullPrice); ?>
                        </span>
                    <?php endif; ?>
                </p>

                <?php $isHidden = $productLength == 0 ? 'hidden' : ''; ?>
                <button type="submit" class="basket__order-button <?= $isHidden; ?>">
                    Заказать
                </button>
            </div>
        </form>
    </div>
</div>