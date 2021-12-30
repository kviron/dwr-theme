<?php
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;

/**
 * Изменяет URL расположения jQuery файла только для фронт-энда
 */
if (!is_admin()) {
    add_action('wp_enqueue_scripts', 'jquery_enqueue_func', 11);
    function jquery_enqueue_func()
    {
        wp_deregister_script('jquery');
        wp_register_script('jquery', "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js", false, null, true);
        wp_enqueue_script('jquery');
    }
}

/**
 * Подключение скрипта html5 для IE с cdn
 */
add_action('wp_head', function () {
    echo '<!--[if lt IE 9]><script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script><![endif]-->';
    echo '<!--[if lt IE 9]><script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv-printshiv.min.js"></script><![endif]-->';
});


//Enqueue styles
add_action('wp_enqueue_scripts', 'load_style', 11);
function load_style()
{
    $package = new Package(new JsonManifestVersionStrategy(THEME_PATH . '/assets/assets.json'));
    wp_enqueue_style('vendor-css', THEME_ASSETS . '/' . $package->getUrl('vendors.css'), false, false);
    wp_enqueue_style('app-css', THEME_ASSETS . '/' . $package->getUrl('app.css'), 'vendor-css', false);
}

//Enqueue scripts
add_action('wp_enqueue_scripts', 'load_scripts', 20);
function load_scripts()
{
    $package = new Package(new JsonManifestVersionStrategy(THEME_PATH . '/assets/assets.json'));
    wp_enqueue_script('vendors-js', THEME_ASSETS . '/' . $package->getUrl('vendors.js'), 'jquery', false, true);
    wp_enqueue_script('app-js', THEME_ASSETS . '/' . $package->getUrl('app.js'), 'jquery', false, true);
}

//Передаем данные переменные в JS  -----------------------
add_action('wp_enqueue_scripts', 'add_global_vars', 99);
function add_global_vars()
{
    $site = [
        'themeUrl'       => THEME_URL,
        'themePath'      => THEME_PATH,
        'themeAssetsUri' => THEME_ASSETS
    ];
    wp_localize_script('app-js', 'site', $site);
}
