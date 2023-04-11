<div class="misc-pub-section">
	<label for="sep_exclude">
		<input type="hidden" name="sep[hidden]" id="sep_hidden" value="1" />
		<input type="checkbox" name="sep[exclude]" id="sep_exclude" value="1" <?php echo ( $exclude ) ? 'checked' : ''; ?> />
		<?php esc_html_e( 'Exclude from Search Results', 'search-exclude' ); ?>
	</label>
</div>
