<?php

namespace vnh\checker;

abstract class Plugin_Checker extends Checker {
	public $file;

	public function maybe_deactivate_plugin() {
		if ($this->is_not_compatible() && is_plugin_active(plugin_basename($this->file))) {
			deactivate_plugins(plugin_basename($this->file));

			if (isset($_GET['activate'])) {
				unset($_GET['activate']);
			}
		}
	}

	public function version_too_low() {
		if ($this->is_not_compatible()) {
			register_activation_hook($this->file, function () {
				wp_die(
					sprintf(
						esc_html__(
							'%s requires %s version %s or higher and cannot be activated. You are currently running version %s.',
							'vnh_textdomain'
						),
						'vnh_name',
						esc_html($this->context),
						esc_html($this->require_version),
						$this->current_version
					)
				);
			});
		}
	}
}
