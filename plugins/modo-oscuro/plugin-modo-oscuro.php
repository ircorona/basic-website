<?php
/*
Plugin Name: Modo Oscuro
Description: Activa el modo oscuro en tu theme para un diseño más amigable con la vista.
Version: 1.0
Author: Irmin Corona
Author URI: https://github.com/ircorona
*/

// Enqueue the dark mode stylesheet
function estilos_plugin() {
    // Get the URL of the plugin's directory
    $plugin_url = plugin_dir_url(__FILE__);

    // Enqueue the CSS file
    wp_enqueue_style(
        'modo-oscuro', // Unique handle for the stylesheet
        $plugin_url . 'assets/css/style.css', // URL to the stylesheet
        array(), // No dependencies
        '1.0', // Version of the stylesheet
        'all' // Media type (all devices)
    );
}
add_action('wp_enqueue_scripts', 'estilos_plugin');
