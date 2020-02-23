<?php

namespace vnh;

use vnh\contracts\Bootable;
use vnh\contracts\Initable;

class Purge_Expired_Transients implements Initable, Bootable {
	public $older_than;

	public function __construct($older_than = '1 day') {
		$this->older_than = $older_than;
	}

	public function init() {
		if (!wp_next_scheduled('purge_transients_cron')) {
			wp_schedule_event(time(), 'daily', 'purge_transients_cron');
		}
	}

	public function boot() {
		add_action('purge_transients_cron', [$this, 'purge_transients']);
	}

	public function purge_transients() {
		global $wpdb;

		$older_than_time = strtotime('-' . $this->older_than);

		/*
		 * Only check if the transients are older than the specified time
		 */
		if ($older_than_time > time() || $older_than_time < 1) {
			return false;
		}

		/*
		 * Get all the expired transients
		 */
		$transients = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT REPLACE(option_name, '_transient_timeout_', '') AS transient_name
				 FROM {$wpdb->options}
				 WHERE option_name LIKE '\_transient\_timeout\__%%'
				 AND option_value < %s",
				$older_than_time
			)
		);

		foreach ($transients as $transient) {
			delete_transient($transient);
		}

		return $transients;
	}
}
