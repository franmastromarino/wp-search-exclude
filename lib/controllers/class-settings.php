<?php

namespace QuadLayers\QLSE\Controllers;

use QuadLayers\QLSE\Services\Entity_Options;

class Settings {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function register_scripts() {
		$content        = include QLSE_PLUGIN_DIR . 'build/settings/js/index.asset.php';
		$entity_options = Entity_Options::instance();

		/**
		 * Register settings assets
		 */
		wp_register_script(
			'qlse-settings',
			plugins_url( '/build/settings/js/index.js', QLSE_PLUGIN_FILE ),
			$content['dependencies'],
			$content['version'],
			true
		);

		wp_register_style(
			'qlse-settings',
			plugins_url( '/build/settings/css/style.css', QLSE_PLUGIN_FILE ),
			array(
				'wp-components',
			),
			QLSE_PLUGIN_VERSION
		);

		wp_localize_script(
			'qlse-settings',
			'qlseSettings',
			array(
				'QLSE_DISPLAY_POST_TYPES' => $entity_options->get_entries(),
				'QLSE_DISPLAY_TAXONOMIES' => $entity_options->get_taxonomies(),
				'QLSE_PLUGIN_URL'         => plugins_url( '/', QLSE_PLUGIN_FILE ),
				'QLSE_PLUGIN_NAME'        => QLSE_PLUGIN_NAME,
				'QLSE_PREMIUM_SELL_URL'   => QLSE_PREMIUM_SELL_URL,
				'QLSE_DEMO_URL'           => QLSE_DEMO_URL,
				'QLSE_DOCUMENTATION_URL'  => QLSE_DOCUMENTATION_URL,
			)
		);
	}

	public function admin_menu() {
		add_options_page(
			'Search Exclude',
			'Search Exclude',
			'manage_options',
			'search_exclude',
			array( $this, 'admin_menu_settings' )
		);
	}

	public function admin_menu_settings() {
		?>
		<div class="wrap" id="search-exclude-settings">
		</div>
		<?php
	}


	public function enqueue_scripts() {
		$current_screen  = get_current_screen()->id;
		$allowed_screens = 'settings_page_search_exclude';

		if ( $current_screen !== $allowed_screens ) {
			return;
		}

		wp_enqueue_script( 'qlse-settings' );
		wp_enqueue_style( 'qlse-settings' );
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
