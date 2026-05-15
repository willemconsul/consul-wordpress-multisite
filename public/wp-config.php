<?php
/**
 * #ddev-generated: Automatically generated WordPress settings file.
 * ddev manages this file and may delete or overwrite the file unless this comment is removed.
 * It is recommended that you leave this file alone.
 *
 * @package ddevapp
 */

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', getenv( 'DB_CHARSET' ) ?: 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', getenv( 'DB_COLLATE' ) ?: '' );

/** Authentication Unique Keys and Salts. */
define( 'AUTH_KEY', 'BMJslkLswakXVNFnuNPUnFXDxIwCTfDIvXsERtCyTmDLTazcrzQypFVEPxGsHKxs' );
define( 'SECURE_AUTH_KEY', 'kanRzQLPHutwRMnFJPRhuzrKTplGNglnKcdDLabbzipfQwcDbHwbbLbxXtdhSyaw' );
define( 'LOGGED_IN_KEY', 'RQDuypmUSSBQhOPrnSplVeTuocOcyklWQVxDYaEVeIpIHjZwbmQcTBLTLJNzYIyz' );
define( 'NONCE_KEY', 'nqNqUsfwHJdORpAibHpUhJTqPttpTuklfaiIpXEhGrkcroTTmXWIwyVklBuYLwGx' );
define( 'AUTH_SALT', 'TfanJBaduqbYqTtJjfAEqpOTaBxdBwreqnffnZYbeoJSvQfuQJEEUWaPsvHNTyku' );
define( 'SECURE_AUTH_SALT', 'qemJcnRQarSJOgOsEJEazAWsMmtcvKszJtHuimQaMGzdrkBmDeuUnkaKfUgQVgKC' );
define( 'LOGGED_IN_SALT', 'UkvbWrysrEFgCASWeXcXWgOzWlWrkhrohKJCmtVAzpXbhsBIRrtuxISDbuRiCRIC' );
define( 'NONCE_SALT', 'VTUxFTAdBBZIuJxeNTOFwrhUbQSwrqIcDpNYWeRDhZhleJKKUpweZVwluxqarMyG' );

/* Add any custom values between this line and the "stop editing" line. */



define( 'WP_ALLOW_MULTISITE', 'true' );
define( 'MULTISITE', 'true' );
define( 'SUBDOMAIN_INSTALL', 'true' );
define( 'DOMAIN_CURRENT_SITE', 'consul-wp-ms.ddev.site' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', '1' );
define( 'BLOG_ID_CURRENT_SITE', '1' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
defined( 'ABSPATH' ) || define( 'ABSPATH', __DIR__ . '/' );

// Include for settings managed by ddev.
$ddev_settings = __DIR__ . '/wp-config-ddev.php';
if ( ! defined( 'DB_USER' ) && getenv( 'IS_DDEV_PROJECT' ) === 'true' && is_readable( $ddev_settings ) ) {
	require_once( $ddev_settings );
}

/** Include wp-settings.php */
if ( file_exists( ABSPATH . '/wp-settings.php' ) ) {
	require_once ABSPATH . '/wp-settings.php';
}
