import { __ } from '@wordpress/i18n';
const { QLSE_DISPLAY_POST_TYPES, QLSE_DISPLAY_TAXONOMIES } = qlseSettings;

import { useDisplaySettings } from '@qlse/store';
import PostTypesSelector from '../../components/post-types-selector';
import Tab from '../tab';
import MultipleSelector from '../../components/multiple-selector';
import TaxonomyTermsSelector from '../../components/taxonomy-terms-selector';

const Content = () => {
	const { setSettingsDisplay, settingsDisplay, saveDisplaySettings } =
		useDisplaySettings();

	const handleSubmit = async () => {
		return await saveDisplaySettings(settingsDisplay);
	};

	const targetOptions = [
		{ label: __('All', 'search-exclude'), value: 'all' },
		{ label: __('Home', 'search-exclude'), value: 'home' },
		{ label: __('Blog', 'search-exclude'), value: 'blog' },
		{ label: __('Search', 'search-exclude'), value: 'search' },
		{ label: __('404', 'search-exclude'), value: 'error' },
	];

	const deviceOptions = [
		{
			value: 'all',
			label: __('Show in all devices', 'search-exclude'),
		},
		{
			value: 'mobile',
			label: __('Show in mobile devices', 'search-exclude'),
		},
		{
			value: 'desktop',
			label: __('Show in desktop devices', 'search-exclude'),
		},
		{
			value: 'hide',
			label: __('Hide in all devices', 'search-exclude'),
		},
	];

	const includeExcludeOptions = [
		{
			value: 1,
			label: __('Include', 'search-exclude'),
		},
		{
			value: 0,
			label: __('Exclude', 'search-exclude'),
		},
	];

	return (
		<Tab settings={settingsDisplay} onSubmit={handleSubmit}>
			<table className="form-table">
				<tbody>
					<tr>
						<th scope="row">{__('Devices', 'search-exclude')}</th>
						<td>
							<select
								style={{ width: '350px' }}
								data-placeholder={__(
									'Choose target&hellip;',
									'search-exclude'
								)}
								value={settingsDisplay.devices}
								onChange={(e) => {
									setSettingsDisplay({
										devices: e.target.value,
									});
								}}
							>
								{deviceOptions.map((option) => (
									<option
										key={option.value}
										value={option.value}
									>
										{option.label}
									</option>
								))}
							</select>
						</td>
					</tr>
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
									value={settingsDisplay.target.include}
									onChange={(e) => {
										setSettingsDisplay({
											target: {
												include: e.target.value,
											},
										});
									}}
								>
									{includeExcludeOptions.map((option) => (
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
									value={settingsDisplay.target.ids}
									onChange={(newValues) => {
										setSettingsDisplay({
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
												name={postType.name?.include}
												value={
													settingsDisplay.entries[
														postType.name
													]?.include
												}
												onChange={(e) => {
													setSettingsDisplay({
														entries: {
															[postType.name]: {
																include:
																	e.target
																		.value,
															},
														},
													});
												}}
											>
												{includeExcludeOptions.map(
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
												settings={settingsDisplay}
												onChangeSettings={
													setSettingsDisplay
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
												name={taxonomy.name?.include}
												value={
													settingsDisplay.taxonomies[
														taxonomy.name
													]?.include
												}
												onChange={(e) => {
													setSettingsDisplay({
														taxonomies: {
															[taxonomy.name]: {
																include:
																	e.target
																		.value,
															},
														},
													});
												}}
											>
												{includeExcludeOptions.map(
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
												settings={settingsDisplay}
												onChangeSettings={
													setSettingsDisplay
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
