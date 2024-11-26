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

		$new_value = array(
			'entries'    => array(
				'post'    => array(
					'all' => false,
					'ids' => array(),
				),
				'page'    => array(
					'all' => false,
					'ids' => array(),
				),
				'product' => array(
					'all' => false,
					'ids' => array(),
				),
			),
			'taxonomies' => array(
				'category'    => array(
					'all' => false,
					'ids' => array(),
				),
				'tags'        => array(
					'all' => false,
					'ids' => array(),
				),
				'product_cat' => array(
					'all' => false,
					'ids' => array(),
				),
			),
			'author'     => array(
				'all' => false,
				'ids' => array(),
			),
		);

		if ( empty( $settings_entity ) ) {
			Models_Settings::instance()->save( $new_value );
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
