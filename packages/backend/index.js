/**
 * External dependencies
 */
import { onDocumentLoaded } from '../helpers/onDocumentLoaded';

onDocumentLoaded(() => {
	const originalInlineEditPost = inlineEditPost.edit;
	inlineEditPost.edit = function (buttonQuickEdit) {
		// Call the original Quick Edit functionality
		originalInlineEditPost.apply(this, arguments);
		const postId = this.getId(buttonQuickEdit).toString();
		const quickEditRow = document.querySelector(`#edit-${postId}`);
		if (quickEditRow) {
			const exclude = Number(
				document.querySelector(`#search-exclude-${postId}`).dataset
					.search_exclude
			);

			quickEditRow.querySelector('input[name="sep[exclude]"]').checked =
				exclude === 1 ? true : false;
		}
	};
});
