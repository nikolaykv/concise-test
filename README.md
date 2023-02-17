Тестовое задание для Concise.

Структура:

 - <a href="https://github.com/nikolaykv/concise-test/blob/4b4149c5df2e2c11a3c3cf815dd2fea0c71588ca/backend/migrations/" target="_blank">Папка миграций для таблиц product и store_product</a>
 - <a href="https://github.com/nikolaykv/concise-test/blob/4b4149c5df2e2c11a3c3cf815dd2fea0c71588ca/backend/models/Product.php" target="_blank">Модель Product</a>
 - <a href="https://github.com/nikolaykv/concise-test/blob/4b4149c5df2e2c11a3c3cf815dd2fea0c71588ca/backend/models/StoreProduct.php" target="_blank">Модель StoreProduct</a>
 - <a href="https://github.com/nikolaykv/concise-test/blob/4b4149c5df2e2c11a3c3cf815dd2fea0c71588ca/backend/controllers/ImagesController.php" target="_blank">ImagesController</a>
 - <a href="https://github.com/nikolaykv/concise-test/blob/4b4149c5df2e2c11a3c3cf815dd2fea0c71588ca/backend/commands/ConciseController.php" target="_blank">ConciseController - консольный контроллер, при помощи которого можно заполнить модели тестовыми данными и сгенерировать миниатюры</a>

Как запустить:

1) Клонируй;
2) В директории docker запусти ```docker-compose up --build``` (база создается автоматически, зависимости подтягиваются через встроенный service composer, см. docker-compose.yaml);
3) http://127.0.0.1/ или http://127.0.0.1:80 - web
4) http://127.0.0.1:8080 - adminer (настройки подкл-я в .env файле)

Задача:<br/>
Добавить команду для генерации миниатюр.

Условия: в системе есть таблицы "product" и "store_product".
В таблице "product" есть поля "id", "image" и "is_deleted" , а в таблице "store_product" есть поля "id", "product_id" и "product_image".
Связь между ними: `product`.`id`=`store_product`.`product_id`.
Также в системе имеется класс Images, который умеет генерировать миниатюры. У класса есть два статичных публичных метода:
generateMiniature() и generateWatermarkedMiniature(). Оба принимают на вход следующие параметры:
1. Ссылку на картинку
2. Массив, в котором указана максимальная ширина и высота миниатюры в ключах 'width' и 'height' (пример: ['width'=>500, 'height'=>400])

В ответ либо возвращают ссылку на миниатюру, либо выбрасывают исключение.


Описание:
Необходимо, чтобы по команде  yii <some-command> генерировались миниатюры для изображений для всех продуктов, которые не удалены.
Команда должна принимать три параметра:
1. sizes - обязательный, набор размеров миниатюр. Размеры разделяются через запятую. Если ширина и высота разная, то они разделяются символом "x" (латиница).
   Пример формата: "100,200x300,500x600".
2. watermarked - опциональный, по умолчанию false. Накладывать ли водные знаки на миниатюру.
3. catalogOnly - опциональный, по умолчанию true. Искать только те товары, которые есть в обеих таблицах - "product" и "store_product".

Сохранять данные нигде не надо.
На выходе система должна выдать сообщение о том, сколько миниатюр было сгенерировано успешно и сколько сгенерировать не удалось.