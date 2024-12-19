/**
 * External dependencies
 */
import { useSettings } from '@qlse/store';
/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';

//TODO: uncomment when WP 6.6 is the minimum version required
// import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { CheckboxControl, Spinner } from '@wordpress/components';
import { useState, useEffect, useRef } from '@wordpress/element';
import { select, subscribe, useDispatch } from '@wordpress/data';
/**
 * Internal dependencies
 */
import { useExcludeMeta } from '../../helpers/hooks';

export const Metabox = () => {
	const { settings, setSettings, isResolvingSettings, saveSettings } =
		useSettings();
	/**
	 * PluginDocumentSettingPanel - Supporting multiple WordPress versions (fix undefined import in wp versions before 6.6)
	 *
	 *
	 * https://make.wordpress.org/core/2024/06/18/editor-unified-extensibility-apis-in-6-6/
	 *
	 *
	 * The wp.editPost and wp.editSite slots will continue to work without changes, but the old slot locations will be deprecated.
	 * To avoid triggering console warnings, you can support both the new and old slots at the same time.
	 *
	 * Once you are ready to make WP 6.6 the minimum required version for your plugin, you should be able to drop the fallbacks and restore the initial code.
	 */
	const PluginDocumentSettingPanel =
		wp.editor?.PluginDocumentSettingPanel ??
		wp.editPost?.PluginDocumentSettingPanel ??
		wp.editSite?.PluginDocumentSettingPanel;

	const { exclude, setExclude } = useExcludeMeta();
	const postId = select('core/editor').getCurrentPostId();
	const postType = select('core/editor').getCurrentPostType();
	const isSavingRef = useRef(false);
	const excludedAll = settings?.entries[postType]?.all;
	const excludedIds = settings?.entries[postType]?.ids;

	const isExcluded = excludedIds?.includes(postId) || excludedAll;

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
		const updatedExcluded = excludedIds.includes(postId)
			? excludedIds.filter((excludedPostId) => excludedPostId !== postId)
			: [...excludedIds, postId];

		setExclude(exclude ? undefined : true);

		setSettings({ entries: { [postType]: { ids: updatedExcluded } } });
	};

	return (
		<PluginDocumentSettingPanel
			title="Search Exclude"
			name="search-exclude"
		>
			{isResolvingSettings ? (
				<div className="qlse__checkbox--loading">
					<Spinner />
					<label>
						{__('Exclude from Search Results', 'search-exclude')}
					</label>
				</div>
			) : (
				<CheckboxControl
					__nextHasNoMarginBottom
					help={
						excludedAll
							? sprintf(
									__(
										'All %s are excluded.',
										'search-exclude'
									),
									postType
							  )
							: ''
					}
					label={__('Exclude from Search Results', 'search-exclude')}
					isDisabled={excludedAll || isResolvingSettings}
					checked={isExcluded}
					onChange={handleChange}
				/>
			)}
		</PluginDocumentSettingPanel>
	);
};
