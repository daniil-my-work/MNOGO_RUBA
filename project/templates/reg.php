<!-- Страница регистрации -->
<div class="page__reg">
    <div class="reg container">
        <!-- Выбор города -->
        <div class="reg__radio hidden">
            <div class="reg__radio-wrapper">
                <h2 class="title reg__radio-title">
                    Выберите ваш город:
                </h2>

                <div class="reg__radio-content">
                    <div class="reg__form-radioButton">
                        <input id="radio-1" type="radio" name="radio" value="1" checked>
                        <label for="radio-1">
                            Ярославль
                        </label>
                    </div>

                    <div class="reg__form-radioButton">
                        <input id="radio-2" type="radio" name="radio" value="2">
                        <label for="radio-2">
                            Рыбинск
                        </label>
                    </div>
                </div>
            </div>
        </div>


        <h2 class="title">Регистрация</h2>

        <!-- Форма регистрации -->
        <form class="reg__form" action="#" method="post" autocomplete="on">
            <div class="reg__form-input-wrapper">
                <label for="user_name">Имя:</label>
                <input class="reg__form-input" type="text" id="user_name" name="user_name" placeholder="Даниил" value="<?= isset($_POST['user_name']) ? $_POST['user_name'] : ''; ?>" required>

                <?php $classHidden = isset($errors['user_name']) ? '' : 'hidden'; ?>
                <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                    <?= $errors['user_name'] ?? ''; ?>
                </span>
            </div>

            <div class="reg__form-input-wrapper">
                <label for="user_email">Почта:</label>
                <input class="reg__form-input" type="email" id="user_email" name="user_email" placeholder="test@yandex.ru" value="<?= isset($_POST['user_email']) ? $_POST['user_email'] : ''; ?>" required>

                <?php $classHidden = isset($errors['user_email']) ? '' : 'hidden'; ?>
                <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                    <?= $errors['user_email'] ?? ''; ?>
                </span>
            </div>

            <div class="reg__form-input-wrapper">
                <label for="user_telephone">Номер телефона:</label>
                <input class="reg__form-input reg__form-input--tel" type="tel" id="user_phone" name="user_phone" placeholder="+7(980) 705 70 02" value="<?= isset($_POST['user_phone']) ? $_POST['user_phone'] : ''; ?>">

                <?php $classHidden = isset($errors['user_phone']) ? '' : 'hidden'; ?>
                <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                    <?= $errors['user_phone'] ?? ''; ?>
                </span>
            </div>

            <div class="reg__form-input-wrapper">
                <label for="user_password">Пароль:</label>
                <input class="reg__form-input" type="password" id="user_password" name="user_password" placeholder="***" value="<?= isset($_POST['user_password']) ? $_POST['user_password'] : ''; ?>">

                <?php $classHidden = isset($errors['user_password']) ? '' : 'hidden'; ?>
                <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                    <?= $errors['user_password'] ?? ''; ?>
                </span>
            </div>

            <!-- <div class="reg__form-address-wrapper">
                <label for="address">Адрес:</label>

                <input class="reg__form-input reg__form-input--adress" type="text" id="address" name="address" pattern="[0-9]{10}" placeholder="ул. Антонова ... ">

                <span class="reg__form-input-wrapper-error hidden">
                    Сообщение с ошибкой: Такого адреса не существует
                </span>

                <ul class="reg__form-address-list hidden">
                    <li class="reg__form-address-item">
                        1
                    </li>
                </ul>
            </div> -->


            <button class="button--basic reg__form-button" type="submit">
                Зарегистрироваться
            </button>
        </form>
    </div>
</div>