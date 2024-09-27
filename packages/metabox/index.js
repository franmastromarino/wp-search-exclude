import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { CheckboxControl } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import { useExcludedSettings } from '@qlse/store';
import { select, dispatch } from '@wordpress/data';
import { addFilter, removeFilter } from '@wordpress/hooks';

const Metabox = () => {
	const {
		settingsExcluded,
		hasResolvedSettingsExcluded,
		saveExcludedSettings,
	} = useExcludedSettings();

	const excluded = settingsExcluded?.excluded;
	const [postExcluded, setPostExcluded] = useState([]);
	const postId = select('core/editor').getCurrentPostId();

	useEffect(() => {
		if (hasResolvedSettingsExcluded) {
			setPostExcluded(excluded);
		}
	}, [hasResolvedSettingsExcluded]);

	useEffect(() => {
		const originalSavePost = dispatch('core/editor').savePost;

		const savePost = async (...args) => {
			saveExcludedSettings(postExcluded);

			return originalSavePost(...args);
		};

		addFilter('core/editor.savePost', 'save_excluded', () => savePost);

		return () => {
			removeFilter('core/editor.savePost', 'save_excluded');
		};
	}, []);

	const handleChange = (checked) => {
		setPostExcluded((prevExcluded) => {
			if (checked) {
				return [...prevExcluded, postId];
			}
			return prevExcluded.filter(
				(excludedPostId) => excludedPostId !== postId
			);
		});
	};

	return (
		<PluginDocumentSettingPanel title="Search Exclude">
			<CheckboxControl
				__nextHasNoMarginBottom
				label={__('Exclude from Search Results', 'search-exclude')}
				checked={postExcluded?.includes(postId)}
				onChange={handleChange}
			/>
		</PluginDocumentSettingPanel>
	);
};

// Register the plugin
registerPlugin('custom-sidebar-metabox', {
	render: Metabox,
});
