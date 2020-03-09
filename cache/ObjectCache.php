<?php

namespace vnh\cache;

class ObjectCache extends Cache {
	protected $group;

	public function __construct($key, $group = '') {
		parent::__construct($key);

		$this->group = $group;
	}

	/**
	 * Sets cache value
	 *
	 * @param mixed $value value to store
	 * @return object $this
	 */
	public function set($value) {
		wp_cache_set($this->key, $value, $this->group);

		return $this;
	}

	/**
	 * Adds cache if it's not already set
	 *
	 * @param mixed $value value to store
	 * @return object $this
	 */
	public function add($value) {
		wp_cache_add($this->key, $value, $this->group);

		return $this;
	}

	/**
	 * Gets value from cache
	 *
	 * @param boolean $force not used, transients are always get from storage
	 * @return mixed          cached value
	 */
	public function get($force = false) {
		return wp_cache_get($this->key, $this->group, $force);
	}

	/**
	 * Deletes value from cache
	 *
	 * @return object $this
	 */
	public function delete() {
		wp_cache_delete($this->key, $this->group);

		return $this;
	}
}
