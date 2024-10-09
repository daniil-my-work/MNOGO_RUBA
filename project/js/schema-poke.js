class SchemeManager {
    constructor(config, selectors) {
        this.config = config;
        this.selectors = selectors; // Добавлено для доступа к селекторам
        this.scheme = localStorage.getItem(config.storageNames.scheme) || 1;
        this.fillerPokeItem = document.querySelector(selectors.fillerCount);
        this.toppingPokeItem = document.querySelector(selectors.toppingCount);

        this.initSchemeState();
        this.updateSchemeDescription();
        this.updateSchemePoke();
    }

    setScheme(schemeValue) {
        localStorage.setItem(this.config.storageNames.scheme, schemeValue);
        this.scheme = schemeValue;
        this.updateSchemeDescription();
        this.initSchemeState();
        this.clearCheckboxList();
    }

    getScheme() {
        return this.scheme;
    }

    updateSchemeDescription() {
        const schemeData = schemaPokeNumber[this.scheme];
        const fillerText = `/ Осталось ${schemeData.filler} из ${schemeData.filler}`;
        const toppingText = `/ Осталось ${schemeData.topping} из ${schemeData.topping}`;

        this.fillerPokeItem.textContent = fillerText;
        this.toppingPokeItem.textContent = toppingText;
    }

    initSchemeState() {
        const radioButtons = document.querySelectorAll(this.selectors.schemeRadio);

        radioButtons.forEach(radio => {
            if (radio.value === this.scheme) {
                radio.checked = true;
            }
        });
    }

    updateSchemePoke() {
        const schemaPoke = document.querySelector(this.selectors.scheme);

        schemaPoke.addEventListener('click', (evt) => {
            const target = evt.target;
            const schemaItem = target.closest('.constructor-poke-shema-item');

            if (schemaItem) {
                const schemaItemValue = schemaItem.dataset.shemaPoke;
                this.setScheme(schemaItemValue);
            }
        });
    }

    countCheckedItem(selector) {
        const checkboxes = document.querySelectorAll(selector);
        return Array.from(checkboxes).filter(cb => cb.checked).length;
    }

    clearCheckbox(selector, fillerNumber, type) {
        const list = document.querySelectorAll(selector);

        list.forEach(item => {
            if (fillerNumber > schemaPokeNumber[this.getScheme()][type]) {
                item.checked = false;
            }
        });
    }

    clearCheckboxList() {
        const fillerChecked = this.countCheckedItem(this.selectors.checkBoxFiller);
        const toppingChecked = this.countCheckedItem(this.selectors.checkBoxTopping);

        this.clearCheckbox(this.selectors.checkBoxFiller, fillerChecked, 'filler');
        this.clearCheckbox(this.selectors.checkBoxTopping, toppingChecked, 'topping');
    }
}

class BasketSumManager {
    constructor(selectors) {
        this.basketPrice = document.querySelector(selectors.basketSum);
        this.basketInputPrice = document.querySelector(selectors.basketSumInput);
        this.storage = { protein: 0, proteinAdd: 0, fillerAdd: 0, toppingAdd: 0, sauceAdd: 0, crunchAdd: 0 };
    }

    updateBasketSum() {
        const sum = Object.values(this.storage).reduce((total, value) => total + value, 0);
        this.basketPrice.textContent = `${sum} руб`;
        this.basketInputPrice.value = sum;
    }

    updateStorageItem(key, value) {
        this.storage[key] = Number(value);
        this.updateBasketSum();
    }

    // getStorageData() {
    //     return this.storage;
    // }
}

class CheckboxManager {
    constructor(selectors, schemeManager, basketManager) {
        this.selectors = selectors;
        this.schemeManager = schemeManager;
        this.basketManager = basketManager;
        this.fillerCheckboxes = document.querySelectorAll(selectors.checkBoxFiller);
        this.toppingCheckboxes = document.querySelectorAll(selectors.checkBoxTopping);
        this.fillerAddCheckboxes = document.querySelectorAll(selectors.checkBoxFillerAdd);
        this.toppingAddCheckboxes = document.querySelectorAll(selectors.checkBoxToppingAdd);
        this.fillerPokeItem = document.querySelector(selectors.fillerCount);
        this.toppingPokeItem = document.querySelector(selectors.toppingCount);
    }

    setupListeners() {
        this.setupListener(this.fillerCheckboxes, 'filler', null);
        this.setupListener(this.toppingCheckboxes, 'topping', null);
        this.setupListener(this.fillerAddCheckboxes, 'fillerAdd', this.selectors.labelFillerAdd);
        this.setupListener(this.toppingAddCheckboxes, 'toppingAdd', this.selectors.labelToppingAdd);
    }

    setupListener(checkboxes, type, label) {
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', (evt) => this.handleCheckboxChange(evt, type, label));
        });
    }

    handleCheckboxChange(evt, type, label = null) {
        const checkboxes = this.getCheckboxes(type);
        let checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;

        if (['filler', 'topping'].includes(type)) {
            const allowedCount = schemaPokeNumber[this.schemeManager.getScheme()][type];

            if (checkedCount > allowedCount) {
                evt.target.checked = false;
                evt.preventDefault();
                checkedCount = allowedCount;

                this.alertOverLimit(type, allowedCount);
            } else {
                this.changeCountValue(type, allowedCount - checkedCount);
            }
        } else if (['fillerAdd', 'toppingAdd'].includes(type)) {
            const sum = this.calculateAdditions(checkboxes);

            // Обновляет сумму Поке
            this.basketManager.updateStorageItem(type, sum);

            if (label) {
                const labelPrice = document.querySelector(label);
                labelPrice.textContent = sum ? `+ ${sum} руб` : '';
            }
        }
    }

    changeCountValue(type, diff) {
        const item = type === 'filler' ? this.fillerPokeItem : this.toppingPokeItem;
        const maxCount = schemaPokeNumber[this.schemeManager.getScheme()][type];
        const text = `/ Осталось ${diff} из ${maxCount}`;
        item.textContent = text;
    }

    getCheckboxes(type) {
        const checkboxMap = {
            'filler': this.fillerCheckboxes,
            'topping': this.toppingCheckboxes,
            'fillerAdd': this.fillerAddCheckboxes,
            'toppingAdd': this.toppingAddCheckboxes
        };
        return checkboxMap[type];
    }

    calculateAdditions(checkboxes) {
        return Array.from(checkboxes).reduce((sum, cb) => cb.checked ? sum + Number(cb.dataset.price) : sum, 0);
    }

    alertOverLimit(type, allowedCount) {
        alert(`Выберите в категории ${type} ${allowedCount} чекбокса(ов).`);
    }
}

class SelectManager {
    constructor(selectors, basketManager) {
        this.selectors = selectors;
        this.basketManager = basketManager;
    }

    setupListeners() {
        this.setupSelectListener('#constructor-poke__select--protein', null);
        this.setupSeveralSelectListener('.constructor-poke__select--required', null);
        this.setupSelectListener('#constructor-poke__select-proteinAdd', '#constructor-poke__add-price--protein');
        this.setupSelectListener('#constructor-poke__select-sauceAdd', '#constructor-poke__add-price--sauce');
        this.setupSelectListener('#constructor-poke__select-crunchAdd', '#constructor-poke__add-price--crunch');
    }

    setupSeveralSelectListener(selector, labelSelector) {
        const selects = document.querySelectorAll(selector);

        selects.forEach(select => {
            if (select) {
                const selectType = select.getAttribute('name');
                select.addEventListener('change', (evt) => this.handleSelectChange(evt, selectType, labelSelector));
            }
        });
    }

    setupSelectListener(selector, labelSelector) {
        const select = document.querySelector(selector);
        const selectType = select.getAttribute('name');

        if (select) {
            select.addEventListener('change', (evt) => this.handleSelectChange(evt, selectType, labelSelector));
        }
    }

    handleSelectChange(evt, key, priceLabel) {
        const price = evt.target.options[evt.target.selectedIndex].getAttribute('data-price');

        const labelPrice = document.querySelector(priceLabel);
        if (labelPrice) {
            labelPrice.textContent = price ? `+ ${price} руб` : '';
        }

        this.basketManager.updateStorageItem(key, price);
    }

    getCurrentSelectedValues() {
        const selects = [
            document.querySelector('#constructor-poke__select--protein'),
            document.querySelector('#constructor-poke__select-proteinAdd'),
            document.querySelector('#constructor-poke__select-sauceAdd'),
            document.querySelector('#constructor-poke__select-crunchAdd')
        ];

        selects.forEach(select => {
            const name = select.getAttribute('name');
            const price = select.options[select.selectedIndex].getAttribute('data-price');

            this.basketManager.updateStorageItem(name, price);
        });
    }
}

class PokeManager {
    constructor(config) {
        this.config = config;
        this.schemeManager = new SchemeManager(config, config.selectors);
        this.basketManager = new BasketSumManager(config.selectors);
        this.checkboxManager = new CheckboxManager(config.selectors, this.schemeManager, this.basketManager);
        this.selectManager = new SelectManager(config.selectors, this.basketManager);

        this.init();
    }

    init() {
        this.schemeManager.updateSchemeDescription();
        this.schemeManager.updateSchemePoke();
        this.checkboxManager.setupListeners();
        this.selectManager.getCurrentSelectedValues();
        this.selectManager.setupListeners();

        // Добавить другие инициализации и слушатели событий
    }
}


const pokePageConfig = {
    storageNames: {
        sum: 'constructorPokeSum',
        scheme: 'shemaPoke'
    },
    selectors: {
        form: '.constructor-poke__form',
        scheme: '.constructor-poke-shema',
        fillerCount: '#fillerCounter',
        toppingCount: '#topingCounter',
        basketSum: '.basket__order-number',
        basketSumInput: '#total-price',
        schemeRadio: '.constructor-poke-item-radio',
        checkBoxFiller: '.constructor-poke-item-checkbox--filler',
        checkBoxTopping: '.constructor-poke-item-checkbox--toping',
        checkBoxFillerAdd: '.constructor-poke-item-checkbox--fillerAdd',
        checkBoxToppingAdd: '.constructor-poke-item-checkbox--toppingAdd',
        labelFillerAdd: '#constructor-poke__add-price--filler',
        labelToppingAdd: '#constructor-poke__add-price--topping'
    }
};

const schemaPokeNumber = {
    1: { 'filler': 5, 'topping': 1 },
    2: { 'filler': 3, 'topping': 2 },
};

if (pagePoke) {
    new PokeManager(pokePageConfig);
}
