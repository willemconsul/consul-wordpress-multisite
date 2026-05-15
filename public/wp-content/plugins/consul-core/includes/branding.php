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
 * Define Brand Constants - Consul Infra Official Colors
 * Basis Consul Infra branding, per site aanpasbaar via Customizer
 */

// Primaire Consul Infra kleuren (officieeel)
const CONSUL_BRAND_PRIMARY = '#004B87';        // Blauw (Consul Infra primair)
const CONSUL_BRAND_SECONDARY = '#ED7D31';     // Oranje (Consul Infra accent)
const CONSUL_BRAND_DARK = '#1F1F1F';          // Donkergrijs
const CONSUL_BRAND_LIGHT = '#F8F8F8';         // Lichtgrijs
const CONSUL_BRAND_ACCENT = '#70AD47';        // Groen accent

// Typografie
const CONSUL_FONT_PRIMARY = '"Segoe UI", -apple-system, BlinkMacSystemFont, "Helvetica Neue", sans-serif';
const CONSUL_FONT_HEADING = '"Segoe UI", -apple-system, BlinkMacSystemFont, "Helvetica Neue", sans-serif';

// Assets
const CONSUL_LOGO_DEFAULT = CONSUL_CORE_ASSETS . 'img/consul-logo.png';
const CONSUL_FAVICON_DEFAULT = CONSUL_CORE_ASSETS . 'img/favicon.ico';

/**
 * Initialize Customizer Settings
 * Voegt branding opties toe aan WordPress Customizer
 */
function consul_core_customize_register($wp_customize) {
    // Section voor branding
    $wp_customize->add_section('consul_branding', array(
        'title' => __('Consul Infra Branding', 'consul-core'),
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
            'label' => __('Primaire kleur (blauw)', 'consul-core'),
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
            'label' => __('Secundaire kleur (oranje)', 'consul-core'),
            'section' => 'consul_branding',
            'settings' => 'consul_color_secondary',
        ))
    );

    // Accent kleur
    $wp_customize->add_setting('consul_color_accent', array(
        'default' => CONSUL_BRAND_ACCENT,
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize, 'consul_color_accent', array(
            'label' => __('Accent kleur (groen)', 'consul-core'),
            'section' => 'consul_branding',
            'settings' => 'consul_color_accent',
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
    $color_accent = get_theme_mod('consul_color_accent', CONSUL_BRAND_ACCENT);
    
    // Zorg voor geldige hex kleuren
    $color_primary = sanitize_hex_color($color_primary) ?: CONSUL_BRAND_PRIMARY;
    $color_secondary = sanitize_hex_color($color_secondary) ?: CONSUL_BRAND_SECONDARY;
    $color_accent = sanitize_hex_color($color_accent) ?: CONSUL_BRAND_ACCENT;
    
    ?>
    <style id="consul-core-css-variables">
        :root {
            --consul-primary: <?php echo esc_attr($color_primary); ?>;
            --consul-secondary: <?php echo esc_attr($color_secondary); ?>;
            --consul-accent: <?php echo esc_attr($color_accent); ?>;
            --consul-dark: <?php echo esc_attr(CONSUL_BRAND_DARK); ?>;
            --consul-light: <?php echo esc_attr(CONSUL_BRAND_LIGHT); ?>;
            
            --consul-font-primary: <?php echo esc_attr(CONSUL_FONT_PRIMARY); ?>;
            --consul-font-heading: <?php echo esc_attr(CONSUL_FONT_HEADING); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'consul_core_output_css_variables');
add_action('admin_head', 'consul_core_output_css_variables');

/**
 * Register Favicon
 */
function consul_core_register_favicon() {
    $favicon_url = CONSUL_FAVICON_DEFAULT;
    
    // Check if favicon exists, if not use default
    $favicon_path = str_replace(site_url('/'), ABSPATH, $favicon_url);
    if (!file_exists($favicon_path)) {
        // Fallback: use PNG logo as favicon fallback
        $favicon_url = CONSUL_LOGO_DEFAULT;
    }
    
    ?>
    <link rel="icon" type="image/png" href="<?php echo esc_url($favicon_url); ?>" />
    <link rel="apple-touch-icon" href="<?php echo esc_url($favicon_url); ?>" />
    <?php
}
add_action('wp_head', 'consul_core_register_favicon');

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
        'accent' => get_theme_mod('consul_color_accent', CONSUL_BRAND_ACCENT),
        'dark' => CONSUL_BRAND_DARK,
        'light' => CONSUL_BRAND_LIGHT,
    );
    
    return isset($color_map[$type]) ? sanitize_hex_color($color_map[$type]) : '';
}

/**
 * Helper function: Get logo URL
 */
function consul_get_logo_url() {
    return CONSUL_LOGO_DEFAULT;
}
