<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <link rel="manifest" href="<?php echo esc_url(THEME_URL)?>/manifest/manifest.webmanifest">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div id="app">
    <?php do_action('dwr_custom_header'); ?>
    <div class="app__content">

