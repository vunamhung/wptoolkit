<?php

namespace vnh;

use vnh\contracts\Bootable;

/**
 * Abstract Register_Assets Class
 * 
 * Base class for registering scripts and styles in WordPress
 * 
 * @package vnh
 */
abstract class Register_Assets implements Bootable {
	/**
	 * Scripts to be registered
	 * 
	 * @var array
	 */
	public $scripts;
	
	/**
	 * Styles to be registered
	 * 
	 * @var array
	 */
	public $styles;

	/**
	 * Register scripts with WordPress
	 * 
	 * @return void
	 */
	public function register_scripts() {
		if (empty($this->scripts)) {
			return;
		}

		foreach ($this->scripts as $handle => $args) {
			$register = new Register_Script($handle, $args);
			$register->register_script();
		}
	}

	/**
	 * Register styles with WordPress
	 * 
	 * @return void
	 */
	public function register_styles() {
		if (empty($this->styles)) {
			return;
		}

		foreach ($this->styles as $handle => $args) {
			$register = new Register_Style($handle, $args);
			$register->register_style();
		}
	}
}
