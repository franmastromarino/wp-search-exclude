<?php

namespace QuadLayers\QLSE\Controllers;

use QuadLayers\QLSE\Models\Settings as Models_Settings;
use QuadLayers\QLSE\Helpers;
use QuadLayers\QLSE\Api\Entities\Settings\Get as API_Settings_Get;


/**
 * Backend Class
 */
class Backend {

	protected static $instance;

	private function __construct() {
		/**
		 * Admin scripts
		 */

		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
		/**
		* Admin menu
		*/
		add_action( 'admin_notices', array( $this, 'bulk_action_notices' ) );
		add_action( 'admin_init', array( $this, 'save_options' ) );
		/**
		 * Edit post metabox
		 */
		add_action( 'post_updated', array( $this, 'post_save' ) );
		add_action( 'edit_attachment', array( $this, 'post_save' ) );
		/**
		 * Add column to posts/pages list
		 */
		add_filter( 'manage_posts_columns', array( $this, 'add_column' ) );
		add_filter( 'manage_pages_columns', array( $this, 'add_column' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'add_column_value' ), 10, 2 );
		add_action( 'manage_pages_custom_column', array( $this, 'add_column_value' ), 10, 2 );
		add_action( 'quick_edit_custom_box', array( $this, 'add_quick_edit_custom_box' ) );
		/**
		 * Add classic editor metabox
		 */
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		/**
		 * Add bulk edit actions
		 */
		foreach ( get_post_types() as $post_type ) {
			/**
			 * Add dropdown
			 */
			add_filter( "bulk_actions-edit-$post_type", array( $this, 'bulk_edit' ) );
			add_filter( "handle_bulk_actions-edit-$post_type", array( $this, 'bulk_action_handler' ), 10, 3 );
		}
		/**
		 * Display messages
		 */
		add_filter(
			'removable_query_args',
			function ( $args ) {
				$args[] = 'se_saved';
				return $args;
			}
		);

		/**
		 * Hook can be used outside the plugin.
		 *
		 * You can pass any post/page/custom_post ids in the array with first parameter.
		 * The second parameter specifies states of visibility in search to be set.
		 * Pass true if you want to hide posts/pages, or false - if you want show them in the search results.
		 *
		 * Example:
		 * Let's say you want "Exclude from Search Results" checkbox to be checked off by default
		 * for newly created posts, but not pages. In this case you can add following code
		 * to your theme's function.php:
		 *
		 * <code>
		 * add_filter('default_content', 'exclude_new_post_by_default', 10, 2);
		 * function exclude_new_post_by_default($content, $post)
		 * {
		 *      if ('post' === $post->post_type) {
		 *          do_action('searchexclude_hide_from_search', array($post->ID), true);
		 *      }
		 * }
		 * </code>
		 *
		 * @param array $post_ids array of post IDs
		 * @param bool $hide
		 */
		add_action( 'searchexclude_hide_from_search', array( $this, 'save_post_ids_to_search_exclude' ), 10, 2 );
	}



	public function bulk_action_notices() {

		if ( empty( $_GET['se_saved'] ) ) {
			return;
		}

		$count   = (int) $_GET['se_saved'];
		$message = sprintf(
			_n(
				'%d item updated.',
				'%d items updated.',
				$count,
				'search-exclude'
			),
			$count
		);

		?>
			<div class="notice notice-success is-dismissible">
				<p><?php echo esc_html( $message ); ?></p>
			</div>
		<?php
	}

	public function bulk_action_handler( $redirect, $doaction, $object_ids ) {
		/**
		 * Let's remove query args first
		 */
		$redirect = remove_query_arg(
			array( 'se_saved' ),
			$redirect
		);

		if ( 'se_show' !== $doaction && 'se_hide' !== $doaction ) {
			return $redirect;
		}

		/**
		 * Do something for "Make Draft" bulk action
		 */
		$exclude = ( 'se_hide' === $doaction );

		$this->save_post_ids_to_search_exclude( $object_ids, $exclude );

		$redirect = add_query_arg(
			'se_saved', /* just a parameter for URL */
			count( $object_ids ), /* how many posts have been selected */
			$redirect
		);

		return $redirect;
	}

	public function bulk_edit( $bulk_array ) {
		$bulk_array['se_hide'] = esc_html__( 'Hide from Search', 'search-exclude' );
		$bulk_array['se_show'] = esc_html__( 'Show in Search', 'search-exclude' );

		return $bulk_array;
	}

	/**
	 *
	 * Save post meta
	 *
	 * @param $post_id int the ID of the post
	 * @param $exclude bool indicates whether post should be excluded from the search results or not
	 */
	protected function save_post_id_to_search_exclude( $post_id, $exclude ) {
		$this->save_post_ids_to_search_exclude( array( intval( $post_id ) ), $exclude );
	}

	public function save_post_ids_to_search_exclude( $post_ids, $exclude ) {
		$post_type = $_REQUEST['post_type'];

		$exclude = (bool) $exclude;
		$entries = Models_Settings::instance()->get()->get( 'entries' );

		$excluded = isset( $entries[ $post_type ]['ids'] ) && is_array( $entries[ $post_type ]['ids'] )
		? $entries[ $post_type ]['ids']
		: array();

		if ( $exclude ) {
			$entries[ $post_type ]['ids'] = array_values( array_unique( array_merge( $excluded, $post_ids ) ) );
		} else {
			$entries[ $post_type ]['ids'] = array_values( array_diff( $excluded, $post_ids ) );
		}

		Models_Settings::instance()->save( array( 'entries' => $entries ) );
	}

	protected function is_excluded( $post_id ) {
		$post_type = get_post_type( $post_id );

		$entries = Models_Settings::instance()->get()->get( 'entries' );

		$excluded = isset( $entries[ $post_type ]['ids'] ) && is_array( $entries[ $post_type ]['ids'] )
			? $entries[ $post_type ]['ids']
			: array();

		return false !== array_search( $post_id, $excluded );
	}

	protected function view( $view, $params = array() ) {
		extract( $params );
		include QLSE_PLUGIN_DIR . '/lib/views/' . $view . '.php';
	}

	public function register_scripts() {
		global $wp_version;

		$backend = include QLSE_PLUGIN_DIR . 'build/backend/js/index.asset.php';
		$store   = include QLSE_PLUGIN_DIR . 'build/store/js/index.asset.php';

		wp_register_script(
			'qlse-backend',
			plugins_url( '/build/backend/js/index.js', QLSE_PLUGIN_FILE ),
			array_merge(
				$backend['dependencies'],
				array( 'inline-edit-post' )
			),
			$backend['dependencies'],
			$backend['version'],
			true
		);

		wp_register_style(
			'qlse-backend',
			plugins_url( '/build/backend/css/style.css', QLSE_PLUGIN_FILE ),
			array(),
			QLSE_PLUGIN_VERSION
		);

		wp_register_script(
			'qlse-store',
			plugins_url( '/build/store/js/index.js', QLSE_PLUGIN_FILE ),
			$store['dependencies'],
			$store['version'],
			true
		);

		wp_localize_script(
			'qlse-store',
			'qlseStore',
			array(
				'WP_VERSION'      => $wp_version,
				'QLSE_REST_ROUTE' => API_Settings_Get::get_rest_path(),

			)
		);
	}

	public function enqueue_scripts() {
		$current_screen  = get_current_screen()->id;
		$allowed_screens = array( 'edit-page', 'edit-post', 'settings_page_search_exclude' );

		if (
			! in_array( $current_screen, $allowed_screens ) ) {
		return;
		}

		/**
		 * Load admin scripts
		 */
		wp_enqueue_media();
		wp_enqueue_script( 'qlse-backend' );
	}

	public function enqueue_style() {
		$current_screen  = get_current_screen()->id;
		$allowed_screens = array( 'edit-page', 'edit-post', 'edit-product' );

		if (
			! in_array( $current_screen, $allowed_screens ) ) {
		return;
		}

		wp_enqueue_style( 'qlse-backend' );
	}

	public function add_quick_edit_custom_box( $column_name ) {
		if ( 'search_exclude' == $column_name ) {
			$this->view( 'quick-edit' );
		}
	}

	public function add_column( $columns ) {
		$columns['search_exclude'] = esc_html__( 'Search Excluded', 'search-exclude' );
		return $columns;
	}

	public function add_column_value( $column_name, $post_id ) {
		if ( 'search_exclude' == $column_name ) {
			$this->view(
				'column-cell',
				array(
					'exclude' => $this->is_excluded( $post_id ),
					'post_id' => $post_id,
				)
			);
		}
	}

	public function post_save( $post_id ) {
		if ( ! isset( $_POST['sep'] ) ) {
			return $post_id;
		}

		$sep     = $_POST['sep'];
		$exclude = ( isset( $sep['exclude'] ) ) ? filter_var( $sep['exclude'], FILTER_VALIDATE_BOOLEAN ) : false;

		$this->save_post_id_to_search_exclude( $post_id, $exclude );

		return $post_id;
	}

	public function options() {
		$excluded = Models_Settings::instance()->get()->get( 'excluded' );
		$query    = new \WP_Query(
			array(
				'post_type'   => 'any',
				'post_status' => 'any',
				'post__in'    => $excluded,
				'order'       => 'ASC',
				'nopaging'    => true,
			)
		);
		$this->view(
			'options',
			array(
				'excluded' => $excluded,
				'query'    => $query,
			)
		);
	}

	public function save_options() {
		if ( ! isset( $_POST['search_exclude_submit'] ) ) {
			return;
		}

		$sep_exclude = isset( $_POST['sep_exclude'] ) ? $_POST['sep_exclude'] : array();

		check_admin_referer( 'search_exclude_submit' );

		$this->check_permissions();

		$excluded = Helpers::filter_posts_ids( $sep_exclude );

		Models_Settings::instance()->save( array( 'excluded' => $excluded ) );
	}

	private function check_permissions() {
		$capability = apply_filters( 'searchexclude_filter_permissions', 'edit_others_pages' );

		if ( ! current_user_can( $capability ) ) {
			wp_die(
				esc_html__( 'Not enough permissions', 'search-exclude' ),
				'',
				array(
					'response' => 401,
					'exit'     => true,
				)
			);
		}
	}

	public function add_meta_box() {
		$current_screen = get_current_screen();
		// Do not show meta box on service pages.
		if ( empty( $current_screen->post_type ) ) {
			return;
		}
		// Check if this is the Gutenberg editor.
		if ( function_exists( 'use_block_editor_for_post_type' ) && use_block_editor_for_post_type( $current_screen->post_type ) ) {
			// This is the Gutenberg editor, don't add the meta box.
			return;
		}
		add_meta_box(
			'sep_metabox_id',
			'Search Exclude',
			function ( $post ) {
				wp_nonce_field( 'sep_metabox_nonce', 'metabox_nonce' );
				$this->view( 'metabox', array( 'exclude' => $this->is_excluded( $post->ID ) ) );
			},
			null,
			'side'
		);
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
