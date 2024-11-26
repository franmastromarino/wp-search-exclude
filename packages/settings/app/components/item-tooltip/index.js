/**
 * External dependencies
 */
import classnames from 'classnames';
/**
 * Wordpress dependencies
 */
import { Popover } from '@wordpress/components';
import { useState } from '@wordpress/element';

import { Ask } from './icons/ask';

const ItemTooltip = ({
	shift = true,
	offset = 5,
	initialOpen = false,
	preventClose = false,
	disabled,
	text = '',
	displayDefaultPopoverContainer = true,
	hideIcon = false,
	children,
	className,
	placement = 'right',
	noArrow = false,
}) => {
	const [isOpen, setIsOpen] = useState(initialOpen);

	// Handle mouse over and mouse leave events
	const handleMouseEnter = () => {
		if (!disabled) {
			setIsOpen(true);
		}
	};

	const handleMouseLeave = () => {
		if (!preventClose) {
			setIsOpen(false);
		}
	};

	//if children is empty
	if (children?.props?.children?.length === 0) {
		return null;
	}

	return (
		<div
			className={classnames(
				'qlse__components__tooltip',
				className,
				disabled && 'qlse__components__tooltip--disabled'
			)}
		>
			<div
				className="qlse__components__tooltip__button"
				onMouseEnter={handleMouseEnter}
			>
				{!!text && (
					<span className="qlse__components__tooltip__button__text">
						{text}
					</span>
				)}
				{!hideIcon && <Ask />}
			</div>
			{isOpen && (
				<Popover
					shift={shift}
					offset={offset}
					noArrow={noArrow}
					placement={placement ? placement : undefined}
					focusOnMount="false"
					className={classnames(
						'qlse__components__tooltip__popover',
						className && className + '__popover'
					)}
					onClose={handleMouseLeave}
					onMouseLeave={handleMouseLeave}
				>
					{displayDefaultPopoverContainer ? (
						<div>{children}</div>
					) : (
						children
					)}
				</Popover>
			)}
		</div>
	);
};

export default ItemTooltip;
