<?php

namespace QuadLayers\QLSE\Api;

interface Route {
	public function callback( \WP_REST_Request $request );

	public static function get_name();

	public static function get_rest_args();

	public static function get_rest_path();

	public static function get_rest_method();

	public function get_rest_permission();
}
