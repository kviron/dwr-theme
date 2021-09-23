<?php
use CE\CE;

add_action('wp_head', 'get_meta_header_site');
function get_meta_header_site()
{
    CE::theTemplate('template-parts/header/meta-section');
}

add_action('site_header', 'get_header_site');
function get_header_site()
{
    CE::theTemplate('template-parts/header/header', [
        'className' => CE::getPageType(),
    ]);
}
