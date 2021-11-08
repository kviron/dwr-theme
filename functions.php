<?php
/*
|--------------------------------------------------------------------------
| Регистрация автозагрузчика composer
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/
if (! file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    wp_die(__('Ошибка загрузки. Пожалуйста выполните <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Обьявление переменных темы
|--------------------------------------------------------------------------
*/
define('THEME_VERSION', '0.0.1');
define('THEME_PATH', get_template_directory());
define('THEME_URL', get_template_directory_uri());
define('THEME_ASSETS', THEME_URL . '/assets');
define('THEME_SLUG', 'dwr-theme');

/**
 * Add classes
 */
require_once  THEME_PATH . "/classes/_init.php";

/**
 * Add carbon-fields plugin
 */
//require THEME_PATH . "/custom-fields/_init.php";

/**
 * Add settings theme
 */
require THEME_PATH . "/settings/_index.php";

/**
 * Add post-types files
 */
require THEME_PATH . "/post-types/_index.php";

/**
 * Add manifest and seo files
 */
require THEME_PATH . "/manifest/_index.php";

/**
 * Add includes
 */
require THEME_PATH . "/includes/_index.php";

/**
 * Add layouts
 */
require THEME_PATH . "/template-parts/layouts/_init.php";

/**
 * Init Classes
 */
\CE\CE::init();
