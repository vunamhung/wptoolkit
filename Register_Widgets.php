<?php

namespace vnh;

use vnh\contracts\Bootable;

class Register_Widgets implements Bootable {
	public $widgets;

	public function __construct($widgets = []) {
		$this->widgets = $widgets;
	}

	public function boot() {
		add_action('widgets_init', [$this, 'register_widgets']);
	}

	public function register_widgets() {
		foreach ($this->widgets as $widget) {
			register_widget($widget);
		}
	}
}
