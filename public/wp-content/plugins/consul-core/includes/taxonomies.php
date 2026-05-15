<?php
/**
 * Consul Core - Custom Taxonomies
 */

if (!defined('ABSPATH')) {
    exit('No direct access allowed.');
}

function consul_core_register_taxonomies() {
    // Taxonomies worden per site geregistreerd in Phase 3
    do_action('consul_core_register_taxonomies');
}
add_action('init', 'consul_core_register_taxonomies');
