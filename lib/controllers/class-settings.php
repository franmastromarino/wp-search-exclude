<?php

namespace QuadLayers\QLSE\Controllers;

class Settings {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

	}

	public function register_scripts() {
		$content = include QLSE_PLUGIN_DIR . 'build/settings/js/index.asset.php';
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
				'qlse-components',
				'wp-components',
			),
			QLSE_PLUGIN_VERSION
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
