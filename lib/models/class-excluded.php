<?php

namespace QuadLayers\QLSE\Models;

/**
 * Backend Class
 */
class Excluded {

	protected static $instance;
	protected $excluded;

	/**
	 * Save excluded posts
	 *
	 * @param $excluded array IDs of posts to be saved for excluding from the search results
	 */
	public function save( $excluded ) {
		update_option( 'sep_exclude', $excluded );
		$this->excluded = $excluded;
	}

	public function get() {
		if ( null === $this->excluded ) {
			$this->excluded = get_option( 'sep_exclude' );
			if ( ! is_array( $this->excluded ) ) {
				$this->excluded = array();
			}
		}

		return $this->excluded;
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
