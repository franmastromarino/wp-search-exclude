export const STORE_NAME = 'qlse/settings';

export const INITIAL_STATE = {
	display: {
		target: {
			include: 1,
			ids: [],
		},
		entries: {
			post: {
				include: 1,
				ids: [],
			},
			page: {
				include: 1,
				ids: [],
			},
		},
		taxonomies: {
			category: {
				include: 1,
				ids: [],
			},
			tags: {
				include: 1,
				ids: [],
			},
			product_cat: {
				include: 1,
				ids: [],
			},
		},
	},
	excluded: [],
};
