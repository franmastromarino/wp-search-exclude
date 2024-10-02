<?php

namespace QuadLayers\QLSE;

use QuadLayers\QLSE\Api\Entities\Settings\Routes_Library;
use QuadLayers\QLSE\Models\Settings as Models_Settings;

final class Plugin {

	private static $instance;

	private function __construct() {
		/**
		* Load plugin textdomain.
		*/
		load_plugin_textdomain( 'search-exclude', false, QLSE_PLUGIN_DIR . '/languages/' );
		/**
		 * On activation
		 */
		add_action( 'qlse_activation', array( $this, 'activate' ) );

		Routes_Library::instance();
		Controllers\Backend::instance();
		Controllers\Frontend::instance();
		Controllers\Gutenberg::instance();
		Controllers\Settings::instance();
	}

	public function activate() {
		$settings_entity = Models_Settings::instance()->get();
		$excluded        = $settings_entity->get( 'excluded' );

		if ( empty( $excluded ) ) {
			Models_Settings::instance()->save( array() );
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
