<?php

namespace vnh;

function theme_slug() {
	return apply_filters('vnh/theme_slug', get_option('template'));
}

function get_theme_info($header) {
	return wp_get_theme(theme_slug())->get($header);
}

define(__NAMESPACE__ . '\THEME_SLUG', theme_slug());
define(__NAMESPACE__ . '\THEME_NAME', get_theme_info('Name'));
define(__NAMESPACE__ . '\THEME_VERSION', get_theme_info('Version'));
define(__NAMESPACE__ . '\THEME_TEXTDOMAIN', get_theme_info('TextDomain'));
define(__NAMESPACE__ . '\THEME_DESCRIPTION', get_theme_info('Description'));
define(__NAMESPACE__ . '\THEME_AUTHOR', get_theme_info('Author'));
define(__NAMESPACE__ . '\THEME_AUTHOR_URI', get_theme_info('AuthorURI'));
define(__NAMESPACE__ . '\THEME_DOCUMENT_URI', get_file_data(get_theme_file_path('style.css'), ['Document URI'])[0]);
