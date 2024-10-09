<!-- Страница заказ -->
<div class="page__order">
    <div class="container">

        <a href="<?= $backLink ?? ''; ?>" class="text basket__breadcrumbs">
            <svg class="basket__breadcrumbs-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                <path fill="currentColor" d="M4 15a1 1 0 0 0 1 1h19.586l-4.292 4.292a1 1 0 0 0 1.414 1.414l6-6a.99.99 0 0 0 .292-.702V15c0-.13-.026-.26-.078-.382a.99.99 0 0 0-.216-.324l-6-6a1 1 0 0 0-1.414 1.414L24.586 14H5a1 1 0 0 0-1 1z" />
            </svg>

            <span>
                <?= $backLinkName ?? ''; ?>
            </span>
        </a>


        <div class="page__order-column">
            <div>
                <img src="./img/svg/bag.svg" alt="" class="basket__icon">

                <p class="sub-title order__id">
                    #<?= $orderInfo['order_id'] ?? ''; ?>
                </p>
            </div>


            <div>
                <ul class="basket__list basket__list--second">
                    <?php if (!empty($productList)) : ?>
                        <?php foreach ($productList as $productItem) : ?>
                            <?php $productId = $productItem['product_id']; ?>
                            <li class="basket__item basket__item--second" data-product-id="<?= $productId ?>">
                                <div class="basket__item-wrapper">
                                    <img src="<?= $productItem['img']; ?>" alt="" class="basket__item-img">

                                    <div class="basket__item-info basket__item-info--second">
                                        <p class="sub-title basket__item-title">
                                            <?= $productItem['title']; ?>
                                        </p>

                                        <p class="text basket__item-price">
                                            <?= $productItem['description']; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="product-item__counter">
                                    <div class="product-item__counter-number-wrapper">
                                        <p class="product-item__counter-number">
                                            <?= $productItem['quantity']; ?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <div class="basket__order">
                    <p class="basket__order-text">
                        Сумма заказа:

                        <span class="basket__order-number">
                            <?= $orderInfo['total_amount'] ?? ''; ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>