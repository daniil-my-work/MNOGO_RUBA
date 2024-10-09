class DeliveryManager {
    constructor(deliverySelector, addressListSelector, basketCafeSelector) {
        this.deliveryType = document.querySelector(deliverySelector);
        this.addressList = document.querySelector(addressListSelector);
        this.basketCafe = document.querySelector(basketCafeSelector);

        this.init();
    }

    init() {
        this.getDeliveryType();
        this.deliveryType.addEventListener('change', (evt) => this.setDeliveryType(evt));
    }

    setDeliveryType(evt) {
        const deliveryMethod = evt.target.value;

        if (deliveryMethod === 'default') {
            return;
        }

        localStorage.setItem('deliveryMethod', deliveryMethod);
        this.toggleVisibility(deliveryMethod === 'delivery');
    }

    getDeliveryType() {
        const deliveryMethod = localStorage.getItem('deliveryMethod');

        if (deliveryMethod) {
            this.deliveryType.value = deliveryMethod;
            this.toggleVisibility(deliveryMethod === 'delivery');
        }
    }

    toggleVisibility(isDelivery) {
        this.addressList.classList.toggle('hidden', !isDelivery);
        this.basketCafe.classList.toggle('hidden', isDelivery);
    }
}

if (pageBasket) {
    new DeliveryManager('#delivery-type', '#basket-delivery-list', '#basket-cafe-list');
}
