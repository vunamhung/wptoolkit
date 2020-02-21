<?php

namespace vnh;

use vnh\contracts\Bootable;

class Register_Widget_Areas implements Bootable {
	public $widget_areas;
	public $default_args;

	public function __construct($widget_areas = []) {
		$this->widget_areas = $widget_areas;
		$this->default_args = apply_filters('vnh/register/widget_areas/default_args', [
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		]);
	}

	public function boot() {
		add_action('widgets_init', [$this, 'register_widget_areas']);
	}

	public function register_widget_areas() {
		foreach ($this->widget_areas as $id => $args) {
			$args = wp_parse_args($args, $this->default_args);
			$args = wp_parse_args($args, ['id' => $id]);
			$args = apply_filters("vnh/widget/$id/args", $args);

			register_sidebar($args);
		}
	}
}
