/**
 * Custom hook that handle the url adding the current tab to the url
 *
 * @returns {Object} tabParam, setBrowserTabParam
 */
export const handleBrowserParams = () => {
	const href = document.location.href;
	const hrefParams = new URLSearchParams(href);
	const url = new URL(window.location);
	const tabParam = hrefParams.get('tab');
	const setBrowserTabParam = (currentTab) => {
		url.searchParams.set('tab', currentTab);
		window.history.pushState(null, '', url.toString());

		// Compatibility Pro
		window.dispatchEvent(new Event('popstate'));
	};
	return {
		tabParam,
		setBrowserTabParam,
	};
};
