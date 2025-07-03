<?php

namespace vnh;

/**
 * Class Ajax
 * 
 * Provides utility methods for handling AJAX requests in WordPress
 * 
 * @package vnh
 */
class Ajax {
	/**
	 * Verifies nonce string
	 *
	 * @param string|null $action Action name, as defined while creating nonce hash, required
	 * @param string $nonce $_REQUEST array key where to search for nonce, default 'nonce'
	 * @return void Dies when nonce is wrong
	 */
	public function verify_nonce($action = null, $nonce = 'nonce') {
		if ($action === null) {
			trigger_error('Action cannot be empty');
		}

		if (check_ajax_referer($action, $nonce, false) === false) {
			$this->error(['wrong_nonce']);
		}
	}

	/**
	 * Prints success response for JavaScript
	 *
	 * @param mixed $data Data to send in the success response
	 * @return void
	 */
	public function success($data) {
		wp_send_json_success($data);
	}

	/**
	 * Prints error response for JavaScript
	 *
	 * @param mixed $data Data to send in the error response
	 * @return void
	 */
	public function error($data) {
		wp_send_json_error($data);
	}

	/**
	 * Responds to JavaScript with success or error message
	 *
	 * @param mixed $success Success data, if empty nothing will be passed
	 * @param array $errors Error data, if not empty an error will be returned
	 * @return void
	 */
	public function response($success = null, $errors = []) {
		if (!empty($errors)) {
			$this->error($errors);
		}

		$this->success($success);
	}
}
