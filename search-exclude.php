<?php

/**
 * Plugin Name:             Search Exclude
 * Plugin URI:              https://wordpress.org/plugins/search-exclude
 * Description:             Hide any page or post from the WordPress search results by checking off the checkbox.
 * Version:                 2.0.7
 * Text Domain:             search-exclude
 * Author:                  QuadLayers
 * Author URI:              https://quadlayers.com
 * License:                 GPLv3
 * Domain Path:             /languages
 * Request at least:        4.7.0
 * Tested up to:            6.4
 * Requires PHP:            5.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
*   Definition globals variables
*/

define( 'QLSE_PLUGIN_NAME', 'Search Exclude' );
define( 'QLSE_PLUGIN_VERSION', '2.0.7' );
define( 'QLSE_PLUGIN_FILE', __FILE__ );
define( 'QLSE_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR );
define( 'QLSE_DOMAIN', 'qlse' );
define( 'QLSE_PREFIX', QLSE_DOMAIN );
define( 'QLSE_WORDPRESS_URL', 'https://wordpress.org/plugins/search-exclude/' );
define( 'QLSE_REVIEW_URL', 'https://wordpress.org/support/plugin/search-exclude/reviews/?filter=5#new-post' );
define( 'QLSE_DEMO_URL', 'https://quadlayers.com/demo/search-exclude/?utm_source=qlse_admin' );
define( 'QLSE_PURCHASE_URL', 'https://quadlayers.com/?utm_source=qlse_admin' );
define( 'QLSE_SUPPORT_URL', 'https://quadlayers.com/account/support/?utm_source=qlse_admin' );
define( 'QLSE_DOCUMENTATION_URL', 'https://quadlayers.com/documentation/search-exclude/?utm_source=qlse_admin' );
define( 'QLSE_DOCUMENTATION_API_URL', 'https://quadlayers.com/documentation/search-exclude/api/?utm_source=qlse_admin' );
define( 'QLSE_DOCUMENTATION_ACCOUNT_URL', 'https://quadlayers.com/documentation/search-exclude/account/?utm_source=qlse_admin' );
define( 'QLSE_GROUP_URL', 'https://www.facebook.com/groups/quadlayers' );
define( 'QLSE_DEVELOPER', false );
define( 'QLSE_PREMIUM_SELL_URL', 'https://quadlayers.com/search-exclude-pro' );

/**
 * Load composer autoload
 */
require_once __DIR__ . '/vendor/autoload.php';
/**
 * Load vendor_packages packages
 */
require_once __DIR__ . '/vendor_packages/wp-i18n-map.php';
require_once __DIR__ . '/vendor_packages/wp-dashboard-widget-news.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-table-links.php';
require_once __DIR__ . '/vendor_packages/wp-plugin-install-tab.php';
/**
 * Load plugin classes
 */
require_once __DIR__ . '/lib/class-plugin.php';
/**
 * On plugin activation
 */
register_activation_hook(
	__FILE__,
	function() {
		do_action( 'qlse_activation' );
	}
);
/**
 * On plugin deactivation
 */
register_deactivation_hook(
	__FILE__,
	function() {
		do_action( 'qlse_deactivation' );
	}
);
