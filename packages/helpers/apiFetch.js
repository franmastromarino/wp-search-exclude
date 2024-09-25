import wpApiFetch from '@wordpress/api-fetch';

/**
 * Ensure the correct error handling for all endpoints and custom hooks
 *
 * @param {*} url
 * @returns response or Error
 */

export async function apiFetch(args) {
	return await wpApiFetch(args)
		.then((response) => {
			if (response.code) {
				throw response;
			}
			return response;
		})
		.catch((error) => {
			if (error?.data?.params && error?.data?.status) {
				return {
					code: error.data.status,
					message: Object.values(error.data.params)[0],
				};
			}
			return {
				code: error?.code ? error.code : 'Unknown',
				message: error?.message ? error.message : 'Unknown',
			};
		});
}
