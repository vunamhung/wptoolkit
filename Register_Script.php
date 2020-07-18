<?php

namespace vnh;

class Register_Script {
	public $handle;
	public $args;

	public function __construct($handle, $args) {
		$this->handle = $handle;
		$this->args = wp_parse_args($args, [
			'deps' => [],
			'version' => null,
			'in_footer' => true,
			'have_min' => false,
			'inline_script_position' => 'before',
		]);
		$this->args = apply_filters("vnh/register/script/$handle/args", $this->args);
	}

	public function register_script() {
		if ($this->args['have_min'] === true) {
			$src = preg_replace('/\.js$/', '.min.js', $this->args['src']);
		} else {
			$src = $this->args['src'];
		}

		wp_register_script($this->handle, esc_url($src), $this->args['deps'], flatten_version($this->args['version']), $this->args['in_footer']);

		if (!empty($this->args['inline_script'])) {
			wp_add_inline_script($this->handle, $this->args['inline_script'], $this->args['inline_script_position']);
		}

		if (!empty($this->args['localize_script'])) {
			foreach ($this->args['localize_script'] as $object_name => $data) {
				wp_localize_script($this->handle, $object_name, $data);
			}
		}
	}
}
