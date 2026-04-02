<?php

namespace QuadLayers\QLSE\Controllers;

class Gutenberg {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function register_scripts() {
		$content = include QLSE_PLUGIN_DIR . 'build/gutenberg/js/index.asset.php';
		/**
		 * Register gutenberg assets
		 */
		wp_register_script(
			'qlse-gutenberg',
			plugins_url( '/build/gutenberg/js/index.js', QLSE_PLUGIN_FILE ),
			$content['dependencies'],
			$content['version'],
			true
		);

		wp_register_style(
			'qlse-gutenberg',
			plugins_url( '/build/gutenberg/css/style.css', QLSE_PLUGIN_FILE ),
			array(
				'wp-components',
			),
			QLSE_PLUGIN_VERSION
		);
	}

	public function enqueue_scripts() {
		$screen = get_current_screen();

		if ( ! $screen || 'post' !== $screen->base ) {
			return;
		}

		if ( ! function_exists( 'use_block_editor_for_post_type' ) || ! use_block_editor_for_post_type( $screen->post_type ) ) {
			return;
		}

		wp_enqueue_script( 'qlse-gutenberg' );
		wp_enqueue_style( 'qlse-gutenberg' );
	}


	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
