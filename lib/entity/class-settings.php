<?php

namespace QuadLayers\QLSE\Entity;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Settings extends SingleEntity {
	public $excluded                  = array();
	public static $sanitizeProperties = array();
	public static $validateProperties = array();
}
