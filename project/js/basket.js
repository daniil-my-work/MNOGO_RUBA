class BasketManager {
    constructor(basketUrl, updateUrl, pageSelector) {
        this.basketUrl = basketUrl;
        this.updateUrl = updateUrl;
        this.selector = pageSelector;
        this.page = document.querySelector(pageSelector);

        if (pageSelector == '#page-main') {
            const menuList = this.page.querySelector(".menu__list");

            if (menuList) {
                menuList.addEventListener("click", this.handleBasketOperationsOnMain.bind(this));
            }

            this.updateBasketDisplay();
        } else if (pageSelector == '#page-basket') {
            const basketList = this.page.querySelector(".basket__list");

            if (basketList) {
                basketList.addEventListener("click", this.handleBasketOperationsOnBasket.bind(this));
            }
        } else if (pageSelector == '#page-menu') {
            const menuList = this.page.querySelector(".menu__list");

            if (menuList) {
                menuList.addEventListener("click", this.handleBasketOperationsOnMain.bind(this));
            }

            this.updateBasketDisplay();
        } 
    }

    async updateBasketDisplay() {
        try {
            const response = await fetch(this.basketUrl, {
                method: "GET",
                credentials: 'same-origin'
            });

            if (!response.ok) throw new Error("Network response was not ok");
            const data = await response.json();
            const basketIndicator = document.querySelector('.action__basket');
            if (data.totalLength > 0) {
                basketIndicator.classList.remove('hidden');
            } else {
                basketIndicator.classList.add('hidden');
            }
        } catch (error) {
            console.error("Ошибка при получении данных из сессии:", error);
        }
    }

    async updateProductList(params, pageSelector) {
        try {
            const response = await fetch(this.updateUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: params.toString(),
            });

            if (!response.ok) throw new Error("Network response was not ok");
            // console.log("Данные успешно обновлены в сессии");

            if (pageSelector == '#page-main') {
                await this.updateBasketDisplay();
            } else if (pageSelector == '#page-basket') {
                // location.reload();
            }
        } catch (error) {
            console.error("There has been a problem with your fetch operation:", error);
        }
    }

    async handleBasketOperationsOnMain(evt) {
        const element = evt.target;
        const productItem = element.closest(".menu__item");

        if (!productItem) return;

        const { productCounterButton, productCounterWrapper, productCounterInput, productCounterNumber } = this.#getDOMElements(productItem, this.selector);
        const counterValue = Number(productCounterInput.value);

        this.#adjustCounterOnMain(element, counterValue, productCounterInput, productCounterNumber, productCounterButton, productCounterWrapper);

        const params = this.#createParamsOnMain(productItem, element, counterValue);

        await this.updateProductList(params, this.selector);
        await this.updateBasketDisplay();
    }

    #getDOMElements(productItem, pageSelector) {
        if (pageSelector == '#page-main' || pageSelector == '#page-menu') {
            return {
                productCounterButton: productItem.querySelector(".product-item__counter-button"),
                productCounterWrapper: productItem.querySelector(".product-item__counter-number-wrapper"),
                productCounterInput: productItem.querySelector(".product-item__counter-input"),
                productCounterNumber: productItem.querySelector(".product-item__counter-number")
            };
        } else if (pageSelector == '#page-basket') {
            return {
                productCounterInput: productItem.querySelector(".product-item__counter-input"),
                productCounterNumber: productItem.querySelector(".product-item__counter-number")
            };
        }
    }

    #createParamsOnMain(productItem, element, counterValue) {
        let newValue = parseInt(counterValue, 10);

        if (element.matches(".product-item__counter-action--plus")) {
            newValue = counterValue + 1;
        } else if (element.matches(".product-item__counter-action--minus") && counterValue > 1) {
            newValue = counterValue - 1;
        } else if (element.matches(".product-item__counter-button")) {
            newValue = 1;
        }

        console.log(element);
        console.log(newValue);

        return new URLSearchParams({
            uniqueId: productItem.dataset.uniqueId,
            productId: productItem.dataset.productId,
            tableName: 'menu',
            categoryId: productItem.dataset.categoryId,
            quantity: newValue,
        });
    }

    #adjustCounterOnMain(element, counterValue, productCounterInput, productCounterNumber, productCounterButton, productCounterWrapper) {
        if (element.matches(".product-item__counter-action--plus")) {
            this.#incrementCounter(productCounterInput, productCounterNumber, counterValue);
        } else if (element.matches(".product-item__counter-action--minus")) {
            this.#decrementCounter(productCounterInput, productCounterNumber, counterValue, productCounterButton, productCounterWrapper);
        } else if (element.matches(".product-item__counter-button")) {
            this.#clickToButtonBasket(productCounterInput, productCounterNumber, productCounterButton, productCounterWrapper);
        }
    }

    #incrementCounter(productCounterInput, productCounterNumber, counterValue) {
        const newValue = counterValue + 1;

        productCounterInput.value = newValue;
        productCounterNumber.textContent = newValue;
    }

    #decrementCounter(productCounterInput, productCounterNumber, counterValue, productCounterButton, productCounterWrapper) {
        const newValue = counterValue - 1;

        if (newValue <= 0) {
            this.#hideCounter(productCounterButton, productCounterWrapper);
            productCounterInput.value = 0;
            productCounterNumber.textContent = '0';
        } else {
            productCounterInput.value = newValue;
            productCounterNumber.textContent = newValue;
        }
    }

    #clickToButtonBasket(productCounterInput, productCounterNumber, productCounterButton, productCounterWrapper) {
        this.#openCounter(productCounterButton, productCounterWrapper);

        productCounterInput.value = 1;
        productCounterNumber.textContent = '1';
    }


    async handleBasketOperationsOnBasket(evt) {
        const element = evt.target;
        const productItem = element.closest(".basket__item");

        if (!productItem) return;

        const { productCounterInput, productCounterNumber } = this.#getDOMElements(productItem, this.selector);
        const counterValue = Number(productCounterInput.value);

        this.#adjustCounterOnBasket(element, productItem, counterValue, productCounterInput, productCounterNumber);

        const params = this.#createParamsOnBasket(productItem, element, counterValue);

        console.log(params);
        await this.updateProductList(params, this.selector);
    }

    #createParamsOnBasket(productItem, element, counterValue) {
        let newValue = counterValue;

        if (element.matches(".product-item__counter-action--plus")) {
            newValue = counterValue + 1;
        } else if (element.matches(".product-item__counter-action--minus") && counterValue > 1) {
            newValue = counterValue - 1;
        } else if (element.matches(".product-item__counter-button--basket") || element.matches(".product-item__counter-button-icon--basket")) {
            newValue = 0;
        }

        return new URLSearchParams({
            uniqueId: productItem.dataset.uniqueId,
            productId: productItem.dataset.productId,
            tableName: productItem.dataset.tableName,
            categoryId: productItem.dataset.categoryId,
            quantity: newValue,
        });
    }

    #adjustCounterOnBasket(element, productItem, counterValue, productCounterInput, productCounterNumber) {
        if (element.matches(".product-item__counter-action--plus")) {
            this.#incrementCounter(productCounterInput, productCounterNumber, counterValue);
        } else if (element.matches(".product-item__counter-action--minus")) {
            this.#decrementCounterOnBasket(productCounterInput, productCounterNumber, counterValue);
        } else if (element.matches(".product-item__counter-button--basket") || element.matches(".product-item__counter-button-icon--basket")) {
            this.#clickToButtonDeleteOnBasket(productItem, productCounterInput, productCounterNumber);
        }
    }

    #decrementCounterOnBasket(productCounterInput, productCounterNumber, counterValue) {
        const newValue = counterValue - 1;

        if (newValue <= 0) {
            productCounterInput.value = 1;
            productCounterNumber.textContent = 1;
            return;
        } else {
            productCounterInput.value = newValue;
            productCounterNumber.textContent = newValue;
        }
    }

    #clickToButtonDeleteOnBasket(productItem, productCounterInput, productCounterNumber) {
        productCounterInput.value = 0;
        productCounterNumber.textContent = '0';

        productItem.remove();
    }

    #openCounter(button, counter) {
        button.classList.add("hidden");
        counter.classList.remove("hidden");
    }

    #hideCounter(button, counter) {
        button.classList.remove("hidden");
        counter.classList.add("hidden");
    }
}


const URL_BASKET_DATA = 'get-session-data.php';
const URL_ADD_TO_BASKET = 'api-update-order.php';


// Инициализация класса BasketManager
document.addEventListener('DOMContentLoaded', function () {
    if (pageMain) {
        new BasketManager(URL_BASKET_DATA, URL_ADD_TO_BASKET, '#page-main');
    }

    if (pageBasket) {
        new BasketManager(URL_BASKET_DATA, URL_ADD_TO_BASKET, '#page-basket');
    }

    if (pageMenu) {
        new BasketManager(URL_BASKET_DATA, URL_ADD_TO_BASKET, '#page-menu');
    }
});
