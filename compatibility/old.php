<?php

add_filter(
	'default_option_qlse_settings',
	function ( $value ) {

		if ( $value ) {
			return $value;
		}

		$old_value = get_option( 'sep_exclude' );
		error_log( 'old_values: ' . json_encode( $old_value, JSON_PRETTY_PRINT ) );
		$new_value = array(
			'excluded' => $old_value,
		);

		return $new_value;
	}
);
