<?php

if ( class_exists( 'QuadLayers\\WP_Notice_Plugin_Promote\\Load' ) ) {
	/**
	 *  Promote constants
	 */
	define( 'QLSE_PROMOTE_LOGO_SRC', plugins_url( '/assets/backend/img/logo.jpg', QLSE_PLUGIN_FILE ) );
	/**
	 * Notice review
	 */
	define( 'QLSE_PROMOTE_REVIEW_URL', 'https://wordpress.org/support/plugin/search-exclude/reviews/?filter=5#new-post' );
	/**
	 * Notice premium sell
	 */
	define( 'QLSE_PROMOTE_PREMIUM_SELL_SLUG', 'search-exclude-pro' );
	define( 'QLSE_PROMOTE_PREMIUM_SELL_NAME', 'Perfect WooCommerce Brands PRO' );
	define( 'QLSE_PROMOTE_PREMIUM_INSTALL_URL', 'https://quadlayers.com/product/search-exclude/?utm_source=qlse_admin' );
	define( 'QLSE_PROMOTE_PREMIUM_SELL_URL', QLSE_PREMIUM_SELL_URL );
	/**
	 * Notice cross sell 1
	 */
	define( 'QLSE_PROMOTE_CROSS_INSTALL_1_SLUG', 'woocommerce-checkout-manager' );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_1_NAME', 'WooCommerce Checkout Manager' );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_1_DESCRIPTION', esc_html__( 'This plugin allows you to add custom fields to the checkout page, related to billing, shipping or additional fields sections.', 'search-exclude' ) );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_1_URL', 'https://quadlayers.com/products/woocommerce-checkout-manager/?utm_source=qlse_admin' );
	/**
	 * Notice cross sell 2
	 */
	define( 'QLSE_PROMOTE_CROSS_INSTALL_2_SLUG', 'woocommerce-direct-checkout' );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_2_NAME', 'WooCommerce Direct Checkout' );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_2_DESCRIPTION', esc_html__( 'It allows you to reduce the steps in the checkout process by skipping the shopping cart page. This can encourage buyers to shop more quickly and potentially increase your sales by reducing cart abandonment.', 'search-exclude' ) );
	define( 'QLSE_PROMOTE_CROSS_INSTALL_2_URL', 'https://quadlayers.com/products/woocommerce-direct-checkout/?utm_source=qlse_admin' );

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
				'plugin_slug'        => QLSE_PROMOTE_PREMIUM_SELL_SLUG,
				'plugin_install_link'   => QLSE_PROMOTE_PREMIUM_INSTALL_URL,
				'plugin_install_label'  => esc_html__(
					'Purchase Now',
					'search-exclude'
				),
				'notice_delay'       => MONTH_IN_SECONDS,
				'notice_logo'        => QLSE_PROMOTE_LOGO_SRC,
				'notice_title'       => esc_html__(
					'Hello! We have a special gift!',
					'search-exclude'
				),
				'notice_description' => sprintf(
					esc_html__(
						'Today we have a special gift for you. Use the coupon code %1$s within the next 48 hours to receive a 20% discount on the premium version of the %2$s plugin.',
						'search-exclude'
					),
					'ADMINPANEL20%',
					QLSE_PROMOTE_PREMIUM_SELL_NAME
				),
				'notice_more_link'   => QLSE_PROMOTE_PREMIUM_SELL_URL,
				'notice_more_label'  => esc_html__(
					'More info!',
					'search-exclude'
				),
			),
			array(
				'plugin_slug'        => QLSE_PROMOTE_CROSS_INSTALL_1_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 4,
				'notice_logo'        => QLSE_PROMOTE_LOGO_SRC,
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
				'notice_logo'        => QLSE_PROMOTE_LOGO_SRC,
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
