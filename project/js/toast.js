class ModalManager {
    constructor(modalSelector) {
        this.modalWrapper = document.querySelector(modalSelector);

        if (this.modalWrapper) {
            this.modalWrapper.addEventListener('click', this.handleModalClick.bind(this));
        }
    }

    async apiUpdateModalCity(params) {
        try {
            const response = await fetch("api-update-modal-city.php", {
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
        }
    }

    async apiCloseToast(params) {
        try {
            const response = await fetch("api-close-toast.php", {
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
        }
    }

    async handleModalClick(evt) {
        const target = evt.target;
        const toast = target.closest('.toast');

        if (!toast) {
            return;
        }

        const params = new URLSearchParams();
        const categoryToast = toast.getAttribute('data-set-category');
        const toastId = toast.getAttribute('data-set-popup-id');
        params.append("toastId", toastId);

        if (target && target.classList.contains('btn-close')) {
            this.removeToast(toast);

            await this.apiCloseToast(params);
        }

        if (categoryToast === 'city' && target.classList.contains('btn')) {
            const buttonValue = target.textContent.trim();
            params.append("cityValue", buttonValue);

            try {
                await this.apiUpdateModalCity(params);
                await this.apiCloseToast(params);

                this.removeToast(toast);
                window.location.reload();
            } catch (err) {
                console.error('Ошибка при обновлении города:', err);
            }
        }
    }

    removeToast(toastElement) {
        toastElement.classList.remove('show');
        toastElement.remove();
    }
}

// Использование класса для управления модальными окнами
const modalManager = new ModalManager('#alert-modal');
