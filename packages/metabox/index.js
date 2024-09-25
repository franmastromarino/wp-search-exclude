import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { CheckboxControl } from '@wordpress/components';
import { useState } from '@wordpress/element';

const Metabox = () => {
	const [check, setCheck] = useState(false);
	const handleChange = (value) => {
		//...
		setCheck(value);
	};

	return (
		<PluginDocumentSettingPanel title="Search Exclude">
			<CheckboxControl
				__nextHasNoMarginBottom
				label={__('Exclude from Search Results', 'search-exclude')}
				value={check}
				onChange={handleChange}
			/>
		</PluginDocumentSettingPanel>
	);
};

// Register the plugin
registerPlugin('custom-sidebar-metabox', {
	render: Metabox,
});
