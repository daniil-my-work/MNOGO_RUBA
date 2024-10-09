// Код для скачивания изображений
const START = 87;
const END = 102;
const DOWNLOAD_NAME = 'sauce';

const items = document.querySelectorAll('.overlap-group1');
const itemsArray = Array.from(items);
// console.log(itemsArray);
const formatArray = itemsArray.slice(START, END);

// Извлекаем URL из стиля background-image
const itemsUrl = formatArray.map(item => {
    // Получаем значение стиля background-image
    const style = item.style.backgroundImage;
    // Извлекаем URL из строки стиля
    const urlMatch = style.match(/url\("?(.*?)"?\)/);
    // Возвращаем URL или null, если не найден
    return urlMatch ? urlMatch[1] : null;
});

// Выводим массив ссылок в консоль
let number = 1;

// Функция для скачивания изображения по URL
function downloadImage(url) {
    fetch(url)
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const extension = url.split('.').pop().split('?')[0]; // Извлекаем расширение файла из URL
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = DOWNLOAD_NAME + number++ + '.' + extension; // Используем имя файла из URL
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        })
        .catch(err => console.error('Ошибка при скачивании изображения:', err));
}

// Скачиваем все изображения из списка
itemsUrl.forEach(downloadImage);




// Код для скачивания изображений
const items2 = document.querySelectorAll('.col-sm');
const itemsArray2 = Array.from(items2);

// Извлекаем URL из стиля background-image
const itemsTitle = itemsArray2.map(item => {
    return item.querySelector('.text-1') ? item.querySelector('.text-1').textContent : null;
});

console.log(itemsTitle);