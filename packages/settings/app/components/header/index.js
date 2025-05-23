/**
 * External dependencies
 */
import {
	QLSE_PLUGIN_NAME,
	QLSE_DOCUMENTATION_URL,
} from '../../../../helpers/constants';
/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { getPluginURL } from '../../../../helpers/getPluginURL';

const Header = () => {
	return (
		<div className="wrap about-wrap full-width-layout">
			<h1>{QLSE_PLUGIN_NAME}</h1>
			<p className="about-text">
				{sprintf(
					__(
						'Thanks for using %s! We will do our best to offer you the best and improved communication experience with your users.',
						'search-exclude!'
					),
					QLSE_PLUGIN_NAME
				)}
			</p>
			<p className="about-text">
				<a href="https://quadlayers.com/" target="__blank">
					{__('Quadlayers', 'search-exclude')}
				</a>
				|
				<a href={QLSE_DOCUMENTATION_URL} target="__blank">
					{__('Documentation', 'search-exclude')}
				</a>
			</p>
			<a
				href="https://quadlayers.com/?utm_source=qlse_admin"
				target="_blank"
				rel="noreferrer"
			>
				<div
					style={{
						backgroundImage: `url(${getPluginURL(
							'/assets/backend/img/quadlayers.jpg'
						)})`,
					}}
					className="wp-badge qlse_quadlayers__logo"
				>
					QuadLayers
				</div>
			</a>
		</div>
	);
};

export default Header;
