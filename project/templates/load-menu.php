<!-- Cтраница загрузки Меню -->
<div class="page__load" id="page-load">

    <!-- Загрузка меню -->
    <div class="load container">
        <h2 class="title load__title">
            Редактировать <?= $activeGroup; ?>
        </h2>

        <div class="account__orders-group load__update-tab">
            <span style="opacity: 0; width: 0; height: 0" id="tab-group">
                <?= $tabGroup; ?>
            </span>

            <?php $activeLinkMenu = isset($tabGroup) && $tabGroup === 'menu' ? 'account__orders-group-link--active' : ''; ?>
            <a class="button--basic account__orders-group-link <?= $activeLinkMenu; ?>" href="./load-menu.php?tabGroup=menu">
                Меню
            </a>

            <?php $activeLinkPoke = isset($tabGroup) && $tabGroup === 'poke' ? 'account__orders-group-link--active' : ''; ?>
            <a class="button--basic account__orders-group-link <?= $activeLinkPoke; ?>" href="./load-menu.php?tabGroup=poke">
                Поке
            </a>
        </div>


        <div class="load__column">
            <!-- Текущее значение меню -->
            <div class="load__current">
                <p class="text about__text mb-3">
                    Вы имеете возможность редактировать меню. Для получения текущих значений меню в виде таблицы, нажмите кнопку "Скачать".
                </p>

                <div class="load__current-content">
                    <p class="text load__current-text">
                        Таблица меню
                    </p>

                    <button class="button--basic button--second load__current-button">
                        Скачать
                    </button>
                </div>
            </div>

            <!-- Обновленное значение меню -->
            <div class="load__update">
                <div class="load__update-description">
                    <?php if (($tabGroup ?? '') === 'menu') : ?>
                        <p class="text about__text mb-2" id="load-description-menu">
                            Чтобы обновить меню, прикрепите файл в формате csv, полученный в виде шаблона.
                        </p>
                    <?php else : ?>
                        <p class="text about__text mb-2" id="load-description-poke">
                            Чтобы обновить конструктор поке, прикрепите файл в формате csv, полученный в виде шаблона.
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Инпут -->
                <form action="./load-menu.php?tabGroup=<?= $tabGroup; ?>" class="load__form" enctype="multipart/form-data" method="post">
                    <div class="load__update-content">
                        <input class="input load__update-input" type="file" name="file" id="file" required>

                        <p class="text load__update-text">
                            вставить файл
                            <br>
                            +
                        </p>
                    </div>

                    <?php $classHidden = isset($errors['file']) ? '' : 'hidden'; ?>
                    <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                        <?= $errors['file'] ?? ''; ?>
                    </span>

                    <!-- hidden -->
                    <button class="button--basic button--second load__update-button" type="submit">
                        Загрузить
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>