<?php

// Initialize theme setup and register menus
if (!function_exists('init_template')) {
    function init_template() {
        // Add support for post thumbnails and title tag
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');

        // Register navigation menus
        register_nav_menus(
            array(
                'top_menu' => __('Main Menu', 'platzigifts'), // Register a main/top menu
                'footer_menu' => __('Footer Menu', 'platzigifts') // Register a footer menu for added flexibility
            )
        );
    }
}
add_action('after_setup_theme', 'init_template');

// Register and enqueue styles and scripts
if (!function_exists('platzigift_enqueue_assets')) {
    function platzigift_enqueue_assets() {
        // Register Bootstrap CSS from jsDelivr (Bootstrap 5.3.0)
        wp_register_style(
            'bootstrap',
            esc_url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'),
            [],
            '5.3.0',
            'all'
        );

        // Register Google Fonts (Montserrat)
        wp_register_style(
            'montserrat',
            esc_url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap'),
            [],
            null,
            'all'
        );

        // Enqueue theme stylesheet and dependencies
        wp_enqueue_style(
            'theme-styles',
            get_stylesheet_uri(),
            ['bootstrap', 'montserrat'],
            wp_get_theme()->get('Version'),
            'all'
        );

        // Enqueue jQuery (bundled with WordPress)
        wp_enqueue_script('jquery');

        // Register Popper.js from jsDelivr (Corrected version)
        wp_register_script(
            'popper',
            esc_url('https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js'),
            [],
            '2.11.7',
            true
        );

        // Register Bootstrap JS from jsDelivr (Bootstrap 5.3.0)
        wp_register_script(
            'bootstrap-js',
            esc_url('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'),
            ['jquery', 'popper'],
            '5.3.0',
            true
        );

        // Enqueue the registered scripts
        wp_enqueue_script('bootstrap-js');

        // Enqueue custom JavaScript
        wp_enqueue_script(
            'custom',
            get_template_directory_uri() . '/assets/js/custom.js',
            ['jquery'], // Added jQuery as a dependency for custom JavaScript
            '1.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'platzigift_enqueue_assets');

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
