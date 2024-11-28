<?php

namespace QuadLayers\QLSE\Entity;

use QuadLayers\WP_Orm\Entity\SingleEntity;
use QuadLayers\QLSE\Services\Entity_Options;

class Settings extends SingleEntity {
	public $entries;
	public $taxonomies;
	public $author;


	public function __construct() {

		$entity_options = Entity_Options::instance();

		$args = $entity_options->get_args();

		$this->entries    = $args['entries'];
		$this->taxonomies = $args['taxonomies'];
		$this->author     = $args['author'];
	}
	public static $sanitizeProperties = array(); //phpcs:ignore
	public static $validateProperties = array(); //phpcs:ignore
}
