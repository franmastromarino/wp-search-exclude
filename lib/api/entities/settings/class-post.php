<?php
namespace QuadLayers\QLSE\Api\Entities\Settings;

use QuadLayers\QLSE\Models\Settings as Models_Settings;
use QuadLayers\QLSE\Api\Entities\Settings\Base;

/**
 * API_Rest_Settings_Post Class
 */
class Post extends Base {

	protected static $route_path = 'settings';

	/**
	 * Saves the settings payload sent in the request body as the plugin's
	 * new settings (excluded entries, taxonomies, and author state).
	 *
	 * @param \WP_REST_Request $request The current REST request; its raw JSON body is the new settings.
	 * @return \WP_REST_Response The save result, or a handled error response.
	 */
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
		$capability = apply_filters( 'searchexclude_filter_permissions', 'edit_others_pages' );

		if ( ! current_user_can( $capability ) ) {
			return false;
		}

		return true;
	}
}
