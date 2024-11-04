import { useState, useEffect, useMemo, memo, useRef } from '@wordpress/element';
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

	const prevAuthors = useRef(null);

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
		if (authors) {
			prevAuthors.current = authors;
		}
	}, [authors]);

	useEffect(() => {
		updateDebouncedSearchTerm(searchTerm);
	}, [searchTerm, updateDebouncedSearchTerm]);

	const options = useMemo(() => {
		const currentAuthors = authors || prevAuthors.current || [];
		const authorsOptions = [
			...(currentAuthors || []),
			...(authorsSearch || []),
		].map((item) => {
			return {
				label: item.name,
				value: parseInt(item.id),
			};
		});

		return authorsOptions;
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
