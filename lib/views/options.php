<div class="wrap">
	<h2>Search Exclude</h2>
	<?php if ( empty( $excluded ) ) : ?>
		<p><?php esc_html_e( 'No items excluded from the search results yet.', 'search-exclude' ); ?></p>
	<?php else : ?>
		<h3><?php esc_html_e( 'The following items are excluded from the search results', 'search-exclude' ); ?></h3>
		<form method="post" action="options-general.php?page=search_exclude" enctype="multipart/form-data">
			<table cellspacing="0" class="wp-list-table widefat fixed pages">
				<thead>
				<tr>
					<th class="check-column" id="cb" scope="col"></th>
					<th class="column-title manage-column" id="title" scope="col"><span><?php esc_html_e( 'Title', 'search-exclude' ); ?></span></th>
					<th class="manage-column column-type" id="type" scope="col"><span><?php esc_html_e( 'Type', 'search-exclude' ); ?></span>
				</tr>
				</thead>
				<tbody id="the-list">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						?>
						<tr valign="top" class="post-<?php the_ID(); ?> page type-page status-draft author-self">
							<th class="check-column" scope="row">
								<input type="checkbox" value="<?php the_ID(); ?>" name="sep_exclude[]" checked="checked"></th>
							<td class="post-title page-title column-title">
								<strong><a title="Edit “<?php echo esc_attr( the_title( '', '', false ) ); ?>”" href="<?php echo esc_url( site_url() ); ?>/wp-admin/post.php?post=<?php the_ID(); ?>&action=edit" class="row-title"><?php echo esc_html( the_title( '', '', false ) ); ?></a></strong>
							</td>
							<td class="author column-author"><?php echo esc_html( get_post_type() ); ?></td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>

			<?php wp_nonce_field( 'search_exclude_submit' ); ?>

			<p class="submit"><input type="submit" name="search_exclude_submit" class="button-primary" value="<?php esc_html_e( 'Save Changes' ); ?>"/></p>
		</form>
	<?php endif; ?>
</div>
