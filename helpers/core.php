<?php

namespace vnh;

function plugin_languages_path($plugin_file) {
	return dirname(plugin_basename($plugin_file)) . '/languages';
}

function abspath($path) {
	return str_replace(ABSPATH, fs()->abspath(), $path);
}

function get_cached_option($opt_name) {
	$all_options = wp_load_alloptions();

	if (isset($all_options[$opt_name])) {
		return $all_options[$opt_name];
	}

	return false;
}
