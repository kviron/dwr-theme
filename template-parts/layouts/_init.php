<?php
use Kviron\CE\CE;

add_action('dwr_custom_header', 'dwr_custom_header');
function dwr_custom_header()
{
    CE::theTemplate('template-parts/layouts/header');
}

add_action('wp_head', 'dwr_custom_head');
function dwr_custom_head()
{
    CE::theTemplate('template-parts/layouts/head');
}

add_action('dwr_custom_footer', 'dwr_custom_footer');
function dwr_custom_footer()
{
    CE::theTemplate('template-parts/layouts/footer');
}
