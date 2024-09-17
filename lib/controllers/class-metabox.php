<?php

namespace QuadLayers\QLSE\Controllers;

class Metabox {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function register_scripts() {
		$content = include QLSE_PLUGIN_DIR . 'build/metabox/js/index.asset.php';
		/**
		 * Register metabox assets
		 */
		wp_register_script(
			'search-exclude-metabox',
			plugins_url( '/build/metabox/js/index.js', QLSE_PLUGIN_FILE ),
			$content['dependencies'],
			$content['version'],
			true
		);

		wp_register_style(
			'search-exclude-metabox',
			plugins_url( '/build/metabox/css/style.css', QLSE_PLUGIN_FILE ),
			array(
				'search-exclude-components',
				'wp-components',
			),
			QLSE_PLUGIN_VERSION
		);
	}

	public function enqueue_scripts() {

		wp_enqueue_script( 'search-exclude-metabox' );
		wp_enqueue_style( 'search-exclude-metabox' );
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
