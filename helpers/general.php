<?php

namespace vnh;

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
