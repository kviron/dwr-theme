#DWR-Theme
Шаблон темы wordpress c подключенным Webpack.

##Зависимости
 - Node js 14.16.1
 - PHP 7.1
 - composer 2.0

##Установка
В папке `themes` выполните команду
```shell
composer create-project kviron/dwr-theme <имя темы>
```

Эта команда создаст папку с темой и локальный файл настроек `.env` для конфигурации
Webpack

##Работа с WebPack
Что бы сбилдить ваши стили и скрипты для продакшена нужно выполнить команду
```shell
npm run build
```

Запустить локальный сервер с отслеживанием изменений
```shell
npm start
```