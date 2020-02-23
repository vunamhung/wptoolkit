<?php

namespace vnh;

class Register_Style {
	public $handle;
	public $args;

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
