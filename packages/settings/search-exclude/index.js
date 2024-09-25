import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

export const SearchExclude = () => {
	const excluded = [];
	// const [excluded, setExcluded] = useState([]);

	const handleSubmit = (event) => {
		event.preventDefault();
	};

	return (
		<div className="wrap">
			<h2>{__('Search Exclude', 'search-excluded')}</h2>
			{excluded.length === 0 ? (
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
						onSubmit={handleSubmit}
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
								{excluded.map((item) => (
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
												value={item.id}
												name="sep_exclude[]"
												defaultChecked={true}
											/>
										</th>
										<td className="post-title page-title column-title">
											<strong>
												<a
													title={`Edit “${item.title}”`}
													href={`/wp-admin/post.php?post=${item.id}&action=edit`}
													className="row-title"
												>
													{item.title}
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
						<input
							type="hidden"
							name="nonce_field"
							value="your_nonce_here"
						/>
						<p className="submit">
							<input
								type="submit"
								name="search_exclude_submit"
								className="button-primary"
								value="Save Changes"
							/>
						</p>
					</form>
				</>
			)}
		</div>
	);
};
