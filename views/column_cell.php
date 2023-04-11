<div id="search-exclude-<?php echo $postId ?>" data-search_exclude="<?php echo (int)$exclude ?>"
    <?php if ($exclude): ?>
        title="Hidden from search results">Hidden
    <?php else: ?>
        title="Visible in search results">Visible
    <?php endif; ?>
</div>