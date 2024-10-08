/**
 * Internal dependencies
 */

import { useAppContext } from '../provider';

export const AppTabSwitcher = () => {
	const { currentTab, tabs } = useAppContext();

	const tab = tabs.find(({ name }) => name == currentTab);

	if (!tab) {
		return <>{currentTab}</>;
	}

	const Content = tab.content;

	return <Content />;
};
