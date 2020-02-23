<?php

namespace vnh;

use vnh\contracts\Bootable;

/**
 * Add Docs and FAQ url to row meta of plugin
 * @package vnh
 */
class Plugin_Row_Meta implements Bootable {
	public $plugin_base;
	public $doc_url;

	public function __construct($plugin_base, $doc_url) {
		$this->plugin_base = $plugin_base;
		$this->doc_url = $doc_url;
	}

	public function boot() {
		add_filter('plugin_row_meta', [$this, 'row_meta'], 10, 2);
	}

	public function row_meta($meta, $plugin_file) {
		if ($plugin_file === $this->plugin_base) {
			$meta['docs'] = sprintf('<a href="%s" target="_blank">%s</a>', esc_url($this->doc_url), esc_html__('Docs & FAQ', 'vnh_textdomain'));
		}

		return $meta;
	}
}
