import { useSettings } from '@qlse/store';
import { __ } from '@wordpress/i18n';
import {
	QLSE_DISPLAY_POST_TYPES,
	QLSE_DISPLAY_TAXONOMIES,
} from '../../../../helpers/constants';
import PostTypesSelector from '../../components/post-types-selector';
import Tab from '../tab';
import MultipleSelector from '../../components/multiple-selector';
import TaxonomyTermsSelector from '../../components/taxonomy-terms-selector';

const Content = () => {
	const { setSettings, settings, saveSettings, isResolvingSettings } =
		useSettings();

	const handleSubmit = async () => {
		return await saveSettings(settings);
	};
	const targetOptions = [
		{ label: __('Home', 'search-exclude'), value: 'home' },
		{ label: __('Blog', 'search-exclude'), value: 'blog' },
		{ label: __('Search', 'search-exclude'), value: 'search' },
		{ label: __('404', 'search-exclude'), value: 'error' },
	];

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
					<tr></tr>
					<tr>
						<th scope="row">{__('Target', 'search-exclude')}</th>
						<td>
							<div
								style={{
									display: 'flex',
									alignItems: 'flex-start',
								}}
							>
								<select
									style={{ width: '80px' }}
									value={settings.target.all}
									onChange={(e) => {
										setSettings({
											target: {
												all: e.target.value,
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
								<MultipleSelector
									options={targetOptions}
									value={settings.target.ids}
									onChange={(newValues) => {
										setSettings({
											target: {
												ids: newValues,
											},
										});
									}}
								/>
							</div>
							<p className="description hidden">
								{__(
									'If you select an option all the other will be excluded',
									'search-exclude'
								)}
							</p>
						</td>
					</tr>
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
											<PostTypesSelector
												key={postType.name}
												label={postType.label}
												postType={postType.name}
												settings={settings}
												onChangeSettings={setSettings}
												disabled={
													settings.entries[
														postType.name
													]?.all
												}
											/>
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
											<TaxonomyTermsSelector
												key={taxonomy.name}
												label={taxonomy.label}
												taxonomy={taxonomy.name}
												settings={settings}
												onChangeSettings={setSettings}
												disabled={
													settings.taxonomies[
														taxonomy.name
													]?.all
												}
											/>
										</div>
									</td>
								</tr>
							);
						}
					)}
				</tbody>
			</table>
		</Tab>
	);
};

export default Content;
