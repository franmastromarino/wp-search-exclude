/**
 * WordPress dependencies
 */

import {
	useContext,
	createContext,
	useRef,
	useCallback,
} from '@wordpress/element';

import { SlotFillProvider, createSlotFill } from '@wordpress/components';

import { useInstanceId } from '@wordpress/compose';

const AppSlotContext = createContext({
	Slot: {
		Header: ({ children }) => children || null,
		Footer: ({ children }) => children || null,
		Content: ({ children }) => children || null,
		Aside: ({ children }) => children || null,
		Navigation: ({ children }) => children || null,
	},
	Fill: {
		Header: ({ children }) => children || null,
		Footer: ({ children }) => children || null,
		Content: ({ children }) => children || null,
		Aside: ({ children }) => children || null,
		Navigation: ({ children }) => children || null,
	},
});

const useAppSlotContext = () => {
	return useContext(AppSlotContext);
};

const AppSlotProvider = (props) => {
	const containerRef = useRef();
	const { children } = props;

	const instanceId = useInstanceId(AppSlotProvider);
	const slotName = `search-exclude.admin.Control.Slot.${instanceId}`;

	const Slot = {};
	const Fill = {};

	const { Fill: FillHeader, Slot: SlotHeader } = useCallback(
		createSlotFill(`${slotName}.header`),
		[slotName]
	);
	const { Fill: FillNavigation, Slot: SlotNavigation } = useCallback(
		createSlotFill(`${slotName}.navigation`),
		[slotName]
	);
	const { Fill: FillContent, Slot: SlotContent } = useCallback(
		createSlotFill(`${slotName}content`),
		[slotName]
	);
	const { Fill: FillAside, Slot: SlotAside } = useCallback(
		createSlotFill(`${slotName}.aside`),
		[slotName]
	);
	const { Fill: FillFooter, Slot: SlotFooter } = useCallback(
		createSlotFill(`${slotName}.footer`),
		[slotName]
	);

	Slot.Header = SlotHeader;
	Fill.Header = FillHeader;

	Slot.Navigation = SlotNavigation;
	Fill.Navigation = FillNavigation;

	Slot.Content = SlotContent;
	Fill.Content = FillContent;

	Slot.Aside = SlotAside;
	Fill.Aside = FillAside;

	Slot.Footer = SlotFooter;
	Fill.Footer = FillFooter;

	return (
		<SlotFillProvider>
			<AppSlotContext.Provider
				value={{
					Slot,
					Fill,
					containerRef,
				}}
			>
				{children}
			</AppSlotContext.Provider>
		</SlotFillProvider>
	);
};

const AppSlotConsumer = AppSlotContext.Consumer;

export { AppSlotProvider, AppSlotConsumer, useAppSlotContext };
