<?php

namespace QuadLayers\QLSE\Controllers;

use QuadLayers\QLSE\Models\Settings as Models_Settings;
use QuadLayers\QLSE\Services\Entity_Visibility;

/**
 * Frontend Class
 */
class Frontend {

	protected static $instance;

	private function __construct() {
	/**
	 * Search filter
	 */
	add_filter( 'pre_get_posts', array( $this, 'search_filter' ) );
	add_filter( 'bbp_has_replies_query', array( $this, 'bbpress_flag_replies' ) );
	}

	public function search_filter( $query ) {
		$settings_entity   = Models_Settings::instance()->get();
		$excluded          = $settings_entity->get( 'excluded' );
		$entity_visibility = Entity_Visibility::instance();
		$current_post      = get_post();

		error_log( 'current_post: ' . json_encode( $current_post, JSON_PRETTY_PRINT ) );

		$exclude =
			( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
			&& $query->is_search
			&& ! $this->is_bbpress( $query );

		$exclude = apply_filters( 'searchexclude_filter_search', $exclude, $query );

		if ( ! is_admin() && $entity_visibility->is_show_view( $settings_entity, $current_post ) ) {
		do_action( 'qlwapp_load' );
		}

		if ( $exclude ) {
			$query->set( 'post__not_in', array_merge( array(), $excluded ) );
		}

		return $query;
	}

	public function is_bbpress( $query ) {
		return $query->get( '___s2_is_bbp_has_replies' );
	}

	/**
	 * Flags a WP Query has being a `bbp_has_replies()` query.
	 *
	 * @attaches-to ``add_filter('bbp_has_replies_query');``
	 *
	 * @param array $args Query arguments passed by the filter.
	 *
	 * @return array The array of ``$args``.
	 *
	 * @see Workaround for bbPress and the `s` key. See: <http://bit.ly/1obLpv4>
	 */
	public function bbpress_flag_replies( $args ) {
		return array_merge( $args, array( '___s2_is_bbp_has_replies' => true ) );
	}


	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
