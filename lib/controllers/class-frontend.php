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
		$rules             = $settings_entity;  // Reglas de inclusión/exclusión
		$entity_visibility = Entity_Visibility::instance();
		$current_post      = get_post_type();

		$exclude =
			( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
			&& $query->is_search
			&& ! $this->is_bbpress( $query );

		// $exclude = apply_filters( 'searchexclude_filter_search', $exclude, $query );
		// $post_type = $query->get( 'post_type' );
		// Only proceed if rules exist and the current context matches frontend search

		error_log( 'query: ' . json_encode( $query, JSON_PRETTY_PRINT ) );
		if ( $exclude && isset( $rules ) ) {

			$post__in     = $query->get( 'post__in', array() );
			$post__not_in = $query->get( 'post__not_in', array() );

			// Handle post type inclusion/exclusion rules
			if ( isset( $rules->entries ) ) {
				foreach ( $rules->entries as $post_type => $rule ) {
					$include = isset( $rule->include ) ? (int) $rule->include : 0;
					$ids     = isset( $rule->ids ) ? $rule->ids : array();
					if ( $post_type ) {
						if ( in_array( 'all', $ids, true ) ) {
							// If "all" is present in ids, apply global rule for post type

							if ( $include ) {
								// Include all posts of this post type
								$query->set( 'post_type', $post_type );
							} else {
								// Exclude all posts of this post type
								$post__not_in = $this->exclude_all_posts_of_type( $post_type );
								$query->set( 'post__not_in', $post__not_in );
							}
						} else {
							// Specific IDs inclusion/exclusion
							if ( $include ) {
								$post__in = array_merge( $post__in, $ids );
							} else {
								$post__not_in = array_merge( $post__not_in, $ids );
							}
						}
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
		error_log( 'test' );
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
