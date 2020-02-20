<?php
/**
 * Loadable Contract.
 *
 * Defines the contract that loadable classes should utilize. Loadable classes
 * should have a `load()` method with the singular purpose of "requiring" the
 * file. This keeps require calls out of the class constructor.
 */

namespace vnh\contracts;

interface Loadable {
	/**
	 * Require file.
	 *
	 * @return void
	 */
	public function load();
}
