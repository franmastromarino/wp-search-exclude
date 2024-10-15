/**
 * External dependencies
 */
import { onDocumentLoaded } from '../helpers/onDocumentLoaded';
/**
 * WordPress dependencies
 */
import { render } from '@wordpress/element';
import { App } from './app';

onDocumentLoaded(() => {
	const container = document.querySelector('#search-exclude-settings');

	render(<App />, container);
});
