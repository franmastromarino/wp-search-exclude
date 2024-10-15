/**
 * External dependencies
 */
import { useSettings } from '@qlse/store';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { CheckboxControl } from '@wordpress/components';
import { useState, useEffect, useRef } from '@wordpress/element';
import { select, subscribe, useDispatch } from '@wordpress/data';
/**
 * Internal dependencies
 */
import { useExcludeMeta } from '../../helpers/hooks';

export const Metabox = () => {
	const { settings, setSettings, hasResolvedSettings, saveSettings } =
		useSettings();

	const { exclude, setExclude } = useExcludeMeta();
	// const [postExcluded, setPostExcluded] = useState([]);
	const postId = select('core/editor').getCurrentPostId();
	const isSavingRef = useRef(false);

	// useEffect(() => {
	// 	if (hasResolvedSettings) {
	// 		setPostExcluded(settings.excluded);
	// 	}
	// }, [hasResolvedSettings]);

	useEffect(() => {
		const unsubscribe = subscribe(() => {
			const isSavingPost = select('core/editor').isSavingPost();
			const isAutosavingPost = select('core/editor').isAutosavingPost();

			if (isSavingPost && !isAutosavingPost && !isSavingRef.current) {
				isSavingRef.current = true;
				saveSettings(settings);
			}

			if (!isSavingPost) {
				isSavingRef.current = false;
			}
		});

		return () => unsubscribe();
	}, [settings]);

	const handleChange = () => {
		const excluded = settings.excluded;

		const updatedExcluded = excluded.includes(postId)
			? excluded.filter((excludedPostId) => excludedPostId !== postId)
			: [...excluded, postId];

		setExclude(exclude ? undefined : true);

		setSettings({ excluded: updatedExcluded });
	};

	return (
		<PluginDocumentSettingPanel title="Search Exclude">
			<CheckboxControl
				__nextHasNoMarginBottom
				label={__('Exclude from Search Results', 'search-exclude')}
				checked={settings.excluded?.includes(postId)}
				onChange={handleChange}
			/>
		</PluginDocumentSettingPanel>
	);
};
