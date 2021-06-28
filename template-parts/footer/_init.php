<?php
use DWR\DWR;

add_action('site_footer', 'get_footer_site');
function get_footer_site()
{
    DWR::theTemplate('template-parts/footer/footer');
}

add_action('site_footer', 'get_footer_scripts_site');
function get_footer_scripts_site()
{
    DWR::theTemplate('template-parts/footer/footer-scripts');
}
