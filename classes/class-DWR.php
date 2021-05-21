<?php
/**
 * Class DWR
 *
 *
 */
class DWR
{
    /**
     * @var array
     */
    public static array $theme = [];

    /**
     * @var array
     * Params vars
     */
    public static array $vars = [];

    /**
     * @var array
     * Params vars
     */
    public static array $item = [];

    public static function init($theme_path, $theme_url, $items_default = 'template-parts/items/item-')
    {
        self::set_theme_url($theme_url);
        self::set_theme_path($theme_path);
        self::$item['path'] = $items_default;
    }

    public static function set_theme_url($url_site)
    {
        self::$theme['url'] = $url_site;
    }

    /**
     * Set path theme
     */
    public static function set_theme_path($path_theme)
    {
        self::$theme['path'] = $path_theme;
    }


    public static function the_posts($args = [])
    {
        global $wp_query;

        $args['post_type'] = $args['post_type'] ?? get_query_var('post_type');
        $state             = $args['state'] ?? 'private';
        $tmp_path          = $args['template'] ?? self::$item['path'] . $args['post_type'];

        if ($state === 'private' && $wp_query->have_posts()) {
            self::loop_posts($wp_query, $tmp_path, $args);
        } else if ($state === 'global') {
            if (!isset($args['paged'])) {
                $args['paged'] = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
            }

            if (!isset($args['posts_per_page'])) {
                $args['posts_per_page'] = get_query_var('paged') ?? 1;
            }

            $query = new WP_Query($args);

            if ($query->have_posts()){
                self::loop_posts($query, $tmp_path, $args);
            } else{
                return false;
            }
        }

        wp_reset_postdata();
    }

    public static function get_posts($post_type, $args = [])
    {
        global $wp_query;

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
        return 'diff';
    }

    public static function get_template($_template_file, $args = [], ...$vars)
    {
        global $wp_query;

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

        $file_path = self::$theme['path'] . '/' . $_template_file . '.php';

        if (isset($args['require_once']) && file_exists($file_path)) {
            require_once $file_path;
        } elseif(file_exists($file_path)) {
            require $file_path;
        }elseif(!file_exists($file_path)){
            printf('Файла по пути %1s ненайден', $file_path);
        }
        echo ob_get_clean();
    }

    static function loop_posts($query, $tmp_path, $args = [])
    {
        $counter = 0;
        while ($query->have_posts()) {
            $query->the_post();
            global $post;
            echo $args['container']['start'] ?? null;

            self::get_template(
                $tmp_path,
                [
                    'post'          => $post ?? null,
                    'thumbnail_url' => get_the_post_thumbnail_url($post->ID, $args['thumbnail_size'] ?? null),
                    'class'         => $args['class'] ?? null,
                    'counter'       => $counter,
                ]
            );

            echo $args['container']['end'] ?? null;

            $counter++;
        }
    }

    public static function create_pagination($posts, $args = [])
    {
        $posts_per_page = $args['posts_per_page'] ?? 10;
        $total_items    = count($posts);
        $total_pages    = ceil($total_items / $posts_per_page);

        if (get_query_var('paged')) {
            $current_page = get_query_var('paged');
        } elseif (get_query_var('page')) {
            $current_page = get_query_var('page');
        } else {
            $current_page = 1;
        }
        $starting_point = ( ( $current_page - 1 ) * $posts_per_page  );

        $big        = 999999999;
        $translated = __('', 'pixplus');

        paginate_links(
            [
                'base'               => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'             => '?paged=%#%',
                'current'            => $current_page,
                'total'              => $total_pages,
                'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
                'prev_text'          => ( '<' ),
                'next_text'          => __('>'),
            ]
        );
    }

    public static function the_post_content(){
        global $post;

        if (get_the_content()) {
            the_content();
        } else {
            printf('<div class="not-content">%s</div>',__('Тут еще нет никакого описания, но оно скоро появится', 'amber'));
        }
    }
}
