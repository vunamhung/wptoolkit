<?php

namespace vnh\cache;

use vnh\contracts\Cacheable;

/**
 * Abstract Cache class
 * 
 * Base class for cache implementations
 * 
 * @package vnh\cache
 */
abstract class Cache implements Cacheable {
	/**
	 * Cache unique key
	 * @var string
	 */
	protected $key;

	/**
	 * Constructor
	 * 
	 * @param string $key Cache unique key
	 */
	public function __construct($key) {
		if (empty($key)) {
			trigger_error('Cache key cannot be empty');
		}

		$this->key = $key;
	}
}
