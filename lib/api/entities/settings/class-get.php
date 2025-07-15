<?php
namespace QuadLayers\QLSE\Api\Entities\Settings;

use QuadLayers\QLSE\Models\Settings as Models_Settings;
use QuadLayers\QLSE\Api\Entities\Settings\Base;

/**
 * API_Rest_Settings_Get Class
 */
class Get extends Base {

	protected static $route_path = 'settings';

	public function callback( \WP_REST_Request $request ) {

		try {
			$settings = Models_Settings::instance()->get()->getProperties();
			return $this->handle_response( $settings );
		} catch ( \Throwable  $error ) {
			return $this->handle_response(
				array(
					'code'    => $error->getCode(),
					'message' => $error->getMessage(),
				)
			);
		}
	}

	public static function get_rest_args() {
		return array();
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}


	public function get_rest_permission() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return false;
		}
		return true;
	}
}
