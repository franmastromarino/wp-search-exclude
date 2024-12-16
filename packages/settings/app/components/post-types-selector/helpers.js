import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

export const usePostTypes = ({
	postType = 'page',
	limit = 50,
	page = 1,
	searchTerm = undefined,
	include = undefined,
	exclude = undefined,
} = {}) => {
	return useSelect(
		(select) => {
			if (!searchTerm && (!include || include.length === 0)) {
				return {
					postTypes: [],
					isResolvingPostTypes: false,
					hasPostTypes: false,
				};
			}
			const { getEntityRecords, isResolving } = select(coreStore);

			// Build the query parameters
			const query = {
				per_page: limit,
				page,
				_fields: 'id,title.rendered',
			};

			// If we have IDs, include them in the query
			if (typeof searchTerm !== 'undefined') {
				// check if include is array and not empty
				if (typeof searchTerm !== 'string' || !searchTerm) {
					return {
						postTypes: [],
						isResolvingPostTypes: false,
						hasPostTypes: false,
					};
				}
				query.search = searchTerm;
			}

			// If we have IDs, include them in the query
			if (typeof include !== 'undefined') {
				// check if include is array and not empty
				if (!Array.isArray(include) || !include?.length) {
					return {
						postTypes: [],
						isResolvingPostTypes: false,
						hasPostTypes: false,
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

			const params = ['postType', postType, query];

			const postTypes = getEntityRecords(...params);

			const isResolvingPostTypes = isResolving(
				'getEntityRecords',
				params
			);

			const hasPostTypes = !isResolvingPostTypes && !!postTypes?.length;

			return {
				postTypes,
				isResolvingPostTypes,
				hasPostTypes,
			};
		},
		[postType, limit, searchTerm, include, exclude]
	);
};
