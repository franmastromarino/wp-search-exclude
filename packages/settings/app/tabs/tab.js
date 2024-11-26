// import 'react-phone-number-input/style.css';
// import PhoneInput from 'react-phone-number-input';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Spinner } from '@wordpress/components';
import { usePrevious } from '@wordpress/compose';
import { useState, useMemo, memo } from '@wordpress/element';
import { isObjectsEqual } from '../../../helpers/isObjectsEqual';

const Tab = (props) => {
	const [isSaving, setIsSaving] = useState(false);

	const { children, className, onSubmit, settings } = props;

	const prevSettings = usePrevious(settings);

	const isModified = useMemo(() => {
		if (!prevSettings) return false;

		return !isObjectsEqual(settings, prevSettings);
	}, [settings, prevSettings]);

	return (
		<div className="wrap about-wrap full-width-layout qlwrap">
			<form
				onSubmit={async (e) => {
					e.preventDefault();
					setIsSaving(true);
					await onSubmit();
					setIsSaving(false);
				}}
			>
				{children}
				<p className="submit">
					<button
						type="submit"
						className="button button-primary"
						disabled={!isModified}
					>
						{__('Save', 'search-exclude')}
					</button>
					<span className="settings-save-status">
						{isSaving && <Spinner />}
					</span>
				</p>
			</form>
		</div>
	);
};

export default memo(Tab);
