<?php

/**
 * Declaring constants
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
 * Add includes
 */
require THEME_PATH . "/template-parts/header/_init.php";

/**
 * Init Classes
 */
DWR::init(THEME_PATH, THEME_URL);
ACF_Archive::instance();
