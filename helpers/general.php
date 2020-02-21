<?php

function plugin_languages_path($plugin_file) {
	return dirname(plugin_basename($plugin_file)) . '/languages';
}

function flatten_version($version) {
	if (empty($version)) {
		return null;
	}

	$parts = explode('.', $version);

	if (count($parts) === 2) {
		$parts[] = '0';
	}

	return implode('', $parts);
}
