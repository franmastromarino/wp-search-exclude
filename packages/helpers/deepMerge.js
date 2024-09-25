/* eslint-disable camelcase */

function isObject(value) {
	const type = typeof value;
	return value != null && (type == 'object' || type == 'function');
}

export function deepMerge(initialObject, newObject) {
	// Create a new object to avoid mutating the original objects
	const mergedObject = {};

	// Merge keys from both initialObject and newObject
	const allKeys = new Set([
		...Object.keys(initialObject),
		...Object.keys(newObject),
	]);

	allKeys.forEach((key) => {
		if (
			initialObject.hasOwnProperty(key) &&
			newObject.hasOwnProperty(key)
		) {
			// If both objects have the key and the value in the initialObject is an object, recursively merge
			if (
				isObject(initialObject[key]) &&
				!Array.isArray(initialObject[key])
			) {
				mergedObject[key] = deepMerge(
					initialObject[key],
					newObject[key]
				);
			} else {
				// If not an object, prefer the value from newObject
				mergedObject[key] = newObject[key];
			}
		} else if (initialObject.hasOwnProperty(key)) {
			// If the key only exists in initialObject
			mergedObject[key] = initialObject[key];
		} else if (newObject.hasOwnProperty(key)) {
			// If the key only exists in newObject
			mergedObject[key] = newObject[key];
		}
	});

	return mergedObject;
}
