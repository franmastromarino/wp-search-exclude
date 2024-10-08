/**
 * External dependencies
 */
import classnames from 'classnames';
/**
 * WordPress dependencies
 */
// import { useEffect } from '@wordpress/element';
/**
 * Internal dependencies
 */
import { useAppContext } from '../../provider';
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
								</a>
							</li>
						);
					})}
			</ul>
		</div>
	);
}

export default Nav;
