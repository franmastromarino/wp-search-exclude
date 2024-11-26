/**
 * External dependencies
 */
import classnames from 'classnames';
/**
 * WordPress dependencies
 */
// import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { useAppContext } from '../../provider';
import ItemTooltip from '../item-tooltip';

// import { activeSubmenuItems, hideSubmenus } from './helpers';
// import { useButtonSettings } from '../../../store/settings';
// import { activeSubmenuItems, hideSubmenus } from './helpers';

function Nav() {
	const { currentTab, setCurrentTab, tabs } = useAppContext();

	// const { settingsButton } = useButtonSettings();

	// useEffect(() => {
	// 	activeSubmenuItems(currentTab);
	// }, [currentTab]);

	// useEffect(() => {
	// 	hideSubmenus(settingsButton);
	// }, [settingsButton]);

	return (
		<div className="wrap about-wrap full-width-layout">
			<ul className="nav-tab-wrapper">
				{tabs
					// .filter(({ name }) => {
					// 	if (
					// 		['box', 'contacts'].includes(name) &&
					// 		'yes' !== settingsButton.box
					// 	) {
					// 		return false;
					// 	}
					// 	return true;
					// })
					.map(({ label, name }) => {
						return (
							<li
								key={name}
								className="qlse__nav-tab-li"
								onClick={(e) => {
									e.preventDefault();
									e.stopPropagation();
									setCurrentTab(name);
								}}
							>
								<a
									href="#"
									className={classnames(
										'nav-tab',
										currentTab === name && 'nav-tab-active'
									)}
									onClick={(e) => {
										e.preventDefault();
									}}
								>
									{label}
									<ItemTooltip>
										<>
											<h4>
												{__(
													'Exclude options:',
													'search-exclude'
												)}
											</h4>
											<p>
												{__(
													'Customize the exclusion settings by post type, taxonomy, or author. Use the selector to search and choose specific IDs to exclude, or opt for the "All" option to exclude every item.',
													'search-exclude'
												)}
											</p>
											<hr />
											<p>
												{__(
													'Note: Enabling the "All" option will not update the status in the "Search Excluded" column. While the post may appear as not excluded, it is indeed excluded.',
													'search-exclude'
												)}
											</p>
										</>
									</ItemTooltip>
								</a>
							</li>
						);
					})}
			</ul>
		</div>
	);
}

export default Nav;
