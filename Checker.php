<?php

namespace vnh;

class Checker {
	public $compare_version;
	public $min_version;
	public $name;
	public $plugin_base;

	public function __construct($compare_version, $min_version, $name, $plugin_base) {
		$this->min_version = $min_version;
		$this->compare_version = $compare_version;
		$this->name = $name;
		$this->plugin_base = $plugin_base;
	}

	public function maybe_deactivate_plugin() {
		if ($this->is_not_compatible() && is_plugin_active($this->plugin_base)) {
			deactivate_plugins($this->plugin_base);

			if (isset($_GET['activate'])) {
				unset($_GET['activate']);
			}
		}
	}

	public function version_too_low($file) {
		register_activation_hook($file, function () {
			wp_die(
				sprintf(
					esc_html__(
						'%s requires %s version %s or higher and cannot be activated. You are currently running version %s.',
						'vnh_textdomain'
					),
					'vnh_name',
					esc_html($this->name),
					esc_html($this->min_version),
					$this->compare_version
				)
			);
		});
	}

	public function is_not_compatible() {
		if (version_compare($this->compare_version, $this->min_version, '>=')) {
			return false;
		}

		return true;
	}

	public function disabled_notice() {
		add_action('admin_notices', function () {
			$html = '<div class="updated" style="border-left: 4px solid #ffba00;"><p>';
			$html .= sprintf(
				esc_html__(
					'%s requires %s version %s or higher to run and has been deactivated. You are currently running version %s.',
					'vnh_textdomain'
				),
				'vnh_name',
				esc_html($this->name),
				$this->min_version,
				$this->compare_version
			);
			$html .= '</p></div>';

			echo $html;
		});
	}
}
