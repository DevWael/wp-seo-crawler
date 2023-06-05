<?php

namespace DevWael\WpSeoCrawler\Rest;

interface RestApiRoute {

	/**
	 * Load the hooks for the objects of the controller.
	 */
	public function load_hooks();

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes();

	/**
	 * Check if the current user has permission to access the route.
	 *
	 * @return bool
	 */
	public function permission();
}
