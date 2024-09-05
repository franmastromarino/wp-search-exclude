<?php

namespace QuadLayers\QLSE;

final class Plugin {

	private static $instance;
	protected $excluded;

	private function __construct() {
		/**
		* Load plugin textdomain.
		*/
		load_plugin_textdomain( 'search-exclude', false, QLSE_PLUGIN_DIR . '/languages/' );
		/**
		 * On activation
		 */
		add_action( 'qlse_activation', array( $this, 'activate' ) );

		Controllers\Backend::instance();
		Controllers\Frontend::instance();
	}

	public function activate() {
		$excluded = Controllers\Backend::instance()->get_excluded();
		if ( empty( $excluded ) ) {
			Controllers\Backend::instance()->save_excluded( array() );
		}
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

Plugin::instance();
