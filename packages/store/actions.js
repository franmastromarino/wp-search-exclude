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

export const setSettings = (newSettings) => {
	return {
		type: 'SET_SETTINGS',
		payload: (prevSettings) => {
			const settings = deepMerge(prevSettings, newSettings);

			return settings;
		},
	};
};

export const saveSettings =
	(data) =>
	async ({ registry, dispatch, select }) => {
		const response = await fetchRestApiSettings({
			method: 'POST',
			data,
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

		dispatch.setSettings(data);

		registry
			.dispatch(noticesStore)
			.createSuccessNotice(__('Settings saved.', 'search-exclude'), {
				type: 'snackbar',
			});

		return true;
	};
