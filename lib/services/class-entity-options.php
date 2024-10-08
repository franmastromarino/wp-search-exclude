<?php

namespace QuadLayers\QLSE\Services;

class Entity_Options {

	protected static $instance;

	public $base = array(
		'include' => 1,
		'ids'     => array(),
	);

	public function get_args() {
		$args = array(
			'entries'    => $this->get_display_entries(),
			'taxonomies' => $this->get_display_taxonomies(),
			'target'     => $this->base,
			'devices'    => 'all',
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
		$array      = array();
		foreach ( $post_types as $type ) {
			if ( $count = wp_count_posts( $type->name ) ) {
				$array[ $type->name ] = $type;
			}
		}
		return $array;
	}

	public function get_taxonomies() {
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
		$array      = array();
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy->name,
					'hide_empty' => false,
				)
			);

			if ( count( $terms ) ) {
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
