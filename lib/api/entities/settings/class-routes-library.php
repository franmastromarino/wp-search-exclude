<?php

namespace QuadLayers\QLSE\Api\Entities\Settings;

use QuadLayers\QLSE\Api\Entities\Settings\Get as Settings_Get;
use QuadLayers\QLSE\Api\Entities\Settings\Post as Settings_Post;


use QuadLayers\QLSE\Api\Route as Route_Interface;

class Routes_Library {
	protected $routes                = array();
	protected static $rest_namespace = 'quadlayers/search-exclude';
	protected static $instance;

	private function __construct() {
		add_action( 'init', array( $this, '_rest_init' ) );
	}

	public static function get_namespace() {
		return self::$rest_namespace;
	}

	public function get_routes( $route_path = null ) {
		if ( ! $route_path ) {
			return $this->routes;
		}

		if ( isset( $this->routes[ $route_path ] ) ) {
			return $this->routes[ $route_path ];
		}
	}

	/**
	 * Registers a route instance so it can be looked up later by {@see get_routes()}.
	 *
	 * @param Route_Interface $instance The route instance to register.
	 */
	public function register( Route_Interface $instance ) {
		$this->routes[ $instance::get_name() ] = $instance;
	}

	/**
	 * Instantiates the settings REST route handlers.
	 *
	 * Hooked to `init`, before `rest_api_init`, so each handler's constructor
	 * can register itself with this library and with WordPress's REST API.
	 */
	public function _rest_init() {
		new Settings_Get();
		new Settings_Post();
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
