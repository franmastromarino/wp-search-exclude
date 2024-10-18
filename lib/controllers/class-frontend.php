<?php

namespace QuadLayers\QLSE\Controllers;

use QuadLayers\QLSE\Models\Settings as Models_Settings;

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
		$settings_entity = Models_Settings::instance()->get();
		$settings        = $settings_entity;

		if ( is_admin() || wp_doing_ajax() || defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return;
		}

		$exclude =
		( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
		&& $query->is_search
		&& ! $this->is_bbpress( $query );

		$exclude = apply_filters( 'searchexclude_filter_search', $exclude, $query );

		if ( $exclude && isset( $settings ) ) {
			$post__in     = $query->get( 'post__in', array() );
			$post__not_in = $query->get( 'post__not_in', array() );

			if ( isset( $settings->entries ) ) {
				foreach ( $settings->entries as $post_type => $setting ) {
					$include = isset( $setting['include'] ) ? (int) $setting['include'] : 0;
					$ids     = isset( $setting['ids'] ) ? $setting['ids'] : array();

					if ( in_array( 'all', $ids, true ) ) {
						if ( $include ) {
							$query->set( 'post_type', $post_type );
						} else {
							// Exclude all posts of this post type
							$post__not_in = $this->exclude_all_posts_of_type( $post_type );
							$query->set( 'post__not_in', $post__not_in );
						}
					} elseif ( $include ) {
							$post__in = array_merge( $post__in, $ids );
					} else {
						$post__not_in = array_merge( $post__not_in, $ids );
					}
				}
			}

			$query->set( 'post__in', $post__in );
			$query->set( 'post__not_in', $post__not_in );
		}

		return $query;
	}

/**
 * Helper function to get all post IDs for a given post type.
 */
	private function exclude_all_posts_of_type( $post_type ) {
		$all_posts = get_posts(
			array(
				'post_type'      => $post_type,
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		return $all_posts;
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
