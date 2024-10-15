/**
 * Internal dependencies
 */
import { INITIAL_STATE } from './constants';

export default function settings(state = INITIAL_STATE, action) {
	switch (action.type) {
		case 'SET_SETTINGS':
			return action.payload(state);
	}
	return state;
}
