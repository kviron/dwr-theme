<?php
$tax_name = 'cat_example';
$post_reg = [
    'post_type' => 'example',
    'menu_name' => __('Название в меню', THEME_SLUG)
];

/**
 * Регистрация новой таксономии
 */
add_action( 'init', 'create_taxonomy_example' );
function create_taxonomy_example(){
    global $tax_name, $post_reg;
    // список параметров: wp-kama.ru/function/get_taxonomy_labels
    register_taxonomy( $tax_name, [ $post_reg['post_type'] ], [
        'label'                 => '', // определяется параметром $labels->name
        'labels'                => [
            'name'              => __('Категории', THEME_SLUG),
            'singular_name'     => __('Категория', THEME_SLUG),
            'search_items'      => __('Найти категорию', THEME_SLUG),
            'all_items'         => __('Все категории', THEME_SLUG),
            'view_item '        => __('Посмотреть категорию', THEME_SLUG),
            'parent_item'       => __('Родительская категория', THEME_SLUG),
            'parent_item_colon' => __('Родительские категории:', THEME_SLUG),
            'edit_item'         => __('Изменить категорию', THEME_SLUG),
            'update_item'       => __('Обновить', THEME_SLUG),
            'add_new_item'      => __('Добавить категорию', THEME_SLUG),
            'new_item_name'     => __('Новое имя категории', THEME_SLUG),
            'menu_name'         => __('Категории', THEME_SLUG),
        ],
        'description'           => '', // описание таксономии
        'public'                => true,
        // 'publicly_queryable'    => null, // равен аргументу public
        // 'show_in_nav_menus'     => true, // равен аргументу public
        // 'show_ui'               => true, // равен аргументу public
        // 'show_in_menu'          => true, // равен аргументу show_ui
        // 'show_tagcloud'         => true, // равен аргументу show_ui
        // 'show_in_quick_edit'    => null, // равен аргументу show_ui
        'hierarchical'          => false,

        'rewrite'               => true,
        //'query_var'             => $taxonomy, // название параметра запроса
        'capabilities'          => [],
        'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
        'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
        'show_in_rest'          => true, // добавить в REST API
        'rest_base'             => null, // $taxonomy
        // '_builtin'              => false,
        //'update_count_callback' => '_update_post_term_count',
    ] );
}


/**
 * Регистрация нового типа поста
 */
add_action('init', 'register_post_types_example');
function register_post_types_example()
{
    global $post_reg, $tax_name;
    register_post_type($post_reg['post_type'], [
        'label'         => null,
        'labels'        => [
            'name'               => $post_reg['menu_name'],      // основное название для типа записи
            'singular_name'      => __('____', THEME_SLUG),                  // название для одной записи этого типа
            'add_new'            => __('Добавить ____', THEME_SLUG),         // для добавления новой записи
            'add_new_item'       => __('Добавление ____', THEME_SLUG),       // заголовка у вновь создаваемой записи в админ-панели.
            'edit_item'          => __('Редактирование ____', THEME_SLUG),   // для редактирования типа записи
            'new_item'           => __('Новое ____', THEME_SLUG),            // текст новой записи
            'view_item'          => __('Смотреть ____', THEME_SLUG),         // для просмотра записи этого типа.
            'search_items'       => __('Искать ____', THEME_SLUG),           // для поиска по этим типам записи
            'not_found'          => __('Не найдено', THEME_SLUG),            // если в результате поиска ничего не было найдено
            'not_found_in_trash' => __('Не найдено в корзине', THEME_SLUG),  // если не было найдено в корзине
            'parent_item_colon'  => __('', THEME_SLUG),                      // для родителей (у древовидных типов)
            'menu_name'          => $post_reg['menu_name'],      // название меню
        ],
        'description'   => '',
        'public'        => true,
        // 'publicly_queryable'  => null,                    // зависит от public
        // 'exclude_from_search' => null,                    // зависит от public
        // 'show_ui'             => null,                    // зависит от public
        // 'show_in_nav_menus'   => null,                    // зависит от public
        'show_in_menu'  => null,                             // показывать ли в меню адмнки
        // 'show_in_admin_bar'   => null,                    // зависит от show_in_menu
        'show_in_rest'  => false,                            // Включаем поддержку Gutenberg
        'rest_base'     => $post_reg['post_type'],               // $post_type. C WP 4.7
        'menu_position' => null,
        'menu_icon'     => null,
        //'capability_type'   => 'post',
        //'capabilities'      => 'post',                     // массив дополнительных прав для этого типа записи
        //'map_meta_cap'      => null,                       // Ставим true чтобы включить дефолтный обработчик специальных прав
        'hierarchical'  => false,
        'supports'      => [
            'title',
            'editor',
            'post-formats'
        ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
        'taxonomies'    => [ $tax_name ],
        'has_archive'   => false,
        'rewrite'       => true,
        'query_var'     => true,
    ]);
}


/**
 * Добавляем типы записей в результат поиска
 */
add_action('pre_get_posts', 'get_example_search_filter');
function get_example_search_filter($query)
{
    global $post_reg;

    if (!is_admin() && $query->is_main_query() && $query->is_search) {
        $query->set('post_type', [
            $post_reg['post_type']
        ]);
    }
}
