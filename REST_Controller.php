<?php

namespace vnh;

use vnh\contracts\Bootable;

abstract class REST_Controller implements Bootable {
	public $prefix;
	public $namespace;
	public $route;
	public $version = 'v1';

	public function __construct() {
		$this->namespace = sprintf('%s/%s', $this->prefix, $this->version);
	}

	public function boot() {
		add_action('rest_api_init', [$this, 'register_routes']);
	}

	abstract public function register_routes();

	abstract protected function permissions();
}
