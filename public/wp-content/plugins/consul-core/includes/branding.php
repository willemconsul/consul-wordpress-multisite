<?php
/**
 * Consul Core - Branding Setup
 * 
 * Configuratie voor Consul Infra huisstijl, inclusief:
 * - Kleuren en typografie
 * - Logo en favicon
 * - WordPress Customizer instellingen
 * - CSS variabelen
 */

if (!defined('ABSPATH')) {
    exit('No direct access allowed.');
}

/**
 * Define Brand Constants
 * Deze zijn standaard Consul Infra kleuren en fonts
 * Per site kunnen deze worden overridden via Customizer
 */

// Primaire Consul Infra kleuren
const CONSUL_BRAND_PRIMARY = '#004A90';        // Blauw
const CONSUL_BRAND_SECONDARY = '#FF6B35';     // Oranje
const CONSUL_BRAND_DARK = '#1A1A1A';          // Donkergrijs
const CONSUL_BRAND_LIGHT = '#F5F5F5';         // Lichtgrijs
const CONSUL_BRAND_ACCENT = '#00BCD4';        // Cyaan

// Typografie
const CONSUL_FONT_PRIMARY = '"Segoe UI", -apple-system, BlinkMacSystemFont, "Helvetica Neue", sans-serif';
const CONSUL_FONT_HEADING = '"Segoe UI", -apple-system, BlinkMacSystemFont, "Helvetica Neue", sans-serif';
const CONSUL_FONT_MONO = '"Courier New", monospace';

/**
 * Initialize Customizer Settings
 * Voegt branding opties toe aan WordPress Customizer
 */
function consul_core_customize_register($wp_customize) {
    // Section voor branding
    $wp_customize->add_section('consul_branding', array(
        'title' => __('Consul Branding', 'consul-core'),
        'priority' => 30,
        'description' => __('Pas de huisstijl aan per site', 'consul-core'),
    ));

    // Primaire kleur
    $wp_customize->add_setting('consul_color_primary', array(
        'default' => CONSUL_BRAND_PRIMARY,
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize, 'consul_color_primary', array(
            'label' => __('Primaire kleur', 'consul-core'),
            'section' => 'consul_branding',
            'settings' => 'consul_color_primary',
        ))
    );

    // Secundaire kleur
    $wp_customize->add_setting('consul_color_secondary', array(
        'default' => CONSUL_BRAND_SECONDARY,
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize, 'consul_color_secondary', array(
            'label' => __('Secundaire kleur', 'consul-core'),
            'section' => 'consul_branding',
            'settings' => 'consul_color_secondary',
        ))
    );

    // Logo
    $wp_customize->add_setting('consul_logo_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(
        new WP_Customize_Image_Control($wp_customize, 'consul_logo_url', array(
            'label' => __('Logo', 'consul-core'),
            'section' => 'title_tagline',
            'settings' => 'consul_logo_url',
        ))
    );
}
add_action('customize_register', 'consul_core_customize_register');

/**
 * Output CSS variables based on Customizer settings
 */
function consul_core_output_css_variables() {
    $color_primary = get_theme_mod('consul_color_primary', CONSUL_BRAND_PRIMARY);
    $color_secondary = get_theme_mod('consul_color_secondary', CONSUL_BRAND_SECONDARY);
    
    // Zorg voor geldige hex kleuren
    $color_primary = sanitize_hex_color($color_primary) ?: CONSUL_BRAND_PRIMARY;
    $color_secondary = sanitize_hex_color($color_secondary) ?: CONSUL_BRAND_SECONDARY;
    
    ?>
    <style id="consul-core-css-variables">
        :root {
            --consul-primary: <?php echo esc_attr($color_primary); ?>;
            --consul-secondary: <?php echo esc_attr($color_secondary); ?>;
            --consul-dark: <?php echo esc_attr(CONSUL_BRAND_DARK); ?>;
            --consul-light: <?php echo esc_attr(CONSUL_BRAND_LIGHT); ?>;
            --consul-accent: <?php echo esc_attr(CONSUL_BRAND_ACCENT); ?>;
            
            --consul-font-primary: <?php echo esc_attr(CONSUL_FONT_PRIMARY); ?>;
            --consul-font-heading: <?php echo esc_attr(CONSUL_FONT_HEADING); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'consul_core_output_css_variables');
add_action('admin_head', 'consul_core_output_css_variables');

/**
 * Display Logo in Header
 */
function consul_core_display_logo() {
    $logo_url = get_theme_mod('consul_logo_url');
    
    if (!$logo_url) {
        // Standaard Consul logo
        $logo_url = CONSUL_CORE_ASSETS . 'img/consul-logo.svg';
    }
    
    if ($logo_url && file_exists(ABSPATH . wp_parse_url($logo_url, PHP_URL_PATH))) {
        $site_url = get_site_url();
        ?>
        <a href="<?php echo esc_url($site_url); ?>" class="site-logo">
            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" class="logo-image">
        </a>
        <?php
    }
}

/**
 * Add body class for site branding
 */
function consul_core_body_class($classes) {
    $classes[] = 'consul-core-active';
    $classes[] = 'blog-' . get_current_blog_id();
    return $classes;
}
add_filter('body_class', 'consul_core_body_class');

/**
 * Helper function: Get brand color
 */
function consul_get_brand_color($type = 'primary') {
    $color_map = array(
        'primary' => get_theme_mod('consul_color_primary', CONSUL_BRAND_PRIMARY),
        'secondary' => get_theme_mod('consul_color_secondary', CONSUL_BRAND_SECONDARY),
        'dark' => CONSUL_BRAND_DARK,
        'light' => CONSUL_BRAND_LIGHT,
        'accent' => CONSUL_BRAND_ACCENT,
    );
    
    return isset($color_map[$type]) ? sanitize_hex_color($color_map[$type]) : '';
}
