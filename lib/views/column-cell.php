<div id="search-exclude-<?php echo esc_attr( $post_id ); ?>" data-search_exclude="<?php echo (int) $exclude; ?>"
	<?php if ( $exclude ) : ?>
		title="<?php esc_html_e( 'Hidden from search results', 'search-exclude' ); ?>"><?php esc_html_e( 'Hidden', 'search-exclude' ); ?>
	<?php else : ?>
		title="<?php esc_html_e( 'Visible in search results', 'search-exclude' ); ?>"><?php esc_html_e( 'Visible', 'search-exclude' ); ?>
	<?php endif; ?>
</div>
