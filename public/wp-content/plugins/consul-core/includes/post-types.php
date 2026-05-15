<?php
/**
 * Consul Core - Custom Post Types
 * 
 * Registreer custom post types voor Multisite sites:
 * - 12Tender: Tenders
 * - Civiele Toekomstbouwers: Events, Projects
 * - MKI: Kennisartikelen
 */

if (!defined('ABSPATH')) {
    exit('No direct access allowed.');
}

// Placeholder for post type registrations
// Zal worden uitgebreid op basis van site-specifieke behoeften

function consul_core_register_post_types() {
    // Post types worden per site geregistreerd in Phase 3
    do_action('consul_core_register_post_types');
}
add_action('init', 'consul_core_register_post_types');
