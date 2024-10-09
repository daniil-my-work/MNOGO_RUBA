<div class="page__poke" id="page-poke">
    <div class="container">
        <a href="./menu.php" class="text basket__breadcrumbs">
            <svg class="basket__breadcrumbs-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                <path fill="currentColor" d="M4 15a1 1 0 0 0 1 1h19.586l-4.292 4.292a1 1 0 0 0 1.414 1.414l6-6a.99.99 0 0 0 .292-.702V15c0-.13-.026-.26-.078-.382a.99.99 0 0 0-.216-.324l-6-6a1 1 0 0 0-1.414 1.414L24.586 14H5a1 1 0 0 0-1 1z">
                </path>
            </svg>

            <span>
                меню
            </span>
        </a>

        <div class="page__poke-error hidden">
            <h3 class="page__poke-error-title">
                Ошибка:
            </h3>

            <ul class="page__poke-error-list">
                <li class="page__poke-error-item">
                    Выберете 1 топпинг
                </li>
            </ul>
        </div>

        <!-- Меню -->
        <div class="constructor-poke">
            <div class="constructor-poke__wrapper">
                <h2 class="title menu__title">
                    Конструктор Поке
                </h2>

                <form action="./constructor-poke.php" method="post" class="constructor-poke__form">
                    <div class="constructor-poke__list">
                        <!-- Протеин -->
                        <div class="constructor-poke__component">
                            <div class="constructor-poke__step">
                                <span class="constructor-poke__step-number">
                                    1
                                </span>

                                <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step1.png" alt="">
                            </div>

                            <h3 class="sub-title constructor-poke__title">
                                Выберите протеин
                            </h3>

                            <select class="form-select constructor-poke__select" id="constructor-poke__select--protein" aria-label="" name="protein" required>
                                <option value="">
                                    Не выбран
                                </option>

                                <?php if (!is_null($proteinList)) : ?>
                                    <?php foreach ($proteinList as $proteinItem) : ?>
                                        <?php $isSelected = !empty($createdPoke['protein']) && $proteinItem['id'] === $createdPoke['protein'] ? 'selected' : ''; ?>
                                        <option value="<?= $proteinItem['id']; ?>" data-price="<?= $proteinItem['price']; ?>" <?= $isSelected; ?>>
                                            <?= $proteinItem['title']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                            <?php $classHidden = isset($errors['protein']) ? '' : 'hidden'; ?>
                            <span class="constructor-poke-error <?= $classHidden; ?>">
                                <?= $errors['protein'] ?? ''; ?>
                            </span>
                        </div>

                        <!-- Основа -->
                        <div class="constructor-poke__component">
                            <div class="constructor-poke__step">
                                <span class="constructor-poke__step-number">
                                    2
                                </span>

                                <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step2.png" alt="">
                            </div>

                            <h3 class="sub-title constructor-poke__title">
                                Выберите основу
                            </h3>

                            <select class="form-select constructor-poke__select constructor-poke__select--required" aria-label="" name="base" required>
                                <option value="" selected>
                                    Не выбран
                                </option>

                                <?php if (!is_null($baseList)) : ?>
                                    <?php foreach ($baseList as $baseItem) : ?>
                                        <?php $isSelected = !empty($createdPoke['base']) && $baseItem['id'] === $createdPoke['base'] ? 'selected' : ''; ?>
                                        <option value="<?= $baseItem['id']; ?>" <?= $isSelected; ?>>
                                            <?= $baseItem['title']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                            <?php $classHidden = isset($errors['base']) ? '' : 'hidden'; ?>
                            <span class="constructor-poke-error <?= $classHidden; ?>">
                                <?= $errors['base'] ?? ''; ?>
                            </span>
                        </div>

                        <!-- Наполнитель -->
                        <div class="constructor-poke__component">
                            <div class="constructor-poke__step">
                                <span class="constructor-poke__step-number">
                                    3
                                </span>

                                <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step3.png" alt="">
                            </div>

                            <h3 class="sub-title constructor-poke__title">
                                Наполнитель
                            </h3>


                            <fieldset class="text constructor-poke-shema">
                                <div class="constructor-poke-shema-item" data-shema-poke="1">
                                    <input type="radio" class="constructor-poke-item-radio" id="shema1" name="shema" value="1">
                                    <label for="shema1">
                                        5 наполнителей + 1 топинг
                                    </label>
                                </div>

                                <div class="constructor-poke-shema-item" data-shema-poke="2">
                                    <input type="radio" class="constructor-poke-item-radio" id="shema2" name="shema" value="2">
                                    <label for="shema2">
                                        3 наполнителя + 2 топинга
                                    </label>
                                </div>
                            </fieldset>

                            <?php $classHidden = isset($errors['filler']) ? '' : 'hidden'; ?>
                            <span class="constructor-poke-error constructor-poke-error--second <?= $classHidden; ?>">
                                <?= $errors['filler'] ?? ''; ?>
                            </span>

                            <div class="text constructor-poke-info">
                                <span class="constructor-poke-info-text">
                                    Наполнители
                                </span>

                                <span class="constructor-poke-info-number" id="fillerCounter">
                                    / Осталось 5 из 5
                                </span>
                            </div>

                            <ul class="constructor-poke-list">
                                <?php if (!is_null($fillerList)) : ?>
                                    <?php foreach ($fillerList as $fillerItem) : ?>
                                        <li class="constructor-poke-item">
                                            <?php $isChecked = !empty($createdPoke['filler']) && in_array($fillerItem['id'], $createdPoke['filler']) ? 'checked' : ''; ?>
                                            <input type="checkbox" class="constructor-poke-item-checkbox constructor-poke-item-checkbox--filler" id="<?= $fillerItem['title']; ?>" name="filler[]" value="<?= $fillerItem['id']; ?>" <?= $isChecked; ?>>

                                            <label for="<?= $fillerItem['title']; ?>">
                                                <?= $fillerItem['title']; ?>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- Топпинг -->
                        <div class="constructor-poke__component">
                            <div class="constructor-poke__component-column-1">
                                <div class="constructor-poke__step">
                                    <span class="constructor-poke__step-number">
                                        4
                                    </span>

                                    <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step4.png" alt="">
                                </div>

                                <h3 class="sub-title constructor-poke__title">
                                    Топпинг
                                </h3>
                            </div>

                            <div>

                                <?php $classHidden = isset($errors['topping']) ? '' : 'hidden'; ?>
                                <span class="constructor-poke-error constructor-poke-error--second <?= $classHidden; ?>">
                                    <?= $errors['topping'] ?? ''; ?>
                                </span>

                                <div class="constructor-poke-info">
                                    <span class="constructor-poke-info-text">
                                        Топпинг
                                    </span>

                                    <span class="constructor-poke-info-number" id="topingCounter">
                                        / Осталось 1 из 1
                                    </span>
                                </div>


                                <ul class="constructor-poke-list constructor-poke-list--toping">
                                    <?php if (!is_null($toppingList)) : ?>
                                        <?php foreach ($toppingList as $toppingItem) : ?>
                                            <li class="constructor-poke-item">
                                                <?php $isChecked = !empty($createdPoke['topping']) && in_array($toppingItem['id'], $createdPoke['topping']) ? 'checked' : ''; ?>
                                                <input type="checkbox" class="constructor-poke-item-checkbox constructor-poke-item-checkbox--toping" id="<?= $toppingItem['title']; ?>" name="topping[]" value="<?= $toppingItem['id']; ?>" <?= $isChecked; ?>>

                                                <label for="<?= $toppingItem['title']; ?>">
                                                    <?= $toppingItem['title']; ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>

                        <!-- Соус -->
                        <div class="constructor-poke__component">
                            <div class="constructor-poke__step">
                                <span class="constructor-poke__step-number">
                                    5
                                </span>

                                <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step5.png" alt="">
                            </div>

                            <h3 class="sub-title constructor-poke__title">
                                Соус
                            </h3>

                            <select class="form-select constructor-poke__select constructor-poke__select--required" aria-label="" name="sauce" required>
                                <option value="" selected>
                                    Не выбран
                                </option>

                                <?php if (!is_null($sauceList)) : ?>
                                    <?php foreach ($sauceList as $sauceItem) : ?>
                                        <?php $isSelected = !empty($createdPoke['sauce']) && $sauceItem['id'] === $createdPoke['sauce'] ? 'selected' : ''; ?>
                                        <option value="<?= $sauceItem['id']; ?>" <?= $isSelected; ?>>
                                            <?= $sauceItem['title']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                            <?php $classHidden = isset($errors['sauce']) ? '' : 'hidden'; ?>
                            <span class="constructor-poke-error <?= $classHidden; ?>">
                                <?= $errors['sauce'] ?? ''; ?>
                            </span>
                        </div>

                        <!-- Хруст -->
                        <div class="constructor-poke__component">
                            <div class="constructor-poke__step">
                                <span class="constructor-poke__step-number">
                                    6
                                </span>

                                <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step6.png" alt="">
                            </div>

                            <h3 class="sub-title constructor-poke__title">
                                Хруст
                            </h3>

                            <select class="form-select constructor-poke__select constructor-poke__select--required" aria-label="" name="crunch" required>
                                <option value="" selected>
                                    Не выбран
                                </option>

                                <?php if (!is_null($crunchList)) : ?>
                                    <?php foreach ($crunchList as $crunchItem) : ?>
                                        <?php $isSelected = !empty($createdPoke['crunch']) && $crunchItem['id'] === $createdPoke['crunch'] ? 'selected' : ''; ?>
                                        <option value="<?= $crunchItem['id']; ?>" <?= $isSelected; ?>>
                                            <?= $crunchItem['title']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                            <?php $classHidden = isset($errors['crunch']) ? '' : 'hidden'; ?>
                            <span class="constructor-poke-error <?= $classHidden; ?>">
                                <?= $errors['crunch'] ?? ''; ?>
                            </span>
                        </div>
                    </div>


                    <div class="constructor-poke__add">
                        <h3 class="mb-3">
                            Дополнительно
                        </h3>

                        <p class="text">
                            Если хотите вы можете добавить дополнительные ингредиенты
                        </p>

                        <label class="sub-title constructor-poke__add-label">
                            Протеин

                            <span class="constructor-poke__add-price" id="constructor-poke__add-price--protein">
                            </span>
                        </label>

                        <?php $classHidden = isset($errors['proteinAdd']) ? '' : 'hidden'; ?>
                        <span class="constructor-poke-error constructor-poke-error--second <?= $classHidden; ?>">
                            <?= $errors['proteinAdd'] ?? ''; ?>
                        </span>

                        <select class="form-select constructor-poke__select" id="constructor-poke__select-proteinAdd" aria-label="" name="proteinAdd">
                            <option value="" selected>
                                Не выбран
                            </option>

                            <?php if (!is_null($proteinAddList)) : ?>
                                <?php foreach ($proteinAddList as $proteinAddItem) : ?>
                                    <option value="<?= $proteinAddItem['id']; ?>" data-price="<?= $proteinAddItem['price']; ?>">
                                        <?= $proteinAddItem['title']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>


                        <div class="constructor-poke__add-column-1">
                            <div>
                                <label class="sub-title constructor-poke__add-label">
                                    Наполнитель

                                    <span class="constructor-poke__add-price" id="constructor-poke__add-price--filler">
                                    </span>
                                </label>

                                <?php $classHidden = isset($errors['fillerAdd']) ? '' : 'hidden'; ?>
                                <span class="constructor-poke-error constructor-poke-error--second <?= $classHidden; ?>">
                                    <?= $errors['fillerAdd'] ?? ''; ?>
                                </span>

                                <ul class="constructor-poke-list constructor-poke-list--second" id="constructor-poke__fillerAdd">
                                    <?php if (!is_null($fillerList)) : ?>
                                        <?php foreach ($fillerList as $fillerItem) : ?>
                                            <li class="constructor-poke-item" data-price="<?= $fillerItem['price']; ?>">
                                                <input type="checkbox" class="constructor-poke-item-checkbox constructor-poke-item-checkbox--fillerAdd" id="<?= $fillerItem['title']; ?>-add" name="fillerAdd[]" value="<?= $fillerItem['id']; ?>" data-price="<?= $fillerItem['price']; ?>">

                                                <label for="<?= $fillerItem['title']; ?>-add">
                                                    <?= $fillerItem['title']; ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>


                            <div>
                                <label class="sub-title constructor-poke__add-label">
                                    Топпинг

                                    <span class="constructor-poke__add-price" id="constructor-poke__add-price--topping">
                                    </span>
                                </label>

                                <?php $classHidden = isset($errors['toppingAdd']) ? '' : 'hidden'; ?>
                                <span class="constructor-poke-error constructor-poke-error--second <?= $classHidden; ?>">
                                    <?= $errors['toppingAdd'] ?? ''; ?>
                                </span>

                                <ul class="constructor-poke-list constructor-poke-list--second" id="constructor-poke__toppingAdd">
                                    <?php if (!is_null($toppingList)) : ?>
                                        <?php foreach ($toppingList as $toppingItem) : ?>
                                            <li class="constructor-poke-item" data-price="<?= $toppingItem['price']; ?>">
                                                <input type="checkbox" class="constructor-poke-item-checkbox constructor-poke-item-checkbox--toppingAdd" id="<?= $toppingItem['title']; ?>-add" name="toppingAdd[]" value="<?= $toppingItem['id']; ?>" data-price="<?= $toppingItem['price']; ?>">

                                                <label for="<?= $toppingItem['title']; ?>-add">
                                                    <?= $toppingItem['title']; ?>
                                                </label>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>


                        <div class="constructor-poke__add-column-2">
                            <div>
                                <label class="sub-title constructor-poke__add-label">
                                    Соус

                                    <span class="constructor-poke__add-price" id="constructor-poke__add-price--sauce">
                                    </span>
                                </label>

                                <?php $classHidden = isset($errors['sauceAdd']) ? '' : 'hidden'; ?>
                                <span class="constructor-poke-error constructor-poke-error--second <?= $classHidden; ?>">
                                    <?= $errors['sauceAdd'] ?? ''; ?>
                                </span>

                                <select class="form-select constructor-poke__select" id="constructor-poke__select-sauceAdd" aria-label="" name="sauceAdd">
                                    <option value="" selected>
                                        Не выбран
                                    </option>

                                    <?php if (!is_null($sauceList)) : ?>
                                        <?php foreach ($sauceList as $sauceItem) : ?>
                                            <option value="<?= $sauceItem['id']; ?>" data-price="<?= $sauceItem['price']; ?>">
                                                <?= $sauceItem['title']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div>
                                <label class="sub-title constructor-poke__add-label">
                                    Хруст

                                    <span class="constructor-poke__add-price" id="constructor-poke__add-price--crunch">
                                    </span>
                                </label>

                                <?php $classHidden = isset($errors['crunchAdd']) ? '' : 'hidden'; ?>
                                <span class="constructor-poke-error constructor-poke-error--second <?= $classHidden; ?>">
                                    <?= $errors['crunchAdd'] ?? ''; ?>
                                </span>

                                <select class="form-select constructor-poke__select" id="constructor-poke__select-crunchAdd" aria-label="" name="crunchAdd">
                                    <option value="" selected>
                                        Не выбран
                                    </option>

                                    <?php if (!is_null($crunchList)) : ?>
                                        <?php foreach ($crunchList as $crunchItem) : ?>
                                            <option value="<?= $crunchItem['id']; ?>" data-price="<?= $crunchItem['price']; ?>">
                                                <?= $crunchItem['title']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="basket__order">
                        <p class="basket__order-text">
                            Ваш заказ:

                            <span class="basket__order-number">
                            </span>

                            <input type="hidden" id="total-price" name="total-price" value="">
                        </p>

                        <button type="submit" class="button--second constructor-poke__button" id="constructor-poke-button">
                            Добавить в корзину
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ссылка на корзину -->
        <!-- <a href="./basket.php" class="action__basket  $totalLength > 0 ? '' : 'hidden'; ?>">
            <svg class="action__basket-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                <path d="M28 8h-4a1 1 0 0 0 0 2h4c.551 0 1 .449 1 1v2c0 .551-.449 1-1 1H4c-.551 0-1-.449-1-1v-2c0-.551.449-1 1-1h4a1 1 0 0 0 0-2H4c-1.654 0-3 1.346-3 3v2c0 1.34.889 2.464 2.104 2.848l1.64 12.301C4.957 29.748 6.387 31 8 31h16c1.613 0 3.043-1.252 3.255-2.85l1.64-12.302A2.994 2.994 0 0 0 31 13v-2c0-1.654-1.346-3-3-3zm-2.727 19.886C25.194 28.479 24.599 29 24 29H8c-.599 0-1.194-.521-1.273-1.115L5.142 16h21.716l-1.585 11.886z"></path>
                <path d="M9.628 12.929a1.001 1.001 0 0 0 1.301-.558l4-10a1 1 0 1 0-1.857-.743l-4 10a1 1 0 0 0 .556 1.301zm11.443-.557a1.003 1.003 0 0 0 1.3.556 1 1 0 0 0 .557-1.3l-4-10a1 1 0 0 0-1.857.743l4 10.001zM16 26a1 1 0 0 0 1-1v-5a1 1 0 0 0-2 0v5a1 1 0 0 0 1 1zm5 0a1 1 0 0 0 1-1v-5a1 1 0 0 0-2 0v5a1 1 0 0 0 1 1zm-10 0a1 1 0 0 0 1-1v-5a1 1 0 0 0-2 0v5a1 1 0 0 0 1 1z"></path>
            </svg>
        </a> -->
    </div>
</div>