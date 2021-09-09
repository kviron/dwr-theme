<?php

namespace DWR;

use WP_Query;

/**
 * Class DWR
 * @implements DWRTemplate
 *
 */
class DWR
{
    /**
     * @var array
     * Array paths a wordpress theme
     */
    protected static array $options = [
        'location'         => null,
        'themeUri'         => null,
        'themeVersion'     => null,
        'themePath'        => null,
        'themeSlug'        => null,
        'themeAssetsPath'  => null,
        'themeAssetsUri'   => null,
        'componentsDir'    => null,
        'templatePagesDir' => null,
        'templatePartsDir' => null,
        'itemsPath'        => null,
    ];

    public static function init($args = [])
    {
        self::$options['themeUri']        = $args['themeUri'] ?? get_template_directory_uri();
        self::$options['themePath']       = $args['themePath'] ?? get_template_directory();
        self::$options['templateParts']   = $args['templateParts'] ?? self::$options['themePath'] . '/template-parts';
        self::$options['itemPath']        = $args['itemPath'] ?? self::$options['templateParts'] . 'items/item-';
        self::$options['themeAssetsPath'] = $args['themeAssetsPath'] ?? self::$options['themePath'] . '/assets';
        self::$options['themeAssetsUri']  = $args['themeAssetsUri'] ?? self::$options['themeUri'] . '/assets';
    }


    public static function thePosts($args = []): void
    {
        global $wp_query;

        $args['post_type'] = $args['post_type'] ?? get_query_var('post_type');
        $state             = $args['state'] ?? 'private';
        $tmp_path          = $args['template'] ?? self::$options['itemPath'] . $args['post_type'];

        if ($state === 'private' && $wp_query->have_posts()) {
            self::loopPosts($wp_query, $tmp_path, $args);
        } else if ($state === 'global') {
            if (!isset($args['paged'])) {
                $args['paged'] = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
            }

            if (!isset($args['posts_per_page'])) {
                $args['posts_per_page'] = get_query_var('paged') ?? 1;
            }

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                self::loopPosts($query, $tmp_path, $args);
            }
        }

        $wp_query->reset_postdata();
    }

    public static function getPosts($post_type, $args = [])
    {
        global $wp_query;

        $args['post_type'] = $args['post_type'] ?? get_query_var('post_type');
        $state             = $args['state'] ?? 'private';
        $tmp_path          = $args['template'] ?? self::$options['itemPath'] . $args['post_type'];

        if ($state === 'private') {
            $query = $wp_query->have_posts();
        } else if ($state === 'global') {
            $query = new WP_Query($args);
        }

        $wp_query->reset_postdata();

        return $query;
    }

    public static function getTypePage(): ?string
    {
        if (is_front_page()) {
            return 'front-page';
        } elseif (is_archive()) {
            return 'archive';
        } elseif (is_single()) {
            return 'single';
        } elseif (is_page()) {
            return 'page';
        }
        return null;
    }

    public static function template($_template_file, $args = []): void
    {
        global $wp_query;

        if (is_array($args)) {
            extract($args, EXTR_SKIP);
        }

        if (is_array($wp_query->query_vars)) {
            extract($wp_query->query_vars, EXTR_SKIP);
        }

        if (isset($s)) {
            $s = esc_attr($s);
        }

        $file_path = self::$options['themePath'] . '/' . $_template_file . '.php';

        if (isset($args['require_once']) && file_exists($file_path)) {
            require_once $file_path;
        } elseif (file_exists($file_path)) {
            require $file_path;
        } elseif (!file_exists($file_path)) {
            printf('Файла по пути %1s ненайден', $file_path);
        }
    }

    public static function theTemplate($_template_file, $args = [])
    {
        ob_start();
        self::template($_template_file, $args);
        echo ob_get_clean();
    }

    public static function getTemplate($_template_file, $args = [])
    {
        ob_start();
        self::template($_template_file, $args);
        return ob_get_clean();
    }

    /**
     * @param $query
     * @param $tmp_path
     * @param array $args
     */
    public static function loopPosts($query, $tmp_path, $args = []): void
    {
        $counter = 0;
        while ($query->have_posts()) {
            $query->the_post();
            global $post;
            echo $args['container']['start'] ?? null;

            self::theTemplate(
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

    public static function createPagination($posts, $args = []): void
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
        $starting_point = (($current_page - 1) * $posts_per_page);

        $big        = 999999999;
        $translated = __('', 'pixplus');

        paginate_links(
            [
                'base'               => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'             => '?paged=%#%',
                'current'            => $current_page,
                'total'              => $total_pages,
                'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
                'prev_text'          => ('<'),
                'next_text'          => __('>'),
            ]
        );
    }

    public static function getPostContent($action = 'echo'): ?string
    {
        global $post;

        if (get_the_content()) {
            if ($action === 'echo') {
                the_content();
            } elseif ($action === 'return') {
                return $post->post_content;
            }
        } else {
            if ($action === 'echo') {
                printf('<div class="not-content">%s</div>', __('Тут еще нет никакого описания, но оно скоро появится', 'amber'));
            } elseif ($action === 'return') {
                return sprintf('<div class="not-content">%s</div>', __('Тут еще нет никакого описания, но оно скоро появится', 'amber'));
            }
        }

        return null;
    }
}
