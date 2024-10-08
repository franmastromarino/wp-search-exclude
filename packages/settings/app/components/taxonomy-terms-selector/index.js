import { useState, useEffect, useMemo, memo } from '@wordpress/element';
import { useDebounce } from '@wordpress/compose';

import { useTaxonomyTerms } from './helpers';
import MultipleSelector from '../../components/multiple-selector';

const TaxonomyTermsSelector = ({ taxonomy, settings, onChangeSettings }) => {
	const value = settings.taxonomies[taxonomy]?.ids;

	const ids = useMemo(
		() =>
			value
				?.filter((item) => item !== 'all')
				?.map((item) => parseInt(item)),
		[taxonomy, settings.taxonomies]
	);

	const { taxonomyTerms, isResolvingTaxonomyTerms, hasTaxonomyTerms } =
		useTaxonomyTerms({
			taxonomy,
			include: ids,
		});

	const [searchTerm, setSearchTerm] = useState('');
	const [debouncedSearchTerm, setDebouncedSearchTerm] = useState(searchTerm);

	const {
		taxonomyTerms: taxonomyTermsSearch,
		isResolvingTaxonomyTerms: isResolvingTaxonomyTermsSearch,
		hasTaxonomyTerms: hasTaxonomyTermsSearch,
	} = useTaxonomyTerms({
		taxonomy,
		exclude: ids,
		searchTerm: debouncedSearchTerm,
	});

	const updateDebouncedSearchTerm = useDebounce((term) => {
		setDebouncedSearchTerm(term);
	}, 300);

	useEffect(() => {
		updateDebouncedSearchTerm(searchTerm);
	}, [searchTerm, updateDebouncedSearchTerm]);

	const options = useMemo(() => {
		const taxonomiesOptions = [
			...(taxonomyTerms || []),
			...(taxonomyTermsSearch || []),
		].map((item) => {
			return {
				label: item.name,
				value: parseInt(item.id),
			};
		});

		return [{ label: 'All', value: 'all' }, ...taxonomiesOptions];
	}, [taxonomyTerms, taxonomyTermsSearch]);

	return (
		<MultipleSelector
			options={options}
			value={value}
			onChange={(newValues) => {
				onChangeSettings({
					taxonomies: {
						[taxonomy]: {
							ids: newValues,
						},
					},
				});
			}}
			onInputChange={setSearchTerm}
		/>
	);
};

export default memo(TaxonomyTermsSelector);
