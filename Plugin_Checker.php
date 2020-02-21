<?php

namespace vnh;

class Plugin_Checker extends Checker {
	public $file;
	public $plugin_base;

	public function __construct($compare_version, $context, $file) {
		parent::__construct($compare_version, $context);
		$this->file = $file;
		$this->plugin_base = plugin_basename($file);
	}

	public function init() {
		if ($this->is_not_compatible()) {
			$this->disabled_notice();
			$this->maybe_deactivate_plugin();
			$this->version_too_low();
		}
	}

	public function maybe_deactivate_plugin() {
		if ($this->is_not_compatible() && is_plugin_active($this->plugin_base)) {
			deactivate_plugins($this->plugin_base);

			if (isset($_GET['activate'])) {
				unset($_GET['activate']);
			}
		}
	}

	public function version_too_low() {
		register_activation_hook($this->file, function () {
			wp_die(
				sprintf(
					esc_html__(
						'%s requires %s version %s or higher and cannot be activated. You are currently running version %s.',
						'vnh_textdomain'
					),
					'vnh_name',
					esc_html($this->context),
					esc_html($this->min_version),
					$this->compare_version
				)
			);
		});
	}
}
