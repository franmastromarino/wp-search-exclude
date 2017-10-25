<div class="wrap">
    <h2>Search Exclude</h2>
    <?php if (empty($excluded)):?>
        <p>No items excluded from the search results yet.</p>
    <?php else: ?>
        <form method="post" action="options-general.php?page=search_exclude" enctype="multipart/form-data">
            <h3>Search Engines</h3>
            <p>
                <label>
                    <input type="checkbox" id="sep_exclude_from_search_engines" name="sep_exclude_from_search_engines" <?php if ($exclude_from_search_engines):?> checked <?php endif ?>> Exclude items from Search Engines
                </label>
            </p>
            <hr>
            <h3>Site Search</h3>
            <h4>The following items are excluded from the search results</h4>
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
            <p class="submit"><input type="submit" name="search_exclude_submit" id="search_exclude_submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
        </form>
    <?php endif; ?>

</div>