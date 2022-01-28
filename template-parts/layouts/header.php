<header class="header">
    <div class="header__container container" style="display: flex; align-items: center">
        <svg class="icon icon_svg icon_size_large" style="margin-right: 8px">
            <use href="#logo.svg"/>
        </svg>
        <a href="<?php bloginfo('url'); ?>" class="logo">
            <span class="">
                <?php _e('DWR-theme', 'dwr-theme') ?>
            </span>
        </a>
        <?php wp_nav_menu(
            [
                'container'  => 'nav',
                'menu_class' => 'header__menu',
            ]
        ) ?>
    </div>
</header>