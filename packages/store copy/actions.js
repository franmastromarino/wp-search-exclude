/**
 * Wordpress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { store as noticesStore } from '@wordpress/notices';
/**
 * Internal dependencies
 */
import { fetchRestApiSettings } from './helpers';
import { deepMerge } from '../helpers/deepMerge';

export const setSettingsDisplay = (newSettings) => {
	return {
		type: 'SET_DISPLAY',
		payload: (prevSettings) => {
			const settings = deepMerge(prevSettings, newSettings);

			return settings;
		},
	};
};

export const setSettingsExcluded = (newExcludeds) => {
	return {
		type: 'SET_EXCLUDED',
		payload: () => {
			return newExcludeds;
		},
	};
};

export const saveSettings =
	(data, route) =>
	async ({ registry, dispatch, select }) => {
		let settings;

		if (!route) {
			throw new Error('Route is required.');
		}

		switch (route) {
			case 'excluded':
				settings = {
					...select.getSettingsExcluded(),
					excluded: data,
				};
				break;
			case 'display':
				settings = {
					...select.getSettingsDisplay(),
					display: data,
				};
		}

		const setter = route.charAt(0).toUpperCase() + route.slice(1);
		const response = await fetchRestApiSettings({
			method: 'POST',
			data: settings,
			route,
		});

		if (response?.code) {
			registry
				.dispatch(noticesStore)
				.createSuccessNotice(
					sprintf('%s: %s', response.code, response.message),
					{ type: 'snackbar' }
				);
			return false;
		}

		dispatch[`setSettings${setter}`](data);

		registry
			.dispatch(noticesStore)
			.createSuccessNotice(
				sprintf(__('%s settings saved.', 'search-exclude'), setter),
				{
					type: 'snackbar',
				}
			);

		return true;
	};

export const saveDisplaySettings = (data) => saveSettings(data, 'display');

export const saveExcludedSettings = (data) => saveSettings(data, 'excluded');
