<?php

namespace vnh;

/**
 * Abstract Singleton Class
 * 
 * Provides a base implementation of the Singleton design pattern
 * 
 * @package vnh
 */
abstract class Singleton {
	/**
	 * Singleton instance
	 * 
	 * @var static
	 */
	protected static $_instance;

	/**
	 * Get singleton instance
	 * 
	 * @return static The singleton instance
	 */
	final public static function instance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new static(...func_get_args());
		}

		return self::$_instance;
	}

	/**
	 * Prevent cloning of the singleton instance
	 *
	 * Disable class cloning and throw an error on object clone.
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 * 
	 * @return void
	 */
	private function __clone() {
		_doing_it_wrong(__FUNCTION__, esc_html__('Cheatin&#8217; huh?', 'vnh_textdomain'), null);
	}

	/**
	 * Prevent unserializing of the singleton instance
	 *
	 * Disable unserializing of the class to maintain singleton pattern.
	 * 
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong(__FUNCTION__, esc_html__('Cheatin&#8217; huh?', 'vnh_textdomain'), null);
	}
}
