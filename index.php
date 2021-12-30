<?php

use Kviron\CE\CE;

get_header(); ?>
    <div class="container">
        <?php
        CE::thePosts(
            [
                'post_type' => 'post'
            ]
        ); ?>
    </div>
<?php get_footer();