<?php
use \Kviron\CE\CE;

/*
|--------------------------------------------------------------------------
| Объявление переменных темы
|--------------------------------------------------------------------------
*/
define('THEME_VERSION', '0.0.1');
define('THEME_PATH', get_template_directory());
define('THEME_URL', get_template_directory_uri());
define('THEME_ASSETS', THEME_URL . '/assets');
define('THEME_SLUG', 'dwr-theme');

/*
|--------------------------------------------------------------------------
| Регистрация авто загрузчика composer
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/
if (!file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    wp_die(__('Ошибка загрузки. Пожалуйста выполните <code>composer install</code> в папке активной темы', 'sage'));
}

require $composer;

CE::init(['debug' => WP_DEBUG]);
ACF_Archive::instance();

require THEME_PATH . '/template-parts/layouts/_init.php';
