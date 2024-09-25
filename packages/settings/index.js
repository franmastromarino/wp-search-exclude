/**
 * External dependencies
 */
import { SearchExclude } from './search-exclude';
import { onDocumentLoaded } from '../helpers/onDocumentLoaded';
/**
 * WordPress dependencies
 */
import { render } from '@wordpress/element';

onDocumentLoaded(() => {
	const container = document.querySelector('#search-exclude-settings');

	render(<SearchExclude />, container);
});
