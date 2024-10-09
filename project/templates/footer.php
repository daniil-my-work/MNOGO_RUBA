<footer class="footer">
    <div class="footer__wrapper">
        <header class="footer__top">
            <div class="footer__top-wrapper container">
                <h3 class="sub-title footer__top-title">
                    Открой свой poke-room «Много рыбы»
                </h3>

                <button type="button" class="footer__top-button">
                    Подробнее
                </button>
            </div>
        </header>

        <!-- Контент с колонкаи меню и адрессов -->
        <div class="footer__content container">
            <img src="../../img/svg/logo-footer.svg" alt="" class="footer__logo">

            <div class="footer__column-wrapper">
                <!-- Меню -->
                <div class="footer__column">
                    <h3 class="sub-title footer__column-title">
                        Меню poke-room
                    </h3>

                    <?php if (!is_null($categoryList)) : ?>
                        <ul class="footer__list">
                            <?php foreach ($categoryList as $category) : ?>
                                <li class="footer__item">
                                    <a href="./menu.php?category=<?= $category['category_title']; ?>" class="footer__item-link">
                                        <?= $category['category_name']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- О нас -->
                <div class="footer__column">
                    <h3 class="sub-title footer__column-title">
                        О нас
                    </h3>

                    <p class="text footer__column-text">
                        ....
                    </p>
                </div>

                <!-- Адреса -->
                <div class="footer__column">
                    <h3 class="sub-title footer__column-title">
                        Адреса
                    </h3>

                    <ul class="footer__list footer__list--second">
                        <li class="footer__item">
                            <p class="text footer__item-text">
                                г. Ярославль, Свободы 52/39
                            </p>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <footer class="footer__bottom">
            <div class="footer__bottom-wrapper container">
                <p class="text footer__bottom-text">
                    © poke-room «Много рыбы»
                </p>

                <a class="text footer__bottom-text" href="#">
                    Политика обработки персональных данных
                </a>
            </div>
        </footer>
    </div>
</footer>