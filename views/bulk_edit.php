<fieldset class="inline-edit-col-right">
    <div class="inline-edit-col">
        <div class="inline-edit-group">
            <label class="alignleft">
                <span class="title search-exclude-label">Show in Search Results</span>
                <select name="sep[exclude]">
                    <option value="">— No Change —</option>
                    <option value="1">Hide</option>
                    <option value="0">Show</option>
                </select>
            </label>
            <?php wp_nonce_field( 'search_exclude_bulk_edit', '_wpnonce_search_exclude_bulk_edit'); ?>
        </div>
    </div>
</fieldset>
