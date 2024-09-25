import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import { useExcludedSettings } from '@qlse/store';

export const QuickEdit = ({ postId, updateButton }) => {
	const { settingsExcluded, saveExcludedSettings } = useExcludedSettings();
	const excluded = settingsExcluded?.excluded;
	const [postExcluded, setPostExcluded] = useState(excluded);

	const handleOnChange = (checked) => {
		setPostExcluded((prevExcluded) => {
			if (checked) {
				return [...prevExcluded, postId];
			}
			return prevExcluded.filter(
				(excludedPostId) => excludedPostId !== postId
			);
		});
	};

	useEffect(() => {
		const handleClick = async () => {
			return await saveExcludedSettings({ excluded: postExcluded });
		};

		updateButton.addEventListener('click', handleClick);

		return () => updateButton.removeEventListener('click', handleClick);
	}, [postExcluded]);

	return (
		<fieldset className="inline-edit-col-right">
			<div className="inline-edit-col">
				<div className="inline-edit-group">
					<label htmlFor="sep_exclude">
						<input
							type="checkbox"
							checked={postExcluded.includes(postId)}
							onChange={(e) => handleOnChange(!!e.target.checked)}
						/>
						<span className="checkbox-title">
							{__(
								'Exclude from Search Results ract',
								'search-exclude'
							)}
						</span>
					</label>
				</div>
			</div>
		</fieldset>
	);
};
