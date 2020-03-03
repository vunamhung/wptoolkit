<?php

namespace vnh;

function get_support($feature, $default = []) {
	if (empty(get_theme_support($feature)[0])) {
		return $default;
	}

	return get_theme_support($feature)[0];
}

function strip_namespace($class) {
	$index = strrchr($class, '\\');
	if ($index) {
		return substr($index, 1);
	}

	return $class;
}

function human_time_diff_maybe($timestamp, $day = 1) {
	$how_long = DAY_IN_SECONDS * $day;

	if (abs(time() - $timestamp) < $how_long) {
		/* translators: %s: human time */
		return sprintf(__('%s ago', 'vnh_textdomain'), human_time_diff($timestamp));
	}

	return date(get_option('date_format'), $timestamp);
}

function get_mod($setting, $default = false) {
	if (class_exists('Kirki')) {
		return \Kirki::get_option(THEME_SLUG, $setting);
	}

	return get_theme_mod($setting, $default);
}

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
