<?php

namespace vnh\api;

use Exception;
use vnh\contracts\Bootable;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

abstract class Route implements RouteInterface, Bootable {
	public function boot() {
		add_action('rest_api_init', [$this, 'register_routes']);
	}

	public function register_routes() {
		foreach ($this->get_args() as $arg) {
			register_rest_route($this->get_namespace(), $this->get_path(), $arg);
		}
	}

	/**
	 * Get the route response based on the type of request.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_response($request) {
		$response = null;
		try {
			switch ($request->get_method()) {
				case 'POST':
					$response = $this->get_route_post_response($request);
					break;
				case 'PUT':
				case 'PATCH':
					$response = $this->get_route_update_response($request);
					break;
				case 'DELETE':
					$response = $this->get_route_delete_response($request);
					break;
				default:
					$response = $this->get_route_response($request);
					break;
			}
		} catch (RouteException $error) {
			$response = $this->get_route_error_response(
				$error->getErrorCode(),
				$error->getMessage(),
				$error->getCode(),
				$error->getAdditionalData()
			);
		} catch (Exception $error) {
			$response = $this->get_route_error_response('unknown_server_error', $error->getMessage(), 500);
		}
		return $response;
	}

	protected function get_route_response(WP_REST_Request $request) {
		throw new RouteException('vnh_rest_invalid_endpoint', __('Method not implemented', 'vnh_textdomain'), 404);
	}

	protected function get_route_post_response(WP_REST_Request $request) {
		throw new RouteException('vnh_rest_invalid_endpoint', __('Method not implemented', 'vnh_textdomain'), 404);
	}

	protected function get_route_update_response(WP_REST_Request $request) {
		throw new RouteException('vnh_rest_invalid_endpoint', __('Method not implemented', 'vnh_textdomain'), 404);
	}

	protected function get_route_delete_response(WP_REST_Request $request) {
		throw new RouteException('vnh_rest_invalid_endpoint', __('Method not implemented', 'vnh_textdomain'), 404);
	}

	/**
	 * Get route response when something went wrong.
	 *
	 * @param string $error_code String based error code.
	 * @param string $error_message User facing error message.
	 * @param int    $http_status_code HTTP status. Defaults to 500.
	 * @param array  $additional_data  Extra data (key value pairs) to expose in the error response.
	 * @return WP_Error WP Error object.
	 */
	protected function get_route_error_response($error_code, $error_message, $http_status_code = 500, $additional_data = []) {
		return new WP_Error($error_code, $error_message, array_merge($additional_data, ['status' => $http_status_code]));
	}

	/**
	 * For non-GET endpoints, require and validate a nonce to prevent CSRF attacks.
	 *
	 * Nonces will mismatch if the logged in session cookie is different! If using a client to test, set this cookie
	 * to match the logged in cookie in your browser.
	 *
	 * @throws RouteException On error.
	 *
	 * @param WP_REST_Request $request Request object.
	 */
	protected function check_nonce(WP_REST_Request $request) {
		$nonce = $request->get_header('X-VNH-API-Nonce');

		if (apply_filters('vnh/api_disable_nonce_check', false)) {
			return;
		}

		if ($nonce === null) {
			throw new RouteException(
				'vnh_rest_missing_nonce',
				__('Missing the X-VNH-API-Nonce header. This endpoint requires a valid nonce.', 'vnh_textdomain'),
				401
			);
		}

		$valid_nonce = wp_verify_nonce($nonce, 'wc_store_api');

		if (!$valid_nonce) {
			throw new RouteException('vnh_rest_invalid_nonce', __('X-VNH-API-Nonce is invalid.', 'vnh_textdomain'), 403);
		}
	}
}
