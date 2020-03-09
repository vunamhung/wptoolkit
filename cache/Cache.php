<?php

namespace vnh\cache;

use vnh\contracts\Cacheable;

abstract class Cache implements Cacheable {
	/**
	 * Cache unique key
	 * @var string
	 */
	protected $key;

	public function __construct($key) {
		if (empty($key)) {
			trigger_error('Cache key cannot be empty');
		}

		$this->key = $key;
	}
}
