<?php
namespace QuadLayers\QLSE\Api\Entities\Settings;

use QuadLayers\QLSE\Models\Settings as Models_Settings;
use QuadLayers\QLSE\Api\Entities\Settings\Base;

/**
 * API_Rest_Settings_Post Class
 */
class Post extends Base {

	protected static $route_path = 'settings';

	public function callback( \WP_REST_Request $request ) {

		try {
			// throw new \Exception( esc_html__( 'Unknown error.', 'search-exclude' ), 500 );
			$body = json_decode( $request->get_body(), true );

			$status = Models_Settings::instance()->save( $body );
			return $this->handle_response( $status );

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
		return \WP_REST_Server::CREATABLE;
	}


	public function get_rest_permission() {
		// TODO: DESCOMENTAR
		// if ( ! current_user_can( 'manage_options' ) ) {
		// return false;
		// }
		return true;
	}
}
