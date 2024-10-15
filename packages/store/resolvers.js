/* eslint no-console: "error" */

/**
 * Internal dependencies
 */
import { fetchRestApiSettings } from './helpers';
import * as actions from './actions';

export const getSettings = async () => {
	try {
		const response = await fetchRestApiSettings();

		return actions.setSettings(response);
	} catch (error) {
		console.error(error);
	}
};
