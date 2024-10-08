const {
	QLSE_PLUGIN_URL,
	// eslint-disable-next-line no-undef
} = qlseSettings;

export function getPluginURL(url) {
	// eslint-disable-next-line no-undef
	return QLSE_PLUGIN_URL + url;
}
