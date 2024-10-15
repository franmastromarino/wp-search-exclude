<?php

namespace QuadLayers\QLSE\Entity;

use QuadLayers\WP_Orm\Entity\SingleEntity;
use QuadLayers\QLSE\Services\Entity_Options;

class Settings extends SingleEntity {
	public $excluded = array();
	public $entries;
	public $taxonomies;
	public $target;


	public function __construct() {

		$entity_options = Entity_Options::instance();

		$args = $entity_options->get_args();

		$this->entries    = $args['entries'];
		$this->taxonomies = $args['taxonomies'];
		$this->target     = $args['target'];
	}
	public static $sanitizeProperties = array();
	public static $validateProperties = array();
}
