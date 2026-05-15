<?php
/**
 * Plugin Name: Consul Core
 * Plugin URI: https://consulinfra.nl
 * Description: Gedeelde core plugin voor Consul Infra Multisite. Bevat branding, ACF setup, custom post types, en herbruikbare blokken.
 * Version: 1.0.0
 * Author: Consul Infra
 * Author URI: https://consulinfra.nl
 * License: GPL-2.0+
 * Text Domain: consul-core
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.2
 * Network: true
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('No direct access allowed.');
}

// Define plugin constants
define('CONSUL_CORE_VERSION', '1.0.0');
define('CONSUL_CORE_DIR', plugin_dir_path(__FILE__));
define('CONSUL_CORE_URL', plugin_dir_url(__FILE__));
define('CONSUL_CORE_ASSETS', CONSUL_CORE_URL . 'assets/');

/**
 * Load plugin includes
 */
function consul_core_load_includes() {
    require_once CONSUL_CORE_DIR . 'includes/branding.php';
    require_once CONSUL_CORE_DIR . 'includes/post-types.php';
    require_once CONSUL_CORE_DIR . 'includes/taxonomies.php';
    require_once CONSUL_CORE_DIR . 'includes/acf-setup.php';
}
add_action('plugins_loaded', 'consul_core_load_includes');

/**
 * Enqueue styles
 */
function consul_core_enqueue_styles() {
    wp_enqueue_style(
        'consul-core-branding',
        CONSUL_CORE_ASSETS . 'css/branding.css',
        [],
        CONSUL_CORE_VERSION
    );
}
add_action('wp_enqueue_scripts', 'consul_core_enqueue_styles');
add_action('admin_enqueue_scripts', 'consul_core_enqueue_styles');

// Activation hook
register_activation_hook(__FILE__, 'consul_core_activate');
function consul_core_activate() {
    // Future: any activation tasks
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'consul_core_deactivate');
function consul_core_deactivate() {
    // Future: any cleanup tasks
}
