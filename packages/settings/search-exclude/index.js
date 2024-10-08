/**
 * External dependencies
 */
import { useExcludedSettings } from '@qlse/store';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
/**
 * Internal dependencies
 */
import { usePostsByIdsAnyPostType } from '../../helpers/hooks';
import { formatDate } from '../../helpers/formatDate';

export const SearchExclude = () => {
	const {
		settingsExcluded: excluded,
		saveExcludedSettings,
		hasResolvedSettingsExcluded,
		isResolvingSettingsExcluded,
	} = useExcludedSettings();

	const { posts, isResolvingPosts, hasResolvedPosts } =
		usePostsByIdsAnyPostType(excluded);

	const [excludedPreview, setExcludedPreview] = useState([]);
	const isExcludedChanged =
		excluded.sort().join(' ') !== excludedPreview.sort().join(' ');

	const isLoadingPostTypes = isResolvingPosts || isResolvingSettingsExcluded;

	const hasLoadedPostTypes = hasResolvedPosts && hasResolvedSettingsExcluded;

	useEffect(() => {
		if (hasLoadedPostTypes) {
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
	const postTypes = [...new Set(posts.map((post) => post.postType))];

	return (
		<div className="wrap">
			<h2>{__('Search Exclude', 'search-exclude')}</h2>
			{isLoadingPostTypes ? (
				<span>{__('Loading…', 'search-exclude')}</span>
			) : posts?.length === 0 ? (
				<p>
					{__(
						'No items excluded from the search results yet.',
						'search-exclude'
					)}
				</p>
			) : (
				<>
					<span>
						{__(
							'The following items are excluded from the search results.',
							'search-exclude'
						)}
					</span>
					<form
						method="post"
						action="options-general.php?page=search_exclude"
						encType="multipart/form-data"
						onSubmit={(e) => handleSubmit(e)}
					>
						{postTypes.map((type, index) => (
							<div key={index}>
								<h3>
									{type[0].toUpperCase() + type.substring(1)}
								</h3>
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
													{__(
														'Title',
														'search-exclude'
													)}
												</span>
											</th>
											<th
												className="manage-column column-type"
												scope="col"
											>
												<span>
													{__(
														'Date',
														'search-exclude'
													)}
												</span>
											</th>
										</tr>
									</thead>
									<tbody>
										{posts
											.filter(
												(post) => post.postType === type
											)
											.map((post) => (
												<tr
													key={post.id}
													className={`post-${post.id} page type-${post.type}`}
												>
													<th
														className="check-column"
														scope="row"
													>
														<input
															type="checkbox"
															onChange={() =>
																handleChange(
																	post.id
																)
															}
															value={post.id}
															defaultChecked={
																true
															}
														/>
													</th>
													<td className="post-title page-title column-title">
														<strong>
															<a
																title={`Edit “${post.title}”`}
																href={`/wp-admin/post.php?post=${post.id}&action=edit`}
																className="row-title"
															>
																{post.title}
															</a>
														</strong>
													</td>
													<td className="author column-date">
														{formatDate(post.date)}
													</td>
												</tr>
											))}
									</tbody>
								</table>
							</div>
						))}

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
