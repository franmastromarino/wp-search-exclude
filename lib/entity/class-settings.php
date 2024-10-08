<?php

namespace QuadLayers\QLSE\Entity;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Settings extends SingleEntity {
	public $excluded                  = array();
	public static $sanitizeProperties = array(); // phpcs:ignore
	public static $validateProperties = array(); // phpcs:ignore
}
