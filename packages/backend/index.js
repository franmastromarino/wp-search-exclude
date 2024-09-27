// /**
//  * External dependencies
//  */
// import { onDocumentLoaded } from '../helpers/onDocumentLoaded';
// /**
//  * WordPress dependencies
//  */
// import { createRoot } from '@wordpress/element';
// /**
//  * Internal dependencies
//  */
// import { SearchExcludeColumn } from './search-exclude-column';
// import { QuickEdit } from './quick-edit';
// // export * from './components';

// onDocumentLoaded(() => {
// 	// const columns = document.querySelectorAll('#the-list tr');
// 	// const container = document.createElement('th');
// 	// const targetHeader = document.querySelector('.wp-list-table thead tr');
// 	// const targetFooter = document.querySelector('.wp-list-table tfoot tr');
// 	// //footer header rendering
// 	// [targetHeader, targetFooter].forEach((target) => {
// 	// 	const clone = container.cloneNode(true);
// 	// 	clone.classList.add('manage-column', 'column-search_exclude');
// 	// 	target.appendChild(clone);
// 	// 	const root = createRoot(clone);
// 	// 	root.render('Search Excluded react');
// 	// });
// 	// //add search-exclude columns
// 	// columns.forEach((column) => {
// 	// 	const containerBody = document.createElement('td');
// 	// 	containerBody.classList.add('column-search_exclude');
// 	// 	column.appendChild(containerBody);
// 	// 	const postId = column.id.split('-')[1];
// 	// 	const root = createRoot(containerBody);
// 	// 	root.render(<SearchExcludeColumn postId={postId} />);
// 	// });
// 	//quick edit section
// 	// const originalInlineEditPost = inlineEditPost.edit;
// 	// inlineEditPost.edit = function (buttonQuickEdit) {
// 	// 	// Call the original Quick Edit functionality
// 	// 	originalInlineEditPost.apply(this, arguments);
// 	// 	const postId = this.getId(buttonQuickEdit).toString();
// 	// 	const quickEditRow = document.querySelector(`#edit-${postId}`);
// 	// 	if (quickEditRow) {
// 	// 		const QuickEditContainer = document.createElement('div');
// 	// 		const updateButton = document.querySelector('button.save');
// 	// 		quickEditRow
// 	// 			.querySelector('fieldset.inline-edit-col-left')
// 	// 			.appendChild(QuickEditContainer);
// 	// 		const root = createRoot(QuickEditContainer);
// 	// 		root.render(
// 	// 			<QuickEdit postId={postId} updateButton={updateButton} />
// 	// 		);
// 	// 	}
// 	// };
// });
