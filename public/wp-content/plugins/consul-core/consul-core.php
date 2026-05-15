<?php
/**
 * Plugin Name: Consul Core
 * Plugin URI: https://consulinfra.nl
 * Description: Core plugin voor Consul Infra branding en functionalieit op alle sites
 * Version: 1.0.0
 * Author: Consul Infra
 * Author URI: https://consulinfra.nl
 * License: GPL v2 or later
 * Network: true
 * Domain Path: /languages
 * Text Domain: consul-core
 */

// Voorkomen dat het bestand direct wordt uitgevoerd
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constanten
define('CONSUL_CORE_PATH', plugin_dir_path(__FILE__));
define('CONSUL_CORE_URL', plugin_dir_url(__FILE__));

// Includes
require_once CONSUL_CORE_PATH . 'includes/branding.php';
require_once CONSUL_CORE_PATH . 'includes/post-types.php';
require_once CONSUL_CORE_PATH . 'includes/taxonomies.php';
require_once CONSUL_CORE_PATH . 'includes/acf-setup.php';

// Hook voor plugin activatie
register_activation_hook(__FILE__, 'consul_core_activate');
function consul_core_activate() {
    // Placeholder voor activatie-logica
}

// Hook voor plugin deactivatie
register_deactivation_hook(__FILE__, 'consul_core_deactivate');
function consul_core_deactivate() {
    // Placeholder voor deactivatie-logica
}
