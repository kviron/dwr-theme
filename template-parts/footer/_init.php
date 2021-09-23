<?php
use CE\CE;

add_action('site_footer', 'get_footer_site');
function get_footer_site()
{
    CE::theTemplate('template-parts/footer/footer');
}

add_action('site_footer', 'get_footer_scripts_site');
function get_footer_scripts_site()
{
    CE::theTemplate('template-parts/footer/footer-scripts');
}
