import { FormTokenField, Spinner } from '@wordpress/components';
import { memo } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';

export const MultipleSelector = ({
	options = [],
	value: _value,
	isLoading,
	onChange,
	label = '',
	multiple = true,
	onInputChange,
	placeholder,
}) => {
	// Convert values ids to int if is numeric
	const value = _value?.map((v) => {
		if (!isNaN(v)) {
			return parseInt(v);
		}
		return v;
	});

	// Remove values from suggestions and return array of suggestions labels
	const suggestions = options
		.filter((s) => !value?.includes(s.value))
		.map((s) => s.label);
	// Convert labels to values
	const handleChange = (newLabels) => {
		const values = options
			.map((s) => {
				if (newLabels.includes(s.label)) {
					return s.value;
				}
			})
			.filter((s) => !!s);

		onChange(values);
	};
	// Convert values to labels
	const labels = options
		.map((option) => {
			if (value?.includes(option.value)) {
				return option.label || option.value;
			}
			return null;
		})
		.filter((s) => !!s);

	return (
		<div className="qlse__multiple-selector">
			<FormTokenField
				value={labels}
				onChange={handleChange}
				suggestions={suggestions}
				label={label}
				__experimentalShowHowTo={false}
				multiple={multiple}
				placeholder={placeholder}
				onInputChange={onInputChange}
			/>
			{isLoading && (
				<div className="qlse__multiple-selector--loading">
					<Spinner />
				</div>
			)}
		</div>
	);
};

export default memo(MultipleSelector);
