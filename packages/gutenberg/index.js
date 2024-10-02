import { registerPlugin } from '@wordpress/plugins';
import { Metabox } from './metabox';

// Register the plugin
registerPlugin('custom-sidebar-metabox', {
	render: Metabox,
});
