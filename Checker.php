<?php

namespace vnh;

use vnh\contracts\Initable;

abstract class Checker implements Initable {
	public $compare_version;
	public $min_version;
	public $context;

	public function __construct($compare_version, $context) {
		$this->compare_version = $compare_version;
		$this->context = $context;
		$this->get_min_version();
	}

	public function get_min_version() {
		global $wp_version;

		if ($this->context === 'PHP') {
			$this->min_version = PHP_VERSION;
		} elseif ($this->context === 'WordPress') {
			$this->min_version = $wp_version;
		}
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
				esc_html($this->context),
				$this->min_version,
				$this->compare_version
			);
			$html .= '</p></div>';

			echo $html;
		});
	}
}
