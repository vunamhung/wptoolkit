<?php

namespace vnh;

function to_camel_case($string): string {
	$string = str_replace(['-', '_'], ' ', $string);
	$string = ucwords(strtolower($string));
	$string = str_replace(' ', '', $string);

	return $string;
}

function to_snake_case($string): string {
	$string = str_replace('-', '_', $string);

	return $string;
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

function the_svg_icon($args) {
	echo wp_kses(get_svg_icon($args), 'svg');
}

function get_svg_icon($args) {
	if (!is_array($args)) {
		$args = ['icon' => $args];
	}

	$args = wp_parse_args($args, [
		'icon' => 'email',
		'title' => '',
		'desc' => '',
		'aria_hidden' => true, // Hide from screen readers.
	]);

	$aria_hidden = $args['aria_hidden'] === true ? 'aria-hidden="true"' : '';
	$aria_labelledby = $args['title'] && $args['desc'] ? 'aria-labelledby="title desc"' : '';

	// Begin SVG markup.
	$svg = sprintf('<svg class="icon %1$s" %2$s %3$s role="img">', $args['icon'], $aria_hidden, $aria_labelledby);
	$svg .= $args['title'] ? sprintf('<title>%s</title>', $args['title']) : '';
	$svg .= $args['desc'] ? sprintf('<desc>%s</desc>', $args['desc']) : '';
	$svg .= sprintf('<use xlink:href="#%s"></use>', $args['icon']);
	$svg .= '</svg>';

	return wp_kses($svg, 'svg');
}

function get_google_fonts_url($font) {
	$font_families = [];

	$font_families[] = $font;

	$query_args = [
		'family' => rawurlencode(implode('|', $font_families)),
		'subset' => rawurlencode('latin,latin-ext'),
	];

	$fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');

	return esc_url_raw($fonts_url);
}

function get_svg_placeholder($width, $height) {
	return sprintf(
		'<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%2$d" viewBox="0 0 %1$d %2$d"><rect fill="#ddd" width="%1$d" height="%2$d"/> <text fill="rgba(0,0,0,0.5)" font-family="sans-serif" font-size="30" dy="10.5" font-weight="bold" x="50%%" y="50%%" text-anchor="middle">%1$d × %2$d</text></svg>',
		esc_html($width),
		esc_html($height)
	);
}

/*
 * TEMPLATES
 */
function include_template($file, &$params = null) {
	$VARS = &$params;
	include get_theme_file_path($file);
}

function require_template($file, &$params = null) {
	$VARS = &$params;
	require get_theme_file_path($file);
}

function get_template($file, &$params = null) {
	ob_start();

	$VARS = &$params;
	require get_theme_file_path($file);

	return ob_get_clean();
}
