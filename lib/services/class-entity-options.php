<?php

namespace QuadLayers\QLSE\Services;

class Entity_Options {

	protected static $instance;

	public $base = array(
		'all' => false,
		'ids' => array(),
	);

	/**
	 * Builds the default settings shape for a fresh install: every searchable
	 * post type and public taxonomy, plus the author entry, each starting
	 * unexcluded.
	 *
	 * @return array{entries: array, taxonomies: array, author: array} The default settings args.
	 */
	public function get_args() {
		$args = array(
			'entries'    => $this->get_display_entries(),
			'taxonomies' => $this->get_display_taxonomies(),
			'author'     => $this->base,
		);
		return $args;
	}

	/**
	 * Maps every searchable post type to the default (unexcluded) entry shape.
	 *
	 * @return array Post type name => default entry array.
	 */
	public function get_display_entries() {
		$post_types = $this->get_entries();
		$array      = array();
		foreach ( $post_types as $key => $entry ) {
			$array[ $key ] = $this->base;
		}
		return $array;
	}

	/**
	 * Maps every public, non-empty taxonomy to the default (unexcluded) entry shape.
	 *
	 * @return array Taxonomy name => default entry array.
	 */
	public function get_display_taxonomies() {
		$taxonomies = $this->get_taxonomies();
		$array      = array();
		foreach ( $taxonomies as $key => $taxonomy ) {
			$array[ $key ] = $this->base;
		}
		return $array;
	}

	/**
	 * Gets every post type that search exclusion can apply to: public post
	 * types, non-public types that are still searchable, and attachments.
	 *
	 * @return \WP_Post_Type[] Post type name => post type object.
	 */
	public function get_entries() {
		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		$searchable_non_public = get_post_types(
			array(
				'public'              => false,
				'exclude_from_search' => false,
			),
			'objects'
		);

		$post_types = array_merge( $post_types, $searchable_non_public );

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

	/**
	 * Gets every public taxonomy that currently has at least one term.
	 *
	 * @return \WP_Taxonomy[] Taxonomy name => taxonomy object.
	 */
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
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
