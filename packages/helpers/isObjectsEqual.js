/**
 * Compare two objects without taking in consideration the order of the keys, also add support for nested objects.
 *
 * Returns true if the two arrays or objects are shallow equal, or false
 * otherwise. Also handles primitive values, just in case.
 *
 * @param {unknown} objA First object or array to compare.
 * @param {unknown} objB Second object or array to compare.
 *
 * @return {boolean} Whether the two values are shallow equal.
 */

export function isObjectsEqual(objA, objB) {
	if (objA === objB) {
		return true;
	}

	if (
		typeof objA !== 'object' ||
		objA === null ||
		typeof objB !== 'object' ||
		objB === null
	) {
		return false;
	}

	const keysA = Object.keys(objA).sort();
	const keysB = Object.keys(objB).sort();

	if (keysA.length !== keysB.length) {
		return false;
	}

	// Compare the sorted keys
	for (let i = 0; i < keysA.length; i++) {
		if (keysA[i] !== keysB[i]) {
			return false;
		}
	}

	// Compare the values of the keys in objA to their counterparts in objB
	for (const key of keysA) {
		const valA = objA[key];
		const valB = objB[key];

		if (
			typeof valA === 'object' &&
			valA !== null &&
			typeof valB === 'object' &&
			valB !== null
		) {
			// Recursive call for nested objects
			if (!isObjectsEqual(valA, valB)) {
				return false;
			}
		} else if (valA !== valB) {
			return false;
		}
	}

	return true;
}
