export const STORE_NAME = 'qlse/settings';

export const INITIAL_STATE = {
	target: {
		all: false,
		ids: [],
	},
	entries: {
		post: {
			all: false,
			ids: [],
		},
		page: {
			all: false,
			ids: [],
		},
	},
	taxonomies: {
		category: {
			all: false,
			ids: [],
		},
		tags: {
			all: false,
			ids: [],
		},
		product_cat: {
			all: false,
			ids: [],
		},
	},
	excluded: [],
};
