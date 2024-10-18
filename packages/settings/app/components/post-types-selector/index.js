import { useState, useEffect, useMemo, memo } from '@wordpress/element';
import { useDebounce } from '@wordpress/compose';

import { usePostTypes } from './helpers';
import MultipleSelector from '../../components/multiple-selector';

const PostTypesSelector = ({ postType, settings, onChangeSettings }) => {
	const value = settings.entries[postType]?.ids;

	const ids = useMemo(
		() =>
			value
				?.filter((item) => item !== 'all')
				?.map((item) => parseInt(item)),
		[postType, settings.entries]
	);

	const { postTypes, isResolvingPostTypes, hasPostTypes } = usePostTypes({
		postType,
		include: ids,
	});

	const [searchTerm, setSearchTerm] = useState('');
	const [debouncedSearchTerm, setDebouncedSearchTerm] = useState(searchTerm);

	const {
		postTypes: postTypesSearch,
		isResolvingPostTypes: isResolvingPostTypesSearch,
		hasPostTypes: hasPostTypesSearch,
	} = usePostTypes({
		postType,
		exclude: ids,
		searchTerm: debouncedSearchTerm,
	});

	console.log('postTypesSearch: ', postTypesSearch);

	const updateDebouncedSearchTerm = useDebounce((term) => {
		setDebouncedSearchTerm(term);
	}, 300);

	useEffect(() => {
		updateDebouncedSearchTerm(searchTerm);
	}, [searchTerm, updateDebouncedSearchTerm]);

	const options = useMemo(() => {
		const postTypesOptions = [
			...(postTypes || []),
			...(postTypesSearch || []),
		].map((item) => {
			return {
				label: item.title?.rendered,
				value: parseInt(item.id),
			};
		});
		return [{ label: 'All', value: 'all' }, ...postTypesOptions];
	}, [postTypes, postTypesSearch]);

	return (
		<MultipleSelector
			options={options}
			value={value}
			onChange={(newValues) => {
				onChangeSettings({
					entries: {
						[postType]: {
							ids: newValues,
						},
					},
				});
			}}
			onInputChange={setSearchTerm}
		/>
	);
};

export default memo(PostTypesSelector);
