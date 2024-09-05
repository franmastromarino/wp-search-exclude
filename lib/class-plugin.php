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

	protected function get_excluded() {
		if ( null === $this->excluded ) {
			$this->excluded = get_option( 'sep_exclude' );
			if ( ! is_array( $this->excluded ) ) {
				$this->excluded = array();
			}
		}

		return $this->excluded;
	}

	protected function save_excluded( $excluded ) {
		update_option( 'sep_exclude', $excluded );
		$this->excluded = $excluded;
	}

	public function activate() {
		$excluded = $this->get_excluded();

		if ( empty( $excluded ) ) {
			$this->save_excluded( array() );
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
