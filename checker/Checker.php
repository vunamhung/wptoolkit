<?php

namespace vnh\checker;

abstract class Checker {
	public $current_version;
	public $require_version;
	public $context;

	public function show_admin_notice() {
		if ($this->is_not_compatible()) {
			add_action('admin_notices', [$this, 'disabled_notice']);
		}
	}

	public function disabled_notice() {
		$html = '<div class="updated" style="border-left: 4px solid #ffba00;"><p>';
		$html .= sprintf(
			esc_html__(
				'%s requires %s version %s or higher to run and has been deactivated. You are currently running version %s.',
				'vnh_textdomain'
			),
			'vnh_name',
			esc_html($this->context),
			$this->require_version,
			$this->current_version
		);
		$html .= '</p></div>';

		echo $html;
	}

	public function is_not_compatible(): bool {
		if (version_compare($this->current_version, $this->require_version, '>=')) {
			return false;
		}

		return true;
	}
}
