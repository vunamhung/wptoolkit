<?php

namespace vnh;

use vnh\contracts\Bootable;

/**
 * Add setting page link and go to pro url
 * @package vnh
 */
class Plugin_Action_Links implements Bootable {
	public $plugin_base;
	public $settings_page_slug;
	public $premium_url;
	public $premium_plugin_base;

	public function __construct($plugin_base, $settings_page_slug, $premium_url = false, $premium_plugin_base = false) {
		$this->plugin_base = $plugin_base;
		$this->settings_page_slug = $settings_page_slug;
		$this->premium_url = $premium_url;
		$this->premium_plugin_base = $premium_plugin_base;
	}

	public function boot() {
		add_action('plugin_action_links_' . $this->plugin_base, [$this, 'action_links']);
	}

	public function action_links($links) {
		$deactivate_link = isset($links['deactivate']) ? $links['deactivate'] : '';

		unset($links['deactivate']);

		$links['settings'] = sprintf(
			'<a href="%s">%s</a>',
			add_query_arg(['page' => $this->settings_page_slug], admin_url('admin.php')),
			esc_html__('Settings', 'vnh_textdomain')
		);

		if (!empty($deactivate_link)) {
			$links['deactivate'] = $deactivate_link;
		}

		if (!empty($this->premium_plugin_base) && !empty($this->premium_url) && !is_plugin_active($this->premium_plugin_base)) {
			$links['pro'] = sprintf(
				'<a href="%s" target="_blank" style="color: #349e34;"><b>%s</b></a>',
				esc_url($this->premium_url),
				__('Go Pro', 'vnh_textdomain')
			);
		}

		return $links;
	}
}
