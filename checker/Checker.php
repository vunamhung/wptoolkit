<?php

namespace vnh\checker;

/**
 * Abstract Checker class
 * 
 * Base class for version compatibility checking
 * 
 * @package vnh\checker
 */
abstract class Checker {
	/**
	 * Current version
	 * @var string
	 */
	public $current_version;
	
	/**
	 * Required version
	 * @var string
	 */
	public $require_version;
	
	/**
	 * Context (e.g., PHP, WordPress)
	 * @var string
	 */
	public $context;

	/**
	 * Show admin notice if not compatible
	 * 
	 * @return void
	 */
	public function show_admin_notice() {
		if ($this->is_not_compatible()) {
			add_action('admin_notices', [$this, 'disabled_notice']);
		}
	}

	/**
	 * Display disabled notice
	 * 
	 * @return void
	 */
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

	/**
	 * Check if current version is not compatible
	 * 
	 * @return bool True if not compatible, false otherwise
	 */
	public function is_not_compatible(): bool {
		if (version_compare($this->current_version, $this->require_version, '>=')) {
			return false;
		}

		return true;
	}
}
