<?php

namespace vnh;

use vnh\contracts\Bootable;

abstract class Register_Assets implements Bootable {
	public $scripts;
	public $styles;

	public function register_scripts() {
		if (empty($this->scripts)) {
			return;
		}

		foreach ($this->scripts as $handle => $args) {
			$register = new Register_Script($handle, $args);
			$register->register_script();
		}
	}

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
