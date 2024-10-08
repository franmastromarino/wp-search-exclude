/**
 * External dependencies
 */
import { useExcludedSettings } from '@qlse/store';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { CheckboxControl } from '@wordpress/components';
import { useState, useEffect, useRef } from '@wordpress/element';
import { select, subscribe } from '@wordpress/data';
/**
 * Internal dependencies
 */
import { useExcludeMeta } from '../../helpers/hooks';

export const Metabox = () => {
	const {
		settingsExcluded: excluded,
		hasResolvedSettingsExcluded,
		saveExcludedSettings,
	} = useExcludedSettings();

	const { exclude, setExclude } = useExcludeMeta();

	const [postExcluded, setPostExcluded] = useState([]);
	const postId = select('core/editor').getCurrentPostId();
	const isSavingRef = useRef(false);

	useEffect(() => {
		if (hasResolvedSettingsExcluded) {
			setPostExcluded(excluded);
		}
	}, [hasResolvedSettingsExcluded]);

	useEffect(() => {
		const unsubscribe = subscribe(() => {
			const isSavingPost = select('core/editor').isSavingPost();
			const isAutosavingPost = select('core/editor').isAutosavingPost();

			if (isSavingPost && !isAutosavingPost && !isSavingRef.current) {
				isSavingRef.current = true;
				saveExcludedSettings(postExcluded);
			}

			if (!isSavingPost) {
				isSavingRef.current = false;
			}
		});

		return () => unsubscribe();
	}, [postExcluded]);

	const handleChange = () => {
		setPostExcluded((prevExcluded) => {
			const updatedExcluded = prevExcluded.includes(postId)
				? prevExcluded.filter(
						(excludedPostId) => excludedPostId !== postId
				  )
				: [...prevExcluded, postId];

			setExclude(exclude ? undefined : true);

			return updatedExcluded;
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
