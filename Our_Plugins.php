<?php

namespace vnh;

class Our_Plugins {
	public $plugin_list_file_url;
	public $transient_name;

	public function __construct($plugin_list_file_url) {
		$this->plugin_list_file_url = $plugin_list_file_url;
		$this->transient_name = sprintf('%s_plugins_list', to_snake_case(THEME_SLUG));
	}

	public function __toString() {
		return $this->render();
	}

	public function render() {
		$html = '<div class="plugins-list info-tab-content">';
		foreach ($this->get_plugins_list() as $plugin) {
			$html .= sprintf(
				'<div class="card">
                  <div class="card-image">%s</div>
                  <div class="card-header">
                    <a href="%s" target="_blank"><button class="btn btn-primary float-right">%s</button></a>
                    <div class="card-title h5">%s</div>
                    <div class="card-subtitle text-gray">%s</div>
                  </div>
                  <div class="card-body">%s</div>
                </div>',
				!empty($plugin['img']) ? $plugin['img'] : get_svg_placeholder(392, 200),
				$plugin['link'],
				__('More info', 'vnh_textdomain'),
				$plugin['name'],
				__('by ', 'vnh_textdomain') . $plugin['author'],
				$plugin['description']
			);
		}
		$html .= '</div>';

		return $html;
	}

	protected function get_plugins_list() {
		$cached_plugins_list = get_transient($this->transient_name);

		if (!empty($cached_plugins_list)) {
			return $cached_plugins_list;
		}

		$response = '';
		if (!is_wp_error($response)) {
			$response = request($this->plugin_list_file_url);
		}

		set_transient($this->transient_name, $response, DAY_IN_SECONDS * 7);

		return $response;
	}
}
