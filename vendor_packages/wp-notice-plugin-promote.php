<?php

if ( class_exists( 'QuadLayers\\WP_Notice_Plugin_Promote\\Load' ) ) {
	add_action('init', function() {
		/**
		 *  Promote constants
		 */
		define( 'QLSE_PROMOTE_LOGO_SRC', plugins_url( '/assets/backend/img/logo.png', QLSE_PLUGIN_FILE ) );
		/**
		 * Notice review
		 */
		define( 'QLSE_PROMOTE_REVIEW_URL', 'https://wordpress.org/support/plugin/search-exclude/reviews/?filter=5#new-post' );
		/**
		 * Notice cross sell 1
		 */
		define( 'QLSE_PROMOTE_CROSS_INSTALL_1_SLUG', 'wp-whatsapp-chat' );
		define( 'QLSE_PROMOTE_CROSS_INSTALL_1_NAME', 'Social Chat' );
		define(
			'QLSE_PROMOTE_CROSS_INSTALL_1_TITLE',
			wp_kses(
				sprintf(
					'<h3 style="margin:0">%s</h3>',
					esc_html__( 'Turn more visitors into customers.', 'search-exclude' )
				),
				array(
					'h3' => array(
						'style' => array()
					)
				)
			)
		);
		define( 'QLSE_PROMOTE_CROSS_INSTALL_1_DESCRIPTION', esc_html__( 'Social Chat allows your users to start a conversation from your website directly to your WhatsApp phone number with one click.', 'search-exclude' ) );
		define( 'QLSE_PROMOTE_CROSS_INSTALL_1_URL', 'https://quadlayers.com/products/whatsapp-chat/?utm_source=qlse_plugin&utm_medium=dashboard_notice&utm_campaign=cross_sell&utm_content=social_chat_link' );
		define( 'QLSE_PROMOTE_CROSS_INSTALL_1_LOGO_SRC', plugins_url( '/assets/backend/img/wp-whatsapp-chat.jpeg', QLSE_PLUGIN_FILE ) );
		/**
		 * Notice cross sell 2
		 */
		define( 'QLSE_PROMOTE_CROSS_INSTALL_2_SLUG', 'insta-gallery' );
		define( 'QLSE_PROMOTE_CROSS_INSTALL_2_NAME', 'Social Feed Gallery' );
		define(
			'QLSE_PROMOTE_CROSS_INSTALL_2_TITLE',
			wp_kses(
				sprintf(
					'<h3 style="margin:0">%s</h3>',
					esc_html__( 'Display Instagram feeds beautifully.', 'search-exclude' )
				),
				array(
					'h3' => array(
						'style' => array()
					)
				)
			)
		);
		define( 'QLSE_PROMOTE_CROSS_INSTALL_2_DESCRIPTION', esc_html__( 'Display Instagram photos from any account with responsive galleries, custom layouts, and an engaging lightbox popup.', 'search-exclude' ) );
		define( 'QLSE_PROMOTE_CROSS_INSTALL_2_URL', 'https://quadlayers.com/products/instagram-feed-gallery/?utm_source=qlse_plugin&utm_medium=dashboard_notice&utm_campaign=cross_sell&utm_content=instagram_feed_link' );
		define( 'QLSE_PROMOTE_CROSS_INSTALL_2_LOGO_SRC', plugins_url( '/assets/backend/img/insta-gallery.jpg', QLSE_PLUGIN_FILE ) );

		new \QuadLayers\WP_Notice_Plugin_Promote\Load(
			QLSE_PLUGIN_FILE,
			array(
				array(
					'type'               => 'ranking',
					'notice_delay'       => 0,
					'notice_logo'        => QLSE_PROMOTE_LOGO_SRC,
					'notice_title'       => wp_kses(
						sprintf(
							'<h3 style="margin:0">%s</h3>',
							esc_html__( 'Enjoying Search Exclude?', 'search-exclude' )
						),
						array(
							'h3' => array(
								'style' => array()
							)
						)
					),
					'notice_description' => esc_html__( 'A quick 5-star review helps us keep improving the plugin and supporting users like you. It only takes 2 seconds — thank you!', 'search-exclude' ),
					'notice_link'        => QLSE_PROMOTE_REVIEW_URL,
					'notice_more_link'   => 'https://quadlayers.com/account/support/?utm_source=qlse_plugin&utm_medium=dashboard_notice&utm_campaign=support&utm_content=report_bug_button',
					'notice_more_label'  => esc_html__(
						'Report a bug',
						'search-exclude'
					),
				),
				array(
					'plugin_slug'        => QLSE_PROMOTE_CROSS_INSTALL_1_SLUG,
					'notice_delay'       => MONTH_IN_SECONDS * 3,
					'notice_logo'        => QLSE_PROMOTE_CROSS_INSTALL_1_LOGO_SRC,
					'notice_title'       => QLSE_PROMOTE_CROSS_INSTALL_1_TITLE,
					'notice_description' => QLSE_PROMOTE_CROSS_INSTALL_1_DESCRIPTION,
					'notice_more_link'   => QLSE_PROMOTE_CROSS_INSTALL_1_URL
				),
				array(
					'plugin_slug'        => QLSE_PROMOTE_CROSS_INSTALL_2_SLUG,
					'notice_delay'       => MONTH_IN_SECONDS * 6,
					'notice_logo'        => QLSE_PROMOTE_CROSS_INSTALL_2_LOGO_SRC,
					'notice_title'       => QLSE_PROMOTE_CROSS_INSTALL_2_TITLE,
					'notice_description' => QLSE_PROMOTE_CROSS_INSTALL_2_DESCRIPTION,
					'notice_more_link'   => QLSE_PROMOTE_CROSS_INSTALL_2_URL
				),
			)
		);
	});
}
