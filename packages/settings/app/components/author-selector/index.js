import { useState, useEffect, useMemo, memo } from '@wordpress/element';
import { useDebounce } from '@wordpress/compose';

import { useAuthor } from './helpers';
import MultipleSelector from '../multiple-selector';

const AuthorSelector = ({ settings, onChangeSettings, disabled }) => {
	const value = settings.author?.ids;

	const ids = useMemo(
		() => value?.map((item) => parseInt(item)),
		[settings.author]
	);

	const { authors, isResolvingAuthors, hasAuthors } = useAuthor({
		include: ids,
	});

	const [searchTerm, setSearchTerm] = useState('');
	const [debouncedSearchTerm, setDebouncedSearchTerm] = useState(searchTerm);

	const {
		authors: authorsSearch,
		isResolvingAuthors: isResolvingAuthorsSearch,
		hasAuthors: hasAuthorsSearch,
	} = useAuthor({
		exclude: ids,
		searchTerm: debouncedSearchTerm,
	});

	const updateDebouncedSearchTerm = useDebounce((term) => {
		setDebouncedSearchTerm(term);
	}, 300);

	useEffect(() => {
		updateDebouncedSearchTerm(searchTerm);
	}, [searchTerm, updateDebouncedSearchTerm]);

	const options = useMemo(() => {
		const taxonomiesOptions = [
			...(authors || []),
			...(authorsSearch || []),
		].map((item) => {
			return {
				label: item.name,
				value: parseInt(item.id),
			};
		});

		return taxonomiesOptions;
	}, [authors, authorsSearch]);

	return (
		<MultipleSelector
			options={options}
			value={value}
			onChange={(newValues) => {
				onChangeSettings({
					author: {
						ids: newValues,
					},
				});
			}}
			onInputChange={setSearchTerm}
			disabled={disabled}
		/>
	);
};

export default memo(AuthorSelector);
