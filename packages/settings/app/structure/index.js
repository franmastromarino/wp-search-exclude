/**
 * Internal dependencies
 */
import { AppSlotConsumer, AppSlotProvider } from './provider';

export const AppStructure = ({ children }) => {
	return (
		<AppSlotProvider>
			<AppSlotConsumer>
				{({ Slot, containerRef }) => {
					return (
						<div ref={containerRef}>
							<header>
								<Slot.Header />
							</header>
							<div>
								<nav>
									<Slot.Navigation />
								</nav>
								<div>
									<div>
										<Slot.Content />
									</div>
									<aside>
										<Slot.Aside />
									</aside>
								</div>
								<footer>
									<Slot.Footer />
								</footer>
							</div>
							{children}
						</div>
					);
				}}
			</AppSlotConsumer>
		</AppSlotProvider>
	);
};
