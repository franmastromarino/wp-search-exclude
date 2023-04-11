<?php

namespace QuadLayers\SearchExclude;

final class Plugin {

	protected static $instance;
	protected $excluded;

	private function __construct() {
		/**
		* Load plugin textdomain.
		*/
		load_plugin_textdomain( 'plugin-init', false, QLSE_PLUGIN_DIR . '/languages/' );
		/**
		 * On activation
		 */
		add_action( 'qlse_activation', array( $this, 'activate' ) );
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
		 * Search filter
		 */
		add_filter( 'pre_get_posts', array( $this, 'search_filter' ) );
		add_filter( 'bbp_has_replies_query', array( $this, 'bbpress_flag_replies' ) );
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
			function( $args ) {
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

		$bulk_array['se_hide'] = 'Hide from Search';
		$bulk_array['se_show'] = 'Show in Search';

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
		$exclude  = (bool) $exclude;
		$excluded = $this->get_excluded();

		if ( $exclude ) {
			$excluded = array_unique( array_merge( $excluded, $post_ids ) );
		} else {
			$excluded = array_diff( $excluded, $post_ids );
		}
		$this->save_excluded( $excluded );
	}

	/**
	 * Save excluded posts
	 *
	 * @param $excluded array IDs of posts to be saved for excluding from the search results
	 */
	protected function save_excluded( $excluded ) {
		update_option( 'sep_exclude', $excluded );
		$this->excluded = $excluded;
	}

	protected function get_excluded() {
		if ( null === $this->excluded ) {
			$this->excluded = get_option( 'sep_exclude' );
			if ( ! is_array( $this->excluded ) ) {
				$this->excluded = array();
			}
		}

		return $this->excluded;
	}

	protected function is_excluded( $post_id ) {
		return false !== array_search( $post_id, $this->get_excluded() );
	}

	protected function view( $view, $params = array() ) {
		extract( $params );
		include dirname( __FILE__ ) . '/views/' . $view . '.php';
	}

	private function filter_posts_ids( $post_ids ) {
		return array_filter( filter_var( $post_ids, FILTER_VALIDATE_INT, FILTER_FORCE_ARRAY ) );
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
		$columns['search_exclude'] = 'Search Exclude';
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

	public function activate() {
		$excluded = $this->get_excluded();

		if ( empty( $excluded ) ) {
			$this->save_excluded( array() );
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

	public function search_filter( $query ) {
		$exclude =
			( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
			&& $query->is_search
			&& ! $this->is_bbpress( $query );

		$exclude = apply_filters( 'searchexclude_filter_search', $exclude, $query );

		if ( $exclude ) {
			$query->set( 'post__not_in', array_merge( array(), $this->get_excluded() ) );
		}

		return $query;
	}

	public function is_bbpress( $query ) {
		return $query->get( '___s2_is_bbp_has_replies' );
	}

	/**
	 * Flags a WP Query has being a `bbp_has_replies()` query.
	 *
	 * @attaches-to ``add_filter('bbp_has_replies_query');``
	 *
	 * @param array $args Query arguments passed by the filter.
	 *
	 * @return array The array of ``$args``.
	 *
	 * @see Workaround for bbPress and the `s` key. See: <http://bit.ly/1obLpv4>
	 */
	public function bbpress_flag_replies( $args ) {
		return array_merge( $args, array( '___s2_is_bbp_has_replies' => true ) );
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
		$excluded = $this->get_excluded();
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

		$excluded = $this->filter_posts_ids( $sep_exclude );
		$this->save_excluded( $excluded );
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
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

Plugin::instance();
