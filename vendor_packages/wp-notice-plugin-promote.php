<?php

if ( class_exists( 'QuadLayers\\WP_Notice_Plugin_Promote\\Load' ) ) {
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
	define( 'QLSE_PROMOTE_CROSS_INSTALL_1_SLUG', 'ai-copilot' );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_1_NAME', 'AI Copilot' );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_1_DESCRIPTION', esc_html__( 'Boost your productivity in WordPress content creation with AI-driven tools, automated content generation, and enhanced editor utilities.', 'search-exclude' ) );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_1_URL', 'https://quadlayers.com/products/ai-copilot/?utm_source=qlse_admin' );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_1_LOGO_SRC', plugins_url( '/assets/backend/img/ai-copilot.png', QLSE_PLUGIN_FILE ) );
	/**
	 * Notice cross sell 2
	 */
	define( 'QLSE_PROMOTE_CROSS_INSTALL_2_SLUG', 'wp-whatsapp-chat' );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_2_NAME', 'Social Chat' );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_2_DESCRIPTION', esc_html__( 'Social Chat allows your users to start a conversation from your website directly to your WhatsApp phone number with one click.', 'search-exclude' ) );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_2_URL', 'https://quadlayers.com/product/whatsapp-chat/?utm_source=qlse_admin' );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_2_LOGO_SRC', plugins_url( '/assets/backend/img/wp-whatsapp-chat.jpeg', QLSE_PLUGIN_FILE ) );

	new \QuadLayers\WP_Notice_Plugin_Promote\Load(
		QLSE_PLUGIN_FILE,
		array(
			array(
				'type'               => 'ranking',
				'notice_delay'       => MONTH_IN_SECONDS,
				'notice_logo'        => QLSE_PROMOTE_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! Thank you for choosing the %s plugin!',
						'search-exclude'
					),
					QLSE_PLUGIN_NAME
				),
				'notice_description' => esc_html__( 'Could you please give it a 5-star rating on WordPress? Your feedback boosts our motivation, helps us promote, and continues to improve this product. Your support matters!', 'search-exclude' ),
				'notice_link'        => QLSE_PROMOTE_REVIEW_URL,
				'notice_link_label'  => esc_html__(
					'Yes, of course!',
					'search-exclude'
				),
				'notice_more_link'   => QLSE_SUPPORT_URL,
				'notice_more_label'  => esc_html__(
					'Report a bug',
					'search-exclude'
				),
			),
			array(
				'plugin_slug'        => QLSE_PROMOTE_CROSS_INSTALL_1_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 4,
				'notice_logo'        => QLSE_PROMOTE_CROSS_INSTALL_1_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! We want to invite you to try our %s plugin!',
						'search-exclude'
					),
					QLSE_PROMOTE_CROSS_INSTALL_1_NAME
				),
				'notice_description' => QLSE_PROMOTE_CROSS_INSTALL_1_DESCRIPTION,
				'notice_more_link'   => QLSE_PROMOTE_CROSS_INSTALL_1_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'search-exclude'
				),
			),
			array(
				'plugin_slug'        => QLSE_PROMOTE_CROSS_INSTALL_2_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 6,
				'notice_logo'        => QLSE_PROMOTE_CROSS_INSTALL_2_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! We want to invite you to try our %s plugin!',
						'search-exclude'
					),
					QLSE_PROMOTE_CROSS_INSTALL_2_NAME
				),
				'notice_description' => QLSE_PROMOTE_CROSS_INSTALL_2_DESCRIPTION,
				'notice_more_link'   => QLSE_PROMOTE_CROSS_INSTALL_2_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'search-exclude'
				),
			),
		)
	);
}
