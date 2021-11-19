<h1>DWR-Theme</h1>  
Шаблон темы wordpress c подключенным Webpack.

<h2>Зависимости</h2>
 - Node js 14.16.1
 - PHP 7.1
 - composer 2.0

<h2>Установка</h2>

В папке `themes` выполните команду
```shell
composer create-project kviron/dwr-theme <имя темы>
```

Эта команда создаст папку с темой и локальный файл настроек `.env` для конфигурации
Webpack

<h2>Работа с WebPack</h2>
Что бы сбилдить ваши стили и скрипты для продакшена нужно выполнить команду
```shell
npm run build
```

Запустить локальный сервер с отслеживанием изменений
```shell
npm start
```