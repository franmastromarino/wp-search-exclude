/**
 * WordPress dependencies
 */

import { __ } from '@wordpress/i18n';
import { applyFilters } from '@wordpress/hooks';

import { createContext, useReducer, useContext } from '@wordpress/element';

/**
 * External dependencies
 */

import { handleBrowserParams } from '../../../helpers/handleBrowserParams';

/**
 * Internal dependencies
 */

import Display from '../tabs/display';
import Settings from '../tabs/settings';

const { tabParam, setBrowserTabParam } = handleBrowserParams();

const getDefaultState = ({ defaultTab }) => {
	return {
		currentTab: tabParam ? tabParam : defaultTab,
		currentTabSection: '',
		prevTab: null,
		prevSubTab: null,
		user: false,
	};
};

const AppContext = createContext({});

const useAppContext = () => {
	return useContext(AppContext);
};

const reducer = (state, action) => {
	switch (action.type) {
		case 'SET_CURRENT_TAB': {
			return {
				...state,
				...action.payload,
				prevTab: state.currentTab,
			};
		}
	}
	return state;
};

const AppProvider = ({ children }) => {
	const TABS = applyFilters('search-exclude.app.tabs', [
		{
			label: __('Visibility', 'search-exclude'),
			name: 'display',
			content: Display,
		},
		{
			label: __('Settings', 'search-exclude'),
			name: 'settings',
			content: Settings,
		},
	]);

	const [state, dispatch] = useReducer(
		reducer,
		getDefaultState({ defaultTab: TABS[0].name })
	);

	const setCurrentTab = (currentTab) => {
		if (state.currentTab == currentTab) {
			return;
		}

		setBrowserTabParam(currentTab);

		dispatch({
			type: 'SET_CURRENT_TAB',
			payload: {
				currentTab,
			},
		});
	};

	return (
		<AppContext.Provider
			value={{
				...state,
				setCurrentTab,
				tabs: TABS,
			}}
		>
			{children}
		</AppContext.Provider>
	);
};

const AppConsumer = AppContext.Consumer;

export { AppProvider, AppConsumer, useAppContext };
