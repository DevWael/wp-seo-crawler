<?php

namespace DevWael\WpSeoCrawler\admin;

/**
 * This class provides the get request data.
 *
 * @package DevWael\WpSeoCrawler\admin
 */
class Request {
	/**
	 * Get $_GET request parameters.
	 *
	 * @return array $_GET request parameters
	 */
	public function get(): array {
		// phpcs:disable
		return $_GET;
		// phpcs:enable
	}

	/**
	 * Get $_POST request parameters.
	 *
	 * @return array $_POST request parameters
	 */
	public function post(): array {
		// phpcs:disable
		return $_POST;
		// phpcs:enable
	}
}
