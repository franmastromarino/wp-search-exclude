import { __ } from '@wordpress/i18n';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { CheckboxControl } from '@wordpress/components';
import { useState, useEffect, useRef } from '@wordpress/element';
import { useExcludedSettings, useCurrentPostMeta } from '@qlse/store';
import { select, subscribe } from '@wordpress/data';

export const Metabox = () => {
	const {
		settingsExcluded: excluded,
		hasResolvedSettingsExcluded,
		saveExcludedSettings,
	} = useExcludedSettings();
	const { meta, setMeta } = useCurrentPostMeta();

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

			setMeta({
				_exclude_from_search: updatedExcluded ? updatedExcluded : false,
			});

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
