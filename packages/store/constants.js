export const STORE_NAME = 'qlwapp/menu/store';

export const INITIAL_STATE = {
	display: {
		devices: 'all',
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
