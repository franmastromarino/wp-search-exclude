/**
 * WordPress dependencies
 */
import { combineReducers } from '@wordpress/data';
/**
 * Internal dependencies
 */
import { INITIAL_STATE } from './constants';

export function display(state = INITIAL_STATE.display, action) {
	switch (action.type) {
		case 'SET_DISPLAY':
			return action.payload(state);
	}
	return state;
}
export function excluded(state = INITIAL_STATE.excluded, action) {
	switch (action.type) {
		case 'SET_EXCLUDED':
			return action.payload(state);
	}
	return state;
}

export default combineReducers({
	display,
	excluded,
});
