<?php

// Initialize theme setup and register menus
if (!function_exists('init_template')) {
    function init_template() {
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');

        register_nav_menus(
            array(
                'top_menu' => __('Main Menu', 'platzigifts'),
                'footer_menu' => __('Footer Menu', 'platzigifts')
            )
        );
    }
}
add_action('after_setup_theme', 'init_template');

// Register and enqueue styles and scripts
if (!function_exists('platzigift_enqueue_assets')) {
    function platzigift_enqueue_assets() {
        wp_register_style(
            'bootstrap',
            esc_url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'),
            [],
            '5.3.0',
            'all'
        );

        wp_register_style(
            'montserrat',
            esc_url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap'),
            [],
            null,
            'all'
        );

        wp_enqueue_style(
            'theme-styles',
            get_stylesheet_uri(),
            ['bootstrap', 'montserrat'],
            wp_get_theme()->get('Version'),
            'all'
        );

        wp_enqueue_script('jquery');

        wp_register_script(
            'popper',
            esc_url('https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js'),
            [],
            '2.11.7',
            true
        );

        wp_register_script(
            'bootstrap-js',
            esc_url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'),
            ['jquery', 'popper'],
            '5.3.0',
            true
        );

        wp_enqueue_script('bootstrap-js');

        wp_enqueue_script(
            'custom',
            get_template_directory_uri() . '/assets/js/custom.js',
            ['jquery'],
            wp_get_theme()->get('Version'),
            true
        );

        wp_localize_script('custom', 'pg', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'apiurl'  => home_url('wp-json/pg/v1/')
        ));
    }
}
add_action('wp_enqueue_scripts', 'platzigift_enqueue_assets');

// Register Custom Block Category
function pgRegisterBlockCategory($categories) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'platzigifts',
                'title' => 'Platzi Gifts'
            )
        )
    );
}
add_filter('block_categories_all', 'pgRegisterBlockCategory', 10, 2);

// Register sidebar widget area
if (!function_exists('platzigift_register_sidebar')) {
    function platzigift_register_sidebar() {
        register_sidebar(
            array(
                'name'          => __('Footer Widget Area', 'platzigifts'),
                'id'            => 'footer',
                'description'   => __('Widget area for the footer section', 'platzigifts'),
                'before_title'  => '<p class="widget-title">',
                'after_title'   => '</p>',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
            )
        );
    }
}
add_action('widgets_init', 'platzigift_register_sidebar');

// Register Custom Post Type for Products
if (!function_exists('platzigift_register_product_post_type')) {
    function platzigift_register_product_post_type() {
        $labels = array(
            'name'                  => __('Products', 'platzigifts'),
            'singular_name'         => __('Product', 'platzigifts'),
            'menu_name'             => __('Products', 'platzigifts'),
            'name_admin_bar'        => __('Product', 'platzigifts'),
            'add_new'               => __('Add New', 'platzigifts'),
            'add_new_item'          => __('Add New Product', 'platzigifts'),
            'new_item'              => __('New Product', 'platzigifts'),
            'edit_item'             => __('Edit Product', 'platzigifts'),
            'view_item'             => __('View Product', 'platzigifts'),
            'all_items'             => __('All Products', 'platzigifts'),
            'search_items'          => __('Search Products', 'platzigifts'),
            'parent_item_colon'     => __('Parent Products:', 'platzigifts'),
            'not_found'             => __('No products found.', 'platzigifts'),
            'not_found_in_trash'    => __('No products found in Trash.', 'platzigifts'),
        );

        $args = array(
            'label'                 => __('Products', 'platzigifts'),
            'description'           => __('Platzi Gifts Products', 'platzigifts'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
            'public'                => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-cart',
            'can_export'            => true,
            'publicly_queryable'    => true,
            'rewrite'               => array('slug' => 'product'),
            'show_in_rest'          => true
        );

        register_post_type('product', $args);
    }
}
add_action('init', 'platzigift_register_product_post_type');

// Register Custom Taxonomy for Product Categories
if (!function_exists('pgRegisterTax')) {
    function pgRegisterTax() {
        $args = array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'              => __('Products Categories', 'platzigifts'),
                'singular_name'     => __('Product Category', 'platzigifts'),
            ),
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'product-categories'),
        );

        register_taxonomy('product-categories', array('product'), $args);
    }
}
add_action('init', 'pgRegisterTax');

// Pass Product Title to Contact Form 7
add_filter('wpcf7_form_hidden_fields', 'add_product_title_to_cf7');
function add_product_title_to_cf7($hidden_fields) {
    if (is_singular('product')) {
        $hidden_fields['product-title'] = get_the_title();
    }
    return $hidden_fields;
}

function pg_register_dynamic_block() {
    // Get the asset file generated by the build
    $assets = include_once get_template_directory() . '/blocks/build/index.asset.php';
    
    // Register JavaScript build
    wp_register_script(
        'pg-basic-block',
        get_template_directory_uri() . '/blocks/build/index.js',
        $assets['dependencies'],
        $assets['version']
    );

    // Register block type with render callback
    register_block_type('pg/basic', array(
        'editor_script' => 'pg-basic-block',
        'render_callback' => 'pgRenderDynamicBlock',
        'attributes' => array(
            'content' => array(
                'type' => 'string',
                'default' => 'Hello world'
            ),
            'imageUrl' => array(
                'type' => 'string',
                'default' => ''
            ),
            'imageId' => array(
                'type' => 'number',
                'default' => 0
            ),
            'imageAlt' => array(
                'type' => 'string',
                'default' => ''
            )
        )
    ));
}
add_action('init', 'pg_register_dynamic_block');

// Add this render callback function
function pgRenderDynamicBlock($attributes) {
    // Get the attributes with defaults
    $content = isset($attributes['content']) ? $attributes['content'] : 'Hello world';
    $image_url = isset($attributes['imageUrl']) ? $attributes['imageUrl'] : '';
    $image_alt = isset($attributes['imageAlt']) ? $attributes['imageAlt'] : '';
    
    // Build the HTML
    $html = '<div class="wp-block-pg-basic">';
    
    // Add image if exists
    if (!empty($image_url)) {
        $html .= sprintf(
            '<div class="block-image"><img src="%s" alt="%s" /></div>',
            esc_url($image_url),
            esc_attr($image_alt)
        );
    }
    
    // Add content
    $html .= sprintf('<h3>%s</h3>', esc_html($content));
    
    $html .= '</div>';
    
    return $html;
}

// API Endpoints
add_action('rest_api_init', 'novedadesAPI');
function novedadesAPI() {
    register_rest_route(
        'pg/v1', 
        '/novedades/(?P<cantidad>\d+)', 
        array(
            'methods'  => 'GET',
            'callback' => 'pedidoNovedades',
        )
    );
}

function pedidoNovedades($data) {
    $cantidad = isset($data['cantidad']) ? (int)$data['cantidad'] : 5;
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $cantidad,
        'order'          => 'ASC',
        'orderby'        => 'title'
    );
    
    $novedades = new WP_Query($args);
    $return = array();
    
    if ($novedades->have_posts()) {
        while ($novedades->have_posts()) {
            $novedades->the_post();
            $return[] = array(
                'imagen' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                'link'   => get_the_permalink(),
                'titulo' => get_the_title()
            );
        }
    }
    wp_reset_postdata();
    return $return;
}

add_action('rest_api_init', 'productosDestacadosAPI');
function productosDestacadosAPI() {
    register_rest_route(
        'pg/v1',
        '/productos-destacados/(?P<cantidad>\d+)',
        array(
            'methods'  => 'GET',
            'callback' => 'pedidoProductosDestacados',
        )
    );
}

function pedidoProductosDestacados($data) {
    $cantidad = isset($data['cantidad']) ? (int)$data['cantidad'] : 5;
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $cantidad,
        'order'          => 'ASC',
        'orderby'        => 'title',
        'meta_query'     => array(
            array(
                'key'     => 'destacado',
                'value'   => 'si',
                'compare' => '='
            )
        )
    );
    
    $productos = new WP_Query($args);
    $return = array();
    
    if ($productos->have_posts()) {
        while ($productos->have_posts()) {
            $productos->the_post();
            $return[] = array(
                'imagen' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                'link'   => get_the_permalink(),
                'titulo' => get_the_title(),
                'precio' => get_post_meta(get_the_ID(), 'precio', true)
            );
        }
    }
    wp_reset_postdata();
    return $return;
}

// Debugging block registration
function debug_block_registration() {
    // Check block type registry
    if (function_exists('register_block_type')) {
        error_log('Block type registration is available');
        
        // Check if build directory exists
        $build_dir = get_template_directory() . '/blocks/build';
        error_log('Build directory path: ' . $build_dir);
        error_log('Build directory exists: ' . (file_exists($build_dir) ? 'yes' : 'no'));
        
        // Check if main block files exist
        error_log('index.js exists: ' . (file_exists($build_dir . '/index.js') ? 'yes' : 'no'));
        error_log('index.asset.php exists: ' . (file_exists($build_dir . '/index.asset.php') ? 'yes' : 'no'));
    }
}
add_action('init', 'debug_block_registration', 9); // Run before block registration