import { registerPlugin } from '@wordpress/plugins';
import { Metabox } from './metabox';

// Register the plugin
registerPlugin('search-exclude-sidebar-metabox', {
	render: Metabox,
});
