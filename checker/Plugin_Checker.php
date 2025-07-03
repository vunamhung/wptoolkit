<?php

namespace vnh\checker;

/**
 * Abstract Plugin Checker class
 * 
 * Plugin-specific version compatibility checking
 * 
 * @package vnh\checker
 */
abstract class Plugin_Checker extends Checker {
	/**
	 * Plugin file path
	 * @var string
	 */
	public $file;

	/**
	 * Maybe deactivate plugin if not compatible
	 * 
	 * @return void
	 */
	public function maybe_deactivate_plugin() {
		if ($this->is_not_compatible() && is_plugin_active(plugin_basename($this->file))) {
			deactivate_plugins(plugin_basename($this->file));

			if (isset($_GET['activate'])) {
				unset($_GET['activate']);
			}
		}
	}

	/**
	 * Handle version too low scenario
	 * 
	 * @return void
	 */
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
