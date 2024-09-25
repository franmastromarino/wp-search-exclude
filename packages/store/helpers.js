/**
 * External dependencies
 */
import { apiFetch } from '../helpers/apiFetch.js';
/**
 * Wordpress dependencies
 */
import { useSelect, useDispatch } from '@wordpress/data';
import { addQueryArgs } from '@wordpress/url';
/**
 * Internal dependencies
 */
import { STORE_NAME } from './constants';

// eslint-disable-next-line no-undef
export const { QLSE_REST_ROUTES, WP_VERSION } = qlseStore;

export const FIRST_WP_VERSION_WITH_THUNK_SUPPORT = '6.0';

export const fetchRestApiSettings = ({ method, data, route } = {}) => {
	const path =
		method === 'GET'
			? addQueryArgs(QLSE_REST_ROUTES[route], data)
			: QLSE_REST_ROUTES[route];

	return apiFetch({
		path,
		method,
		data,
	});
};

export function useDisplaySettings() {
	const { setSettingsDisplay, saveDisplaySettings } = useDispatch(STORE_NAME);

	const {
		settingsDisplay,
		isResolvingSettingsDisplay,
		hasResolvedSettingsDisplay,
	} = useSelect((select) => {
		const { getSettingsDisplay, isResolving, hasFinishedResolution } =
			select(STORE_NAME);

		return {
			settingsDisplay: getSettingsDisplay(),
			isResolvingSettingsDisplay: isResolving('getSettingsDisplay'),
			hasResolvedSettingsDisplay:
				hasFinishedResolution('getSettingsDisplay'),
		};
	}, []);

	return {
		settingsDisplay,
		isResolvingSettingsDisplay,
		hasResolvedSettingsDisplay,
		hasSettingsDisplay: !!(
			hasResolvedSettingsDisplay && Object.keys(settingsDisplay)?.length
		),
		saveDisplaySettings,
		setSettingsDisplay,
	};
}

export function useExcludedSettings() {
	const { setSettingsExcluded, saveExcludedSettings } =
		useDispatch(STORE_NAME);

	const {
		settingsExcluded,
		isResolvingSettingsExcluded,
		hasResolvedSettingsExcluded,
	} = useSelect((select) => {
		const { getSettingsExcluded, isResolving, hasFinishedResolution } =
			select(STORE_NAME);

		return {
			settingsExcluded: getSettingsExcluded(),
			isResolvingSettingsExcluded: isResolving('getSettingsExcluded'),
			hasResolvedSettingsExcluded: hasFinishedResolution(
				'getSettingsExcluded'
			),
		};
	}, []);

	return {
		settingsExcluded,
		isResolvingSettingsExcluded,
		hasResolvedSettingsExcluded,
		hasSettingsExcluded: !!(
			hasResolvedSettingsExcluded && Object.keys(settingsExcluded)?.length
		),
		saveExcludedSettings,
		setSettingsExcluded,
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
					'insta-gallery'
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
