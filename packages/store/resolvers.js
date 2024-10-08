/* eslint no-console: "error" */

/**
 * Internal dependencies
 */
import { fetchRestApiSettings } from './helpers';
import * as actions from './actions';

export const getSettingsDisplay = async () => {
	try {
		const response = await fetchRestApiSettings({ route: 'display' });
		return actions.setSettingsDisplay(response);
	} catch (error) {
		console.error(error);
	}
};

export const getSettingsExcluded = async () => {
	try {
		const response = await fetchRestApiSettings({ route: 'excluded' });
		return actions.setSettingsExcluded(response?.excluded);
	} catch (error) {
		console.error(error);
	}
};
