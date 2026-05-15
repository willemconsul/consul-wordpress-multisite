<?php
/**
 * Consul Core - ACF Setup
 * 
 * Configuratie voor Advanced Custom Fields Pro
 */

if (!defined('ABSPATH')) {
    exit('No direct access allowed.');
}

// ACF JSON folder configuration
function consul_core_acf_json_save_point($path) {
    return CONSUL_CORE_DIR . 'acf-json';
}

function consul_core_acf_json_load_point($paths) {
    unset($paths[0]);
    $paths[] = CONSUL_CORE_DIR . 'acf-json';
    return $paths;
}

add_filter('acf/settings/save_json_dir', 'consul_core_acf_json_save_point');
add_filter('acf/settings/load_json_dir', 'consul_core_acf_json_load_point');

// Placeholder for ACF field group registrations
// Zal worden uitgebreid in Phase 4
