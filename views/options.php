<div class="wrap">
    <h2>Search Exclude</h2>
    <?php if (empty($excluded)):?>
        <p>No items excluded from the search results yet.</p>
    <?php else: ?>
        <h3>The following items are excluded from the search results</h3>
        <form method="post" action="options-general.php?page=search_exclude" enctype="multipart/form-data">
            <table cellspacing="0" class="wp-list-table widefat fixed pages">
                <thead>
                <tr>
                    <th style="" class="check-column" id="cb" scope="col"></th><th style="" class="column-title manage-column" id="title" scope="col"><span>Title</span></th><th style="" class="manage-column column-type" id="type" scope="col"><span>Type</span>
                </tr>
                </thead>

                <tbody id="the-list">
                    <?php while ( $query->have_posts() ) : $query->the_post();?>
                <tr valign="top" class="post-<?php the_ID()?> page type-page status-draft author-self" >
                    <th class="check-column" scope="row"><input type="checkbox" value="<?php the_ID()?>" name="sep_exclude[]" checked="checked"></th>
                    <td class="post-title page-title column-title"><strong><a title="Edit “<?php the_title()?>”" href="<?php echo site_url()?>/wp-admin/post.php?post=<?php the_ID()?>&action=edit" class="row-title"><?php the_title()?></a></strong></td>
                    <td class="author column-author"><?php echo get_post_type();?></td>
                </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <?php wp_nonce_field( 'search_exclude_submit'); ?>

            <p class="submit"><input type="submit" name="search_exclude_submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
        </form>
    <?php endif; ?>
</div>
