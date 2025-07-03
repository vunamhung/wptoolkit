<?php

namespace vnh;

/**
 * Class Register_Style
 * 
 * Handles registration of CSS stylesheets in WordPress
 * 
 * @package vnh
 */
class Register_Style {
	/**
	 * The stylesheet handle/identifier
	 * 
	 * @var string
	 */
	public $handle;
	
	/**
	 * Arguments for stylesheet registration
	 * 
	 * @var array
	 */
	public $args;

	/**
	 * Constructor
	 * 
	 * @param string $handle The stylesheet handle/identifier
	 * @param array $args Arguments for stylesheet registration
	 */
	public function __construct($handle, $args) {
		$this->handle = $handle;
		$this->args = wp_parse_args($args, [
			'deps' => false,
			'version' => null,
			'media' => 'all',
			'has_rtl' => false,
		]);
		$this->args = apply_filters("vnh/register/style/$handle/args", $this->args);
	}

	/**
	 * Register the stylesheet with WordPress
	 * 
	 * Registers the stylesheet using wp_register_style() and adds RTL support if enabled
	 * 
	 * @return void
	 */
	public function register_style() {
		wp_register_style(
			$this->handle,
			esc_url($this->args['src']),
			$this->args['deps'],
			flatten_version($this->args['version']),
			$this->args['media']
		);

		if ($this->args['has_rtl']) {
			wp_style_add_data($this->handle, 'rtl', 'replace');
		}
	}
}
