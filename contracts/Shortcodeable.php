<?php
/**
 * Shortcodeable Contract.
 *
 * Defines the contract that shortcode classes should utilize. Shortcode classes
 * should have a `add_shortcode()` and a `callback()`.
 */

namespace vnh\contracts;

interface Shortcodeable {
	public function add_shortcode();
	public function callback($atts);
}
