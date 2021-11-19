<?php
use Kviron\CE;

add_action('get_site_header', 'get_header_site');
function get_header_site()
{
    CE::theTemplate('template-parts/layouts/header', [
        'className' => CE::getPageType(),
    ]);
}

add_action('get_footer', 'get_footer_site');
function get_footer_site()
{
    CE::theTemplate('template-parts/layouts/footer');
}
