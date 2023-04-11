import jQuery from 'jquery';

(function ($) {
	// we create a copy of the WP inline edit post function
	// eslint-disable-next-line
	const $wpInlineEdit = inlineEditPost.edit;

	// and then we overwrite the function with our own code
	// eslint-disable-next-line
	inlineEditPost.edit = function (id) {
		// "call" the original WP edit function
		// we don't want to leave WordPress hanging
		$wpInlineEdit.apply(this, arguments);

		let $postId = 0;
		if (typeof id === 'object') {
			$postId = parseInt(this.getId(id));
		}

		if ($postId > 0) {
			const $editRow = $('#edit-' + $postId);
			const $exclude = $('#search-exclude-' + $postId).data(
				'search_exclude'
			);
			$editRow
				.find('input[name="sep[exclude]"]')
				.prop('checked', $exclude);
		}
	};
})(jQuery);
