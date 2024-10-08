export const formatDate = (dateString) => {
	const date = new Date(dateString);

	const year = date.getFullYear();
	const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
	const day = String(date.getDate()).padStart(2, '0');
	let hours = date.getHours();
	const minutes = String(date.getMinutes()).padStart(2, '0');

	// Determine AM or PM
	const ampm = hours >= 12 ? 'pm' : 'am';
	hours = hours % 12 || 12; // Convert to 12-hour format

	// Format the date
	const formattedDate = `${year}/${month}/${day} at ${hours}:${minutes} ${ampm}`;

	return formattedDate;
};
