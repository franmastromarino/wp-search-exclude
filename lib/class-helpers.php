<?php

namespace QuadLayers\QLSE;

final class Helpers {
	public static function filter_posts_ids( $post_ids ) {
		return array_filter( filter_var( $post_ids, FILTER_VALIDATE_INT, FILTER_FORCE_ARRAY ) );
	}
}
