<?php

namespace DevWael\WpSeoCrawler\FrontEnd\Routes;

/**
 * Interface Rule
 *
 * @package DevWael\WpSeoCrawler\FrontEnd\Routes
 */
interface Rule {

	/**
	 * Register the custom rewrite rule to WordPress.
	 *
	 * @return void
	 */
	public function register(): void;

	/**
	 * Register query var to use it with the template.
	 *
	 * @param array $vars query vars.
	 * @return array
	 */
	public function register_query_var( array $vars): array;

	/**
	 * Provide the template file path for WordPress.
	 *
	 * @param string $template path.
	 * @return string path.
	 */
	public function load_template( string $template): string;
}
