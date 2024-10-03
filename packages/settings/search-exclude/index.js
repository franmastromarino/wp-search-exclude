import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import { useExcludedSettings } from '@qlse/store';
import {
	usePostsByIds,
	usePostsByIdsAnyPostType,
	usePostTypes,
	useValidPostTypes,
} from '../../helpers/hooks';

export const SearchExclude = () => {
	const {
		settingsExcluded: excluded,
		saveExcludedSettings,
		hasResolvedSettingsExcluded,
		isResolvingSettingsExcluded,
	} = useExcludedSettings();

	const { posts, isResolvingPosts, hasPosts, hasResolvedPosts } =
		usePostsByIdsAnyPostType(excluded);

	console.log('test: ', posts);

	const [postExcluded, setPostExcluded] = useState([]);
	const [excludedPreview, setExcludedPreview] = useState([]);
	const isExcludedChanged =
		excluded.sort().join(' ') !== excludedPreview.sort().join(' ');

	const test2 = useValidPostTypes();

	console.log('test2: ', test2);

	const isLoadingPostTypes = isResolvingPosts || isResolvingSettingsExcluded;

	const hasLoadedPostTypes = hasResolvedPosts && hasResolvedSettingsExcluded;

	useEffect(() => {
		if (hasLoadedPostTypes) {
			const excludedSet = new Set(excluded);

			setPostExcluded([...posts].filter(({ id }) => excludedSet.has(id)));

			setExcludedPreview(excluded);
		}
	}, [hasLoadedPostTypes, excluded]);

	const handleChange = (postId) => {
		setExcludedPreview((prevExcluded) => {
			if (prevExcluded.includes(postId)) {
				return prevExcluded.filter(
					(excludedPostId) => excludedPostId !== postId
				);
			}
			return [...prevExcluded, postId];
		});
	};

	const handleSubmit = async (e) => {
		e.preventDefault();
		await saveExcludedSettings(excludedPreview);
	};

	return (
		<div className="wrap">
			<h2>{__('Search Exclude', 'search-excluded')}</h2>
			{isLoadingPostTypes ? (
				<div>{__('Loading…', 'search-excluded')}</div>
			) : postExcluded?.length === 0 ? (
				<p>
					{__(
						'No items excluded from the search results yet.',
						'search-excluded'
					)}
				</p>
			) : (
				<>
					<h3>
						{__(
							'The following items are excluded from the search results',
							'search-excluded'
						)}
					</h3>
					<form
						method="post"
						action="options-general.php?page=search_exclude"
						encType="multipart/form-data"
						onSubmit={(e) => handleSubmit(e)}
					>
						<table
							className="wp-list-table widefat fixed pages"
							cellSpacing="0"
						>
							<thead>
								<tr>
									<th
										className="check-column"
										scope="col"
									></th>
									<th
										className="column-title manage-column"
										scope="col"
									>
										<span>
											{__('Title', 'search-excluded')}
										</span>
									</th>
									<th
										className="manage-column column-type"
										scope="col"
									>
										<span>
											{__('Type', 'search-excluded')}
										</span>
									</th>
								</tr>
							</thead>
							<tbody>
								{postExcluded.map((item) => (
									<tr
										key={item.id}
										className={`post-${item.id} page type-${item.type}`}
									>
										<th
											className="check-column"
											scope="row"
										>
											<input
												type="checkbox"
												onChange={() =>
													handleChange(item.id)
												}
												value={item.id}
												defaultChecked={true}
											/>
										</th>
										<td className="post-title page-title column-title">
											<strong>
												<a
													title={`Edit “${item.title?.raw}”`}
													href={`/wp-admin/post.php?post=${item.id}&action=edit`}
													className="row-title"
												>
													{item.title?.raw}
												</a>
											</strong>
										</td>
										<td className="author column-author">
											{item.type}
										</td>
									</tr>
								))}
							</tbody>
						</table>
						<p className="submit">
							<input
								type="submit"
								className="button-primary"
								disabled={!isExcludedChanged}
								value="Save Changes"
							/>
						</p>
					</form>
				</>
			)}
		</div>
	);
};
