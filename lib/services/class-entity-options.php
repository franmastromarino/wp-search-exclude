<?php

namespace QuadLayers\QLSE\Services;

class Entity_Options {

	protected static $instance;

	public $base = array(
		'all' => false,
		'ids' => array(),
	);

	public function get_args() {
		$args = array(
			'entries'    => $this->get_display_entries(),
			'taxonomies' => $this->get_display_taxonomies(),
			'author'     => $this->base,
		);
		return $args;
	}

	public function get_display_entries() {
		$post_types = $this->get_entries();
		$array      = array();
		foreach ( $post_types as $key => $entry ) {
			$array[ $key ] = $this->base;
		}
		return $array;
	}

	public function get_display_taxonomies() {
		$taxonomies = $this->get_taxonomies();
		$array      = array();
		foreach ( $taxonomies as $key => $taxonomy ) {
			$array[ $key ] = $this->base;
		}
		return $array;
	}

	public function get_entries() {
		$post_types = get_post_types(
			array(
				'public'            => true,
				'show_in_nav_menus' => true,
			),
			'objects'
		);

		if ( ! isset( $post_types['attachment'] ) ) {
			$attachment = get_post_types(
				array(
					'name' => 'attachment',
				),
				'objects'
			);
			if ( ! empty( $attachment ) ) {
				$post_types['attachment'] = $attachment['attachment'];
			}
		}

		$result = array();

		foreach ( $post_types as $type ) {
				$result[ $type->name ] = $type;
		}

		return $result;
	}

	public function get_taxonomies() {
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
		$array      = array();

		// Only check if taxonomy has terms without loading all terms
		foreach ( $taxonomies as $taxonomy ) {
			$term_count = get_terms(
				array(
					'taxonomy'   => $taxonomy->name,
					'hide_empty' => false,
					'fields'     => 'count',
				)
			);

			if ( $term_count > 0 ) {
				$array[ $taxonomy->name ] = $taxonomy;
			}
		}

		return $array;
	}

	public static function instance() {

			if ( ! is_wp_error( $has_terms ) && ! empty( $has_terms ) ) {
				$array[ $taxonomy->name ] = $taxonomy;
			}
		}

		return $array;
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
