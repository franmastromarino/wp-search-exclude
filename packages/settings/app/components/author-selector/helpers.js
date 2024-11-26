import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

export const useAuthor = ({
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
						authors: [],
						isResolvingAuthors: false,
						hasAuthors: false,
					};
				}
				query.search = searchTerm;
			}

			// If we have IDs, include them in the query
			if (typeof include !== 'undefined') {
				// check if include is array and not empty
				if (!Array.isArray(include) || !include?.length) {
					return {
						authors: [],
						isResolvingAuthors: false,
						hasAuthors: false,
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

			const params = ['root', 'user', query];
			const authors = getEntityRecords(...params);
			const isResolvingAuthors = isResolving('getEntityRecords', params);
			const hasAuthors = !isResolvingAuthors && !!authors?.length;

			return {
				authors,
				isResolvingAuthors,
				hasAuthors,
			};
		},
		[limit, searchTerm, include, exclude]
	);
};
