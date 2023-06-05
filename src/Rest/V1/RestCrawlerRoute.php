<?php

namespace DevWael\WpSeoCrawler\Rest\V1;

use DevWael\WpSeoCrawler\Rest\RestApiRoute;
use WP_REST_Request;

class RestCrawlerRoute implements RestApiRoute {

	/**
	 * Load the hooks for the objects of the controller.
	 */
	public function load_hooks() {
		\add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		\register_rest_route(
			'wp-seo-crawler/v1',
			'/crawl',
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'initiate_request' ],
				'permission_callback' => [ $this, 'permission' ],
			]
		);
	}

	/**
	 * Check if the current user has permission to access the route.
	 *
	 * @return bool
	 */
	public function permission() {
		return \current_user_can( 'manage_options' );
	}

	/**
	 * Initiate the request.
	 *
	 * @param WP_REST_Request $request The request object.
	 */
	public function initiate_request( WP_REST_Request $request ) {
		// todo: initiate the request.
	}
}
