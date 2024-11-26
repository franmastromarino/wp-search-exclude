/**
 * add the .current class to wordpress submenu elements comparing the current tab with the submenu item inner text.
 *
 * @param {string} currentTab
 */

export const activeSubmenuItems = (currentTab) => {
	const menu = Array.from(
		document.querySelector('#toplevel_page_search-exclude .wp-submenu')
			.children
	);
	// capitalize the first letter of the string
	const CapitalizedFirstLetterString =
		currentTab[0].toUpperCase() + currentTab.slice(1).toLowerCase();

	menu.forEach((nav) => {
		if (nav.innerText.trim() === CapitalizedFirstLetterString) {
			nav.classList.add('current');
		} else {
			nav.classList.remove('current');
		}
	});
};

export const hideSubmenus = (settingsModules = {}) => {
	const menu = Array.from(
		document.querySelector('#toplevel_page_search-exclude .wp-submenu')
			.children
	);

	menu.forEach((nav) => {
		const navName = nav.innerText.trim().toLowerCase();

		if (['box', 'contacts'].includes(navName)) {
			if (settingsModules.box === 'no') {
				nav.style.display = 'none';
			} else {
				nav.style.display = 'block';
			}
		}
	});
};
