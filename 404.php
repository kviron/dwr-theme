<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Vacancy
 */

get_header();
?>

    <div class="container" style="text-align: center">
        <h1 class="title">Ошибка 404</h1>
        <span><?php _e('Такой страницы не существует', 'dwr-theme') ?> <a href="<?php bloginfo('home'); ?>">Главная</a></span>
    </div>

<?php
get_footer();
