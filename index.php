<?php

use Kviron\CE;

get_header(); ?>
<div id="app">
    <div class="container">
        <?php CE::thePosts(
            [
                'post_type' => 'post'
            ]
        ); ?>
    </div>

</div>
<?php get_footer(); ?>
