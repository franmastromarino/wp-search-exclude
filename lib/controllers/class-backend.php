<?php

namespace QuadLayers\QLSE\Controllers;

use QuadLayers\QLSE\Models\Settings as Models_Settings;
use QuadLayers\QLSE\Helpers;

/**
 * Backend Class
 */
class Backend {

	protected static $instance;

	private function __construct() {
		/**
		 * Admin scripts
		 */
		add_action( 'admin_print_scripts-edit.php', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
		/**
		* Admin menu
		*/
		add_action( 'admin_init', array( $this, 'save_options' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		/**
		 * Edit post metabox
		 */
		add_action( 'post_updated', array( $this, 'post_save' ) );
		add_action( 'edit_attachment', array( $this, 'post_save' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		/**
		 * Add column to posts/pages list
		 */
		add_filter( 'manage_posts_columns', array( $this, 'add_column' ) );
		add_filter( 'manage_pages_columns', array( $this, 'add_column' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'add_column_value' ), 10, 2 );
		add_action( 'manage_pages_custom_column', array( $this, 'add_column_value' ), 10, 2 );
		add_action( 'quick_edit_custom_box', array( $this, 'add_quick_edit_custom_box' ) );
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
		add_action( 'admin_notices', array( $this, 'bulk_action_notices' ) );
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
				$count
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
		$exclude         = (bool) $exclude;
		$settings_entity = Models_Settings::instance()->get();
		$excluded        = $settings_entity->get( 'excluded' );

		if ( $exclude ) {
			$settings_entity->set( 'excluded', array_unique( array_merge( $excluded, $post_ids ) ) );
		} else {
			$settings_entity->set( 'excluded', array_diff( $excluded, $post_ids ) );

		}

		Models_Settings::instance()->save( $settings_entity->getProperties() );
	}

	protected function is_excluded( $post_id ) {
		$excluded = Models_Settings::instance()->get()->get( 'excluded' );

		return false !== array_search( $post_id, $excluded );
	}

	protected function view( $view, $params = array() ) {
		extract( $params );
		include QLSE_PLUGIN_DIR . '/lib/views/' . $view . '.php';
	}

	public function enqueue_scripts() {

		$backend = include QLSE_PLUGIN_DIR . 'build/backend/js/index.asset.php';

		wp_enqueue_script(
			'search-exclude-backend',
			plugins_url( '/build/backend/js/index.js', QLSE_PLUGIN_FILE ),
			array_merge(
				$backend['dependencies'],
				array( 'inline-edit-post' )
			),
			$backend['version'],
			true
		);
	}

	public function enqueue_style() {
		wp_enqueue_style(
			'search-exclude-backend',
			plugins_url( '/build/backend/css/style.css', QLSE_PLUGIN_FILE ),
			array(),
			QLSE_PLUGIN_VERSION
		);
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

	public function add_meta_box() {
		$current_screen = get_current_screen();
		/* Do not show meta box on service pages */
		if ( empty( $current_screen->post_type ) ) {
			return;
		}
		add_meta_box( 'sep_metabox_id', 'Search Exclude', array( $this, 'metabox' ), null, 'side' );
	}

	public function metabox( $post ) {
		wp_nonce_field( 'sep_metabox_nonce', 'metabox_nonce' );
		$this->view( 'metabox', array( 'exclude' => $this->is_excluded( $post->ID ) ) );
	}

	public function admin_menu() {
		add_options_page(
			'Search Exclude',
			'Search Exclude',
			'manage_options',
			'search_exclude',
			array( $this, 'options' )
		);
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

		$query = new \WP_Query(
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
				'Not enough permissions',
				'',
				array(
					'response' => 401,
					'exit'     => true,
				)
			);
		}
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
