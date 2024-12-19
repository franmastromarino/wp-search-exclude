/**
 * WordPress dependencies
 */
import { useSelect, useDispatch } from '@wordpress/data';
import { store as editorStore } from '@wordpress/editor';
import { store as coreStore } from '@wordpress/core-data';

export const useExcludeMeta = (props = {}) => {
	const { meta, setMeta } = useCurrentPostMeta(props);
	const setExclude = (newValue) => {
		setMeta({
			_exclude: newValue,
		});
	};
	return {
		exclude: meta?._exclude,
		setExclude,
	};
};

export const useCurrentPostMeta = (props = {}) => {
	const { context = {} } = props;
	const { meta, postType, postId } = useSelect(
		(select) => {
			const { getEditedEntityRecord } = select(coreStore);
			const { getCurrentPostId, getCurrentPostType } =
				select(editorStore);
			const {
				postType = getCurrentPostType(),
				postId = getCurrentPostId(),
			} = context;
			const post = getEditedEntityRecord('postType', postType, postId);
			return {
				meta: post?.meta,
				postType,
				postId,
			};
		},
		[context]
	);
	const { editEntityRecord } = useDispatch(coreStore);
	const setMeta = (newValue) => {
		editEntityRecord('postType', postType, postId, {
			meta: {
				...meta,
				...newValue,
			},
		});
	};
	return {
		meta,
		setMeta,
	};
};

// export const useValidPostTypes = () => {
// 	return useSelect(
// 		(select) => {
// 			const { getPostTypes, isResolving, hasFinishedResolution } =
// 				select(coreStore);

// 			const query = { per_page: -1 };
// 			const postTypes = getPostTypes(query);

// 			const isResolvingPostTypes = isResolving('getPostTypes', [query]);
// 			const hasResolvedPostTypes = hasFinishedResolution('getPostTypes', [
// 				query,
// 			]);

// 			if (isResolvingPostTypes || !hasResolvedPostTypes || !postTypes) {
// 				return {
// 					validPostTypes: [],
// 					isResolvingPostTypes: true,
// 					hasResolvedPostTypes: false,
// 				};
// 			}

// 			// Filter out post types that are not public or are internal
// 			const validPostTypes = postTypes.filter(
// 				(postType) =>
// 					postType.viewable && !postType.slug.startsWith('wp_')
// 			);

// 			return {
// 				validPostTypes,
// 				isResolvingPostTypes: false,
// 				hasResolvedPostTypes: true,
// 			};
// 		},
// 		[] // No dependencies; runs once unless select dependencies change
// 	);
// };

// export const usePostsByIdsAnyPostType = (ids = [], validPostTypes = []) => {
// 	// Call useValidPostTypes unconditionally
// 	const {
// 		validPostTypes: fetchedPostTypes,
// 		isResolvingPostTypes,
// 		hasResolvedPostTypes,
// 	} = useValidPostTypes();

// 	// Decide which validPostTypes to use
// 	const resolvedValidPostTypes = validPostTypes.length
// 		? validPostTypes
// 		: fetchedPostTypes;

// 	// Now use useSelect to fetch the posts
// 	const { postsData, isResolvingPosts, hasResolvedPosts } = useSelect(
// 		(select) => {
// 			const { getEntityRecords, isResolving, hasFinishedResolution } =
// 				select(coreStore);

// 			// Check if post types are resolving
// 			if (
// 				isResolvingPostTypes ||
// 				!hasResolvedPostTypes ||
// 				!resolvedValidPostTypes.length
// 			) {
// 				return {
// 					postsData: [],
// 					isResolvingPosts: true,
// 					hasResolvedPosts: false,
// 				};
// 			}

// 			// Initialize variables to collect posts and resolution states
// 			let allPosts = [];
// 			let isResolvingAllPosts = false;
// 			let hasResolvedAllPosts = true;

// 			// Loop through each valid post type and fetch posts with the given IDs
// 			resolvedValidPostTypes.forEach((postType) => {
// 				const batchSize = 50;
// 				const postBatches = [];

// 				//split ids quantity into smaller batch, to prevent overloading the url (400 Bad Request)
// 				for (let i = 0; i < ids.length; i += batchSize) {
// 					postBatches.push(ids.slice(i, i + batchSize));
// 				}

// 				postBatches.forEach((idsBatch) => {
// 					const query = {
// 						include: idsBatch,
// 						per_page: idsBatch.length, // ids.length is at least 1 here
// 						_fields: ['id', 'title', 'date', 'link', 'type'],
// 					};

// 					const posts = getEntityRecords(
// 						'postType',
// 						postType.slug,
// 						query
// 					);

// 					const isResolvingCurrent = isResolving('getEntityRecords', [
// 						'postType',
// 						postType.slug,
// 						query,
// 					]);

// 					const hasResolvedCurrent = hasFinishedResolution(
// 						'getEntityRecords',
// 						['postType', postType.slug, query]
// 					);

// 					// Update resolving states
// 					isResolvingAllPosts =
// 						isResolvingAllPosts || isResolvingCurrent;
// 					hasResolvedAllPosts =
// 						hasResolvedAllPosts && hasResolvedCurrent;

// 					if (posts && posts.length) {
// 						allPosts = allPosts.concat(
// 							posts.map(({ id, title, date, link, type }) => ({
// 								id,
// 								title: title.rendered,
// 								date,
// 								link,
// 								postType: type,
// 							}))
// 						);
// 					}
// 				});
// 			});
// 			return {
// 				postsData: allPosts,
// 				isResolvingPosts: isResolvingAllPosts,
// 				hasResolvedPosts: hasResolvedAllPosts,
// 			};
// 		},
// 		[ids.join(','), resolvedValidPostTypes.map((pt) => pt.slug).join(',')] // Dependencies
// 	);

// 	const hasPosts = !isResolvingPosts && postsData.length > 0;

// 	return {
// 		posts: postsData,
// 		isResolvingPosts,
// 		hasPosts,
// 		hasResolvedPosts,
// 	};
// };
