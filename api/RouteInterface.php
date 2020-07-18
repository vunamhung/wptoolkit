<?php
/**
 * Routeable Contract.
 */

namespace vnh\api;

interface RouteInterface {
	/**
	 * Get the namespace for this route.
	 *
	 * @return string
	 */
	public function get_namespace();

	/**
	 * Get the path of this REST route.
	 *
	 * @return string
	 */
	public function get_path();

	/**
	 * Get arguments for this REST route.
	 *
	 * @return array An array of endpoints.
	 */
	public function get_args();
}
