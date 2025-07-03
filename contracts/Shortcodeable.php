<?php
/**
 * Shortcodeable Contract.
 *
 * Defines the contract that shortcode classes should utilize. Shortcode classes
 * should have a `add_shortcode()` and a `callback()`.
 */

namespace vnh\contracts;

interface Shortcodeable {
	/**
	 * Add shortcode
	 * 
	 * @return void
	 */
	public function add_shortcode();
	
	/**
	 * Shortcode callback
	 * 
	 * @param array $atts Shortcode attributes
	 * @return string
	 */
	public function callback($atts);
}
