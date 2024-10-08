/**
 * Internal dependencies
 */
import Header from '../../components/header';
import Nav from '../../components/nav';
import { useAppSlotContext } from '../../structure/provider';
import Content from './content';

const Settings = () => {
	const { Fill } = useAppSlotContext();

	return (
		<>
			<Fill.Header>
				<Header />
			</Fill.Header>
			<Fill.Navigation>
				<Nav />
			</Fill.Navigation>
			<Fill.Content>
				<Content />
			</Fill.Content>
		</>
	);
};

export default Settings;
