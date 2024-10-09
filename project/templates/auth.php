<!-- Страница Авторизации -->
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


        <div class="reg__column">
            <h2 class="title">Войти</h2>
    
            <img src="" alt="" class="content__img reg__column-img">
        </div>

        <!-- Форма для авторизации -->
        <form class="reg__form" action="#" method="post">
            <div class="reg__form-input-wrapper text">
                <label for="email">Почта:</label>
                <input class="reg__form-input" type="email" id="user_email" name="user_email" placeholder="test@yandex.ru" value="<?= isset($_POST['user_email']) ? $_POST['user_email'] : ''; ?>" required>

                <?php $classHidden = isset($errors['user_email']) ? '' : 'hidden'; ?>
                <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                    <?= $errors['user_email'] ?? ''; ?>
                </span>
            </div>

            <div class="reg__form-input-wrapper text">
                <label for="password">Пароль:</label>
                <input class="reg__form-input" type="password" id="user_password" name="user_password" placeholder="***" value="<?= isset($_POST['user_password']) ? $_POST['user_password'] : ''; ?>" required>

                <?php $classHidden = isset($errors['user_password']) ? '' : 'hidden'; ?>
                <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                    <?= $errors['user_password'] ?? ''; ?>
                </span>
            </div>

            <button class="button--basic reg__form-button" type="submit">
                Войти
            </button>
        </form>
    </div>
</div>