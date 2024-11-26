import { useSettings } from '@qlse/store';
import { __, sprintf } from '@wordpress/i18n';
import {
	QLSE_DISPLAY_POST_TYPES,
	QLSE_DISPLAY_TAXONOMIES,
} from '../../../../helpers/constants';
import PostTypesSelector from '../../components/post-types-selector';
import Tab from '../tab';
import TaxonomyTermsSelector from '../../components/taxonomy-terms-selector';
import AuthorSelector from '../../components/author-selector';

const Content = () => {
	const { setSettings, settings, saveSettings, isResolvingSettings } =
		useSettings();

	const handleSubmit = async () => {
		return await saveSettings(settings);
	};

	const excludeOptions = [
		{
			value: false,
			label: __('Ids', 'search-exclude'),
		},
		{
			value: true,
			label: __('All', 'search-exclude'),
		},
	];

	return (
		<Tab settings={settings} onSubmit={handleSubmit}>
			<table className="form-table">
				<tbody>
					{Object.values(QLSE_DISPLAY_POST_TYPES).map(
						(postType, index) => {
							return (
								<tr
									key={postType}
									className="qlse-premium-field"
								>
									<th scope="row">{postType.label}</th>
									<td>
										<div
											style={{
												display: 'flex',
												alignItems: 'flex-start',
											}}
										>
											<select
												style={{ width: '80px' }}
												name={postType.name?.all}
												value={
													settings.entries[
														postType.name
													]?.all
												}
												onChange={(e) => {
													setSettings({
														entries: {
															[postType.name]: {
																all:
																	e.target
																		.value ===
																	'true',
															},
														},
													});
												}}
											>
												{excludeOptions.map(
													(option) => (
														<option
															key={option.value}
															value={option.value}
														>
															{option.label}
														</option>
													)
												)}
											</select>
											<div>
												<PostTypesSelector
													key={postType.name}
													label={postType.label}
													postType={postType.name}
													settings={settings}
													onChangeSettings={
														setSettings
													}
													disabled={
														settings.entries[
															postType.name
														]?.all
													}
												/>
												{settings.entries[postType.name]
													?.all && (
													<p className="description">
														{sprintf(
															__(
																'All %s are excluded.',
																'ai-copilot'
															),
															postType.label.toLowerCase()
														)}
													</p>
												)}
											</div>
										</div>
									</td>
								</tr>
							);
						}
					)}
					{Object.values(QLSE_DISPLAY_TAXONOMIES).map(
						(taxonomy, index) => {
							return (
								<tr
									key={taxonomy}
									className="qlse-premium-field"
								>
									<th scope="row">{taxonomy.label}</th>
									<td>
										<div
											style={{
												display: 'flex',
												alignItems: 'flex-start',
											}}
										>
											<select
												style={{ width: '80px' }}
												name={taxonomy.name?.all}
												value={
													settings.taxonomies[
														taxonomy.name
													]?.all
												}
												onChange={(e) => {
													setSettings({
														taxonomies: {
															[taxonomy.name]: {
																all:
																	e.target
																		.value ===
																	'true',
															},
														},
													});
												}}
											>
												{excludeOptions.map(
													(option) => (
														<option
															key={option.value}
															value={option.value}
														>
															{option.label}
														</option>
													)
												)}
											</select>
											<div>
												<TaxonomyTermsSelector
													key={taxonomy.name}
													label={taxonomy.label}
													taxonomy={taxonomy.name}
													settings={settings}
													onChangeSettings={
														setSettings
													}
													disabled={
														settings.taxonomies[
															taxonomy.name
														]?.all
													}
												/>
												{settings.taxonomies[
													taxonomy.name
												]?.all && (
													<p className="description">
														{sprintf(
															__(
																'All %s are excluded.',
																'ai-copilot'
															),
															taxonomy.label.toLowerCase()
														)}
													</p>
												)}
											</div>
										</div>
									</td>
								</tr>
							);
						}
					)}
					<tr className="qlse-premium-field">
						<th scope="row">{__('Authors', 'search-exclude')}</th>
						<td>
							<div
								style={{
									display: 'flex',
									alignItems: 'flex-start',
								}}
							>
								<select
									style={{ width: '80px' }}
									name={settings.author?.all}
									value={settings.author?.all}
									onChange={(e) => {
										setSettings({
											author: {
												all: e.target.value === 'true',
											},
										});
									}}
								>
									{excludeOptions.map((option) => (
										<option
											key={option.value}
											value={option.value}
										>
											{option.label}
										</option>
									))}
								</select>
								<div>
									<AuthorSelector
										settings={settings}
										onChangeSettings={setSettings}
										disabled={settings.author?.all}
									/>
									{settings.author?.all && (
										<p className="description">
											{__(
												'All authors are excluded.',
												'ai-copilot'
											)}
										</p>
									)}
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</Tab>
	);
};

export default Content;
