<?php

add_filter(
	'default_option_qlse_settings',
	function ( $value ) {
		if ( $value ) {
			return $value;
		}

		$old_value_excluded = get_option( 'sep_exclude' );
		$excluded           = isset( get_option( 'qlse_settings' )['excluded'] ) ? get_option( 'qlse_settings' )['excluded'] : array();

		$ids = [ $old_value_excluded, $excluded ];

		// Initialize the new entries array.
		$new_value = array(
			'entries' => array(
				'post'    => array(
					'all' => false,
					'ids' => array(),
				),
				'page'    => array(
					'all' => false,
					'ids' => array(),
				),
				'product' => array(
					'all' => false,
					'ids' => array(),
				),
			),
		);

		// Iterate over the old exclude IDs and categorize them by post type.
		foreach ( $ids as $id ) {
			$post_type = get_post_type( $id );
			if ( $post_type && isset( $new_value['entries'][ $post_type ] ) ) {
				$new_value['entries'][ $post_type ]['ids'][] = $id;
			}
		}

		return $new_value;
	}
);
