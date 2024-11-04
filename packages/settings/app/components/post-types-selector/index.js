import { useState, useEffect, useMemo, memo, useRef } from '@wordpress/element';
import { useDebounce } from '@wordpress/compose';

import { usePostTypes } from './helpers';
import MultipleSelector from '../../components/multiple-selector';

const PostTypesSelector = ({
	postType,
	settings,
	onChangeSettings,
	disabled,
}) => {
	const value = settings.entries[postType]?.ids;

	const ids = useMemo(
		() => value?.map((item) => parseInt(item)),
		[postType, settings.entries]
	);

	const { postTypes, isResolvingPostTypes, hasPostTypes } = usePostTypes({
		postType,
		include: ids,
	});

	const prevPostTypes = useRef(null);

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

	const updateDebouncedSearchTerm = useDebounce((term) => {
		setDebouncedSearchTerm(term);
	}, 300);

	useEffect(() => {
		if (postTypes) {
			prevPostTypes.current = postTypes;
		}
	}, [postTypes]);

	useEffect(() => {
		updateDebouncedSearchTerm(searchTerm);
	}, [searchTerm, updateDebouncedSearchTerm]);

	const options = useMemo(() => {
		const currentPostTypes = postTypes || prevPostTypes.current || [];
		const postTypesOptions = [
			...(currentPostTypes || []),
			...(postTypesSearch || []),
		].map((item) => {
			return {
				label: item.title?.rendered,
				value: parseInt(item.id),
			};
		});
		return postTypesOptions;
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
			disabled={disabled}
		/>
	);
};

export default memo(PostTypesSelector);
