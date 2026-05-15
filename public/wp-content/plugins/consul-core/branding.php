<?php
if (!defined('ABSPATH')) exit;
define('CONSUL_PRIMARY_COLOR', '#192548');
define('CONSUL_ACCENT_COLOR', '#da0f22');
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('consul-fonts', 'https://fonts.googleapis.com/css2?family=Lato:wght@400;700');
    wp_enqueue_style('consul-branding', CONSUL_CORE_URL . 'assets/css/branding.css');
    wp_add_inline_style('consul-branding', ':root{--consul-primary:' . get_option('consul_primary_color', CONSUL_PRIMARY_COLOR) . ';--consul-accent:' . get_option('consul_accent_color', CONSUL_ACCENT_COLOR) . ';}');
});
add_action('customize_register', function($wp_customize) {
    $wp_customize->add_section('consul_branding', ['title' => 'Consul Branding']);
    $wp_customize->add_setting('consul_primary_color', ['default' => CONSUL_PRIMARY_COLOR, 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'consul_primary_color', ['label' => 'Primaire Kleur', 'section' => 'consul_branding']));
    $wp_customize->add_setting('consul_accent_color', ['default' => CONSUL_ACCENT_COLOR, 'sanitize_callback' => 'sanitize_hex_color']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'consul_accent_color', ['label' => 'Accent Kleur', 'section' => 'consul_branding']));
});