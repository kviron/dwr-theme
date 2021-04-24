<?php

/**
 * Class DWR
 */
class DWR {
    /**
     * @var array
     */
    public static $theme = [];

    /**
     * @var array
     */
    public static $item = [];

    /**
     * @param $theme_path - Init theme path in class
     * @param $theme_url - Init theme url in class
     * @param string $items_default
     */
    public static function init($theme_path, $theme_url, $items_default = 'template-parts/items/item-')
    {
        self::$theme['url']  = $theme_url;
        self::$theme['path'] = $theme_path;
        self::$item['path']  = $items_default;
    }

    /**
     * @param $post_type  - post type from getting posts
     * @param array $args - array arguments
     */
    public static function the_posts($post_type, $args = [])
    {
        global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

        $counter     = 0;
        $tmp_default = self::$item['path'] . $post_type;

        $args['post_type'] = $args['post_type'] ?? get_query_var('post_type');
        $state             = $args['state'] ?? 'private';
        $tmp_path          = $args['template'] ?? self::$item['path'] . $args['post_type'];

        if ($state === 'private') {
            self::loop_posts($wp_query, $tmp_path, $args);
        } else if ($state === 'global') {
            if (!isset($args['paged'])) {
                $args['paged'] = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
            }

            if (!isset($args['posts_per_page'])) {
                $args['posts_per_page'] = get_query_var('paged') ?? 1;
            }

            $query = new WP_Query($args);
            self::loop_posts($query, $tmp_path, $args);
        }

        wp_reset_postdata();
    }

    /**
     * @param $post_type
     * @param array $args
     * @return WP_Query
     */
    public static function get_posts($post_type, $args = [])
    {
        global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

        $args['post_type'] = $args['post_type'] ?? get_query_var('post_type');
        $state             = $args['state'] ?? 'private';
        $tmp_path          = $args['template'] ?? self::$item['path'] . $args['post_type'];

        if ($state === 'private') {
            $query = $wp_query->have_posts();
        } else if ($state === 'global') {
            $query = new WP_Query($args);
        }

        wp_reset_postdata();

        return $query;
    }

    /**
     * @return string - return type current page
     */
    public static function get_type_page()
    {
        if (is_front_page()) {
            return 'front_page';
        } elseif (is_archive()) {
            return 'archive';
        } elseif (is_single()) {
            return 'single';
        } elseif (is_page()) {
            return 'page';
        }
        return 'error';
    }

    /**
     * @param $_template_file
     * @param array $args
     * @param false $require_once
     * Require template parts based on load_template()
     */
    public static function get_template($_template_file, $args = [], $require_once = false)
    {
        global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

        $file = self::$theme['path'] . '/' . $_template_file . '.php';

        if (file_exists($file)){
            ob_start();

            if (is_array($args)) {
                extract($args, EXTR_SKIP);
            }

            if (is_array($wp_query->query_vars)) {
                extract($wp_query->query_vars, EXTR_SKIP);
            }

            if (isset($s)) {
                $s = esc_attr($s);
            }

            if ($require_once) {
                require_once $file;
            } else {
                require $file;
            }
            echo ob_get_clean();
        }else{
            return false;
        }
    }

    static function loop_posts($query, $tmp_path, $args = [])
    {
        $counter = 0;
        while ($query->have_posts()) {
            $query->the_post();

            echo $args['container']['start'] ?? null;

            self::get_template(
                $tmp_path,
                [
                    'post'          => $post,
                    'thumbnail_url' => get_the_post_thumbnail_url($post->ID, $args['thumbnail_size'] ?? null),
                    'class'         => $args['class'] ?? null,
                    'counter'       => $counter,
                ]);

            echo $args['container']['end'] ?? null;

            $counter++;
        }
    }

    public static function create_pagination($posts, $args = [])
    {
        $total_items    = count($posts);
        $total_pages    = ceil($total_items / ($args['posts_per_page'] ?? 10));

        if (get_query_var('paged')) {
            $current_page = get_query_var('paged');
        } elseif (get_query_var('page')) {
            $current_page = get_query_var('page');
        } else {
            $current_page = 1;
        }
        $starting_point = ( ( $current_page - 1 ) * ($args['posts_per_page'] ?? 10) );

        $big        = 999999999;

        paginate_links(
            [
                'base'               => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'             => '?paged=%#%',
                'current'            => $current_page,
                'total'              => $total_pages,
                'before_page_number' => '<span class="screen-reader-text">' . __('', THEME_SLUG) . ' </span>',
                'prev_text'          => __('<'),
                'next_text'          => __('>'),
            ]);
    }

}
