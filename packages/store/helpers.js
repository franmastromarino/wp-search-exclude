/**
 * External dependencies
 */
import { apiFetch } from '../helpers/apiFetch.js';
/**
 * Wordpress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useSelect, useDispatch } from '@wordpress/data';
import { addQueryArgs } from '@wordpress/url';
/**
 * Internal dependencies
 */
import { STORE_NAME } from './constants';

// eslint-disable-next-line no-undef
export const { QLSE_REST_ROUTE, WP_VERSION } = qlseStore;

export const FIRST_WP_VERSION_WITH_THUNK_SUPPORT = '6.0';

export const fetchRestApiSettings = ({ method, data } = {}) => {
	const path =
		method === 'GET'
			? addQueryArgs(QLSE_REST_ROUTE, data)
			: QLSE_REST_ROUTE;

	return apiFetch({
		path,
		method,
		data,
	});
};

export function useSettings() {
	const { setSettings, saveSettings } = useDispatch(STORE_NAME);

	const { settings, isResolvingSettings, hasResolvedSettings } = useSelect(
		(select) => {
			const { getSettings, isResolving, hasFinishedResolution } =
				select(STORE_NAME);

			return {
				settings: getSettings(),
				isResolvingSettings: isResolving('getSettings'),
				hasResolvedSettings: hasFinishedResolution('getSettings'),
			};
		},
		[]
	);

	return {
		settings,
		isResolvingSettings,
		hasResolvedSettings,
		hasSettings: !!(hasResolvedSettings && Object.keys(settings)?.length),
		saveSettings,
		setSettings,
	};
}

export const isVersionLessThan = (currentVersion, targetVersion) => {
	const currentParts = currentVersion.split('.').map(Number);
	const targetParts = targetVersion.split('.').map(Number);

	for (let i = 0; i < targetParts.length; i++) {
		if (currentParts[i] < targetParts[i]) {
			return true;
		}

		if (currentParts[i] > targetParts[i]) {
			return false;
		}
	}

	return false;
};

const createThunkArgs = (instance, registry) => {
	const thunkArgs = {
		registry,
		get dispatch() {
			return Object.assign(
				(action) => instance.store.dispatch(action),
				instance.getActions()
			);
		},
		get select() {
			return Object.assign(
				(selector) =>
					selector(instance.store.__unstableOriginalGetState()),
				instance.getSelectors()
			);
		},
		get resolveSelect() {
			return instance.getResolveSelectors();
		},
	};

	return thunkArgs;
};

const createThunkMiddleware = (thunkArgs, next) => (action) => {
	if (typeof action === 'function') {
		return action(thunkArgs);
	}

	return next(action);
};

export const applyThunkMiddleware = (store) => {
	const originalInstantiate = store.instantiate;

	store.instantiate = (registry) => {
		const instance = originalInstantiate(registry);

		if (!instance.store || !instance.store.dispatch) {
			throw new Error(
				__(
					'The created instance does not contain a valid store.',
					'search-exclude'
				)
			);
		}

		const dispatch = instance.store.dispatch;
		const thunkArgs = createThunkArgs(instance, registry);

		instance.store.dispatch = createThunkMiddleware(thunkArgs, dispatch);

		return instance;
	};

	return store;
};
