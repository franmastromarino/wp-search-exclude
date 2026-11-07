<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div id="search-exclude-<?php echo esc_attr( $params['post_id'] ); ?>" data-search_exclude="<?php echo (int) $params['exclude']; ?>"
	<?php if ( $params['exclude'] ) : ?>
		title="<?php esc_html_e( 'Hidden from search results', 'search-exclude' ); ?>"><?php esc_html_e( 'Hidden', 'search-exclude' ); ?>
	<?php else : ?>
		title="<?php esc_html_e( 'Visible in search results', 'search-exclude' ); ?>"><?php esc_html_e( 'Visible', 'search-exclude' ); ?>
	<?php endif; ?>
</div>
