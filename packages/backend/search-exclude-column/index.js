/* eslint-disable @wordpress/valid-sprintf */
import { __ } from '@wordpress/i18n';
import { useExcludedSettings } from '@qlse/store';
/**
 * External dependencies
 */

export const SearchExcludeColumn = ({ postId }) => {
	const { settingsExcluded } = useExcludedSettings();
	const excluded = settingsExcluded?.excluded;

	const isExcluded = excluded?.includes(postId);

	return (
		<div
			id={`search-exclude-${postId}`}
			title={
				isExcluded
					? __('Hidden from search results', 'search-exclude')
					: __('Visible in search results', 'search-exclude')
			}
		>
			{isExcluded
				? __('Hidden', 'search-exclude')
				: __('Visible', 'search-exclude')}
		</div>
	);
};
