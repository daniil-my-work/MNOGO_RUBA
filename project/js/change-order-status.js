class OrderStatusManager {
    constructor(activeTableSelector, completeTableSelector) {
        this.activeTable = document.querySelector(activeTableSelector);
        this.completeTable = document.querySelector(completeTableSelector);
        this.setupEventListeners();
    }

    setupEventListeners() {
        if (this.activeTable) {
            this.activeTable.addEventListener('click', this.changeOrderStatus.bind(this));
        }
        if (this.completeTable) {
            this.completeTable.addEventListener('click', this.changeOrderStatus.bind(this));
        }
    }

    async changeOrderStatus(evt) {
        const target = evt.target;
        if (!target.classList.contains('account__orders-checkbox')) {
            return;
        }

        const parentTr = target.closest('tr');
        const input = parentTr.querySelector('input[name="order-id"]');
        const orderId = input.value;
        const params = new URLSearchParams();
        params.append("orderId", orderId);

        const parentTable = target.closest('table');
        params.append("status", parentTable.id === 'account__orders-table--active');

        try {
            await this.apiUpdateOrderStatus(params);
            location.reload();
        } catch (err) {
            console.error('Ошибка при обновлении статуса заказа:', err);
        }
    }

    async apiUpdateOrderStatus(params) {
        try {
            const response = await fetch("api-update-order-status.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: params.toString(),
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
        } catch (error) {
            console.error("There has been a problem with your fetch operation:", error);
            throw error;
        }
    }
}

// Использование класса для управления статусом заказов
if (pageAdmin) {
    new OrderStatusManager('#account__orders-table--active', '#account__orders-table--complete');
}
