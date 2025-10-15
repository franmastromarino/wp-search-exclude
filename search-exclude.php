<?php

/**
 * Plugin Name:             Search Exclude
 * Plugin URI:              https://wordpress.org/plugins/search-exclude
 * Description:             Hide any page or post from the WordPress search results by checking off the checkbox.
 * Version:                 2.5.8
 * Text Domain:             search-exclude
 * Author:                  QuadLayers
 * Author URI:              https://quadlayers.com
 * License:                 GPLv3
 * Domain Path:             /languages
 * Request at least:        4.7
 * Tested up to:            6.8
 * Requires PHP:            5.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
*   Definition globals variables
*/
define( 'QLSE_PLUGIN_NAME', 'Search Exclude' );
define( 'QLSE_PLUGIN_VERSION', '2.5.8' );
define( 'QLSE_PLUGIN_FILE', __FILE__ );
define( 'QLSE_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR );
define( 'QLSE_DOMAIN', 'qlse' );
define( 'QLSE_PREFIX', QLSE_DOMAIN );
define( 'QLSE_WORDPRESS_URL', 'https://wordpress.org/plugins/search-exclude/' );
define( 'QLSE_REVIEW_URL', 'https://wordpress.org/support/plugin/search-exclude/reviews/?filter=5#new-post' );
define( 'QLSE_GROUP_URL', 'https://www.facebook.com/groups/quadlayers' );
define( 'QLSE_DEVELOPER', false );
/**
 * Load composer autoload
 */
require_once __DIR__ . '/vendor/autoload_packages.php';
/**
 * Load compatibility
 */
require_once __DIR__ . '/compatibility/old.php';
/**
 * Load vendor_packages packages
 */
require_once __DIR__ . '/vendor_packages/wp-i18n-map.php';
require_once __DIR__ . '/vendor_packages/wp-dashboard-widget-news.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-table-links.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-install-tab.php';
require_once __DIR__ . '/vendor_packages/wp-notice-plugin-promote.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-feedback.php';
/**
 * Load plugin classes
 */
require_once __DIR__ . '/lib/class-plugin.php';
/**
 * On plugin activation
 */
register_activation_hook(
	__FILE__,
	function () {
		do_action( 'qlse_activation' );
	}
);
/**
 * On plugin deactivation
 */
register_deactivation_hook(
	__FILE__,
	function () {
		do_action( 'qlse_deactivation' );
	}
);
