import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

export const useTaxonomyTerms = ({
	taxonomy,
	limit = 50,
	searchTerm = undefined,
	include = undefined,
	exclude = undefined,
} = {}) => {
	return useSelect(
		(select) => {
			const { getEntityRecords, isResolving } = select(coreStore);

			// Build the query parameters
			const query = {
				per_page: limit,
			};

			// If we have IDs, include them in the query
			if (typeof searchTerm !== 'undefined') {
				// check if include is array and not empty
				if (typeof searchTerm !== 'string' || !searchTerm) {
					return {
						taxonomyTerms: [],
						isResolvingTaxonomyTerms: false,
						hasTaxonomyTerms: false,
					};
				}
				query.search = searchTerm;
			}

			// If we have IDs, include them in the query
			if (typeof include !== 'undefined') {
				// check if include is array and not empty
				if (!Array.isArray(include) || !include?.length) {
					return {
						taxonomyTerms: [],
						isResolvingTaxonomyTerms: false,
						hasTaxonomyTerms: false,
					};
				}
				query.include = include.join(',');
			}

			// If we have IDs, exclude them in the query
			if (typeof exclude !== 'undefined') {
				if (Array.isArray(exclude) && !!exclude?.length) {
					query.exclude = exclude.join(',');
				}
			}

			const params = ['taxonomy', taxonomy, query];
			const taxonomyTerms = getEntityRecords(...params);
			const isResolvingTaxonomyTerms = isResolving(
				'getEntityRecords',
				params
			);
			const hasTaxonomyTerms =
				!isResolvingTaxonomyTerms && !!taxonomyTerms?.length;

			return {
				taxonomyTerms,
				isResolvingTaxonomyTerms,
				hasTaxonomyTerms,
			};
		},
		[taxonomy, limit, searchTerm, include, exclude]
	);
};
