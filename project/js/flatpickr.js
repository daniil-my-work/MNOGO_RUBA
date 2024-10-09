// JavaScript для инициализации Flatpickr
const dateNow = new Date();
const dateWeekAgo = dateNow.setDate(dateNow.getTime() - 7 * 24 * 60 * 60 * 1000);

// Вставляет указанную дату
const dateFirst = document.querySelector('#date-first');
const dateSecond = document.querySelector('#date-second');


// Инициализация Flatpickr с настройками
flatpickr("#datepicker", {
    locale: "ru", // Установка русского языка
    mode: "range", // Режим - выбор диапазона дат
    dateFormat: "d.m.Y", // Формат даты (год-месяц-день)
    defaultDate: [dateWeekAgo, dateNow], // Диапазон дат: неделя назад и сегодня
    locale: {
        firstDayOfWeek: 1, // Установка начала недели на понедельник
        weekdays: {
            shorthand: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
            longhand: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота']
        },
        months: {
            shorthand: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
            longhand: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
        }
    },
    rangeSeparator: 'до', // Заменяем 'to' на 'до'
    onChange: function (selectedDates, dateStr, instance) {
        if (selectedDates[0]) {
            const dateFirst = document.querySelector('#date-first');
            dateFirst.value = formatDate(selectedDates[0]);
        }

        if (selectedDates[1]) {
            const dateSecond = document.querySelector('#date-second');
            dateSecond.value = formatDate(selectedDates[1]);
        }
    },
    onReady: function (selectedDates, dateStr, instance) {
        // Устанавливаем выбранные значения в Flatpickr
        instance.setDate([new Date(dateFirst.value), new Date(dateSecond.value)]);
    }
});


function formatDate(date) {
    const day = ('0' + date.getDate()).slice(-2);
    const month = ('0' + (date.getMonth() + 1)).slice(-2);
    const year = date.getFullYear();
    return year + '-' + month + '-' + day;
}