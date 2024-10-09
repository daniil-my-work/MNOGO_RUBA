class Burger {
     /**
     * Конструктор класса Burger.
     * Инициализирует объекты, представляющие элементы интерфейса бургер-меню.
     * 
     * @param {string} burgerSelector - CSS-селектор для кнопки бургер-меню.
     * @param {string} navSelector - CSS-селектор для навигационного блока.
     * @param {string} bodySelector - CSS-селектор для основного тела страницы.
     */
    constructor(burgerSelector, navSelector, bodySelector){
        this._burger = document.querySelector(burgerSelector);
        this._nav = document.querySelector(navSelector);
        this._body = document.querySelector(bodySelector);

        this._initEvents();
    }

    /**
     * Переключает видимость навигационной панели и кнопки бургера.
     * Добавляет или удаляет класс `show`, который контролирует отображение элементов.
     */
    toggleMenu() {
        this._nav.classList.toggle('show');
        this._burger.classList.toggle('show');
        this._updateBodyScroll();
    }

    /**
     * Обновляет свойства прокрутки страницы в зависимости от состояния навигационной панели.
     * Блокирует или разблокирует прокрутку основного тела страницы.
     */
    _updateBodyScroll() {
        if (this._nav.classList.contains('show')) {
            this._body.style.overflowY = 'hidden';
            document.addEventListener('click', this._closeMenu.bind(this));
        } else {
            this._body.style.overflowY = 'scroll';
            document.removeEventListener('click', this._closeMenu.bind(this));
        }
    }

    /**
     * Закрывает навигационное меню, если клик происходит вне элементов меню и бургера.
     * Удаляет класс `show`, что приводит к закрытию меню.
     * 
     * @param {Event} evt - Объект события, который содержит информацию о клике.
     */
    _closeMenu(evt) {
        const target = evt.target;
        if (target.closest('.header__burger') || target.closest('.header__nav')) {
            return;
        }

        this._nav.classList.remove("show");
        this._burger.classList.remove("show");
        this._updateBodyScroll();
    }

    /**
     * Инициализирует события при создании объекта.
     * Назначает слушатель событий для бургер-кнопки, который активирует функцию `toggleMenu`.
     */
    _initEvents() {
        this._burger.addEventListener('click', () => this.toggleMenu());
    }
}

// Добавляет слушатель после загрузки страницы
document.addEventListener('DOMContentLoaded', function() {
    new Burger('.header__burger', '.header__nav', '.page__body');
});