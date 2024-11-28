<?php

add_filter(
	'default_option_qlse_settings',
	function ( $value ) {

		if ( $value ) {
			return $value;
		}

		$old_value_ids = isset( $value['excluded'] ) ? $value['excluded'] : get_option( 'sep_exclude' );

		if ( empty( $old_value_ids ) ) {
			return $value;
		}

		$new_value = array(
			'entries' => array(
				'post' => array(
					'all' => false,
					'ids' => array(),
				),
				'page' => array(
					'all' => false,
					'ids' => array(),
				),
			),
		);

		// Iterate over the old exclude IDs and categorize them by post type.
		foreach ( $old_value_ids as $id ) {
			$post_type = get_post_type( $id );
			if ( $post_type ) {
				$new_value['entries'][ $post_type ]['ids'][] = $id;
			}
		}

		return $new_value;
	}
);

add_filter(
	'option_qlse_settings',
	function ( $value ) {

		if ( isset( $value['entries'] ) ) {
			return $value;
		}

		$old_value_ids = isset( $value['excluded'] ) ? $value['excluded'] : get_option( 'sep_exclude' );

		if ( empty( $old_value_ids ) ) {
			return $value;
		}

		$new_value = array(
			'entries' => array(
				'post' => array(
					'all' => false,
					'ids' => array(),
				),
				'page' => array(
					'all' => false,
					'ids' => array(),
				),
			),
		);

		// Iterate over the old exclude IDs and categorize them by post type.
		foreach ( $old_value_ids as $id ) {
			$post_type = get_post_type( $id );
			if ( $post_type ) {
				$new_value['entries'][ $post_type ]['ids'][] = $id;
			}
		}

		return $new_value;
	}
);
