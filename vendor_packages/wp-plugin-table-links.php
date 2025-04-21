<?php

if ( class_exists( 'QuadLayers\\WP_Plugin_Table_Links\\Load' ) ) {
	add_action('init', function() {
		new \QuadLayers\WP_Plugin_Table_Links\Load(
			QLSE_PLUGIN_FILE,
			array(
				array(
					'text'   => esc_html__( 'Settings', 'search-exclude' ),
					'url'    => admin_url( 'options-general.php?page=search_exclude' ),
					'target' => '_self',
				),
				array(
					'text' => esc_html__( 'QuadLayers', 'search-exclude' ),
					'url'  => QLSE_PURCHASE_URL,
				),
				array(
					'place' => 'row_meta',
					'text'  => esc_html__( 'Support', 'search-exclude' ),
					'url'   => QLSE_SUPPORT_URL,
				),
			)
		);	
	});

}
