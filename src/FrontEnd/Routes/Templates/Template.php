<?php

namespace DevWael\WpSeoCrawler\FrontEnd\Routes\Templates;

/**
 * Interface Template
 *
 * @package DevWael\WpSeoCrawler\FrontEnd\Templates
 */
interface Template {
	/**
	 * This method should provide the path of the frontend template.
	 *
	 * @return string
	 */
	public function template_path(): string;

	/**
	 * This method should provide the regex that will be provided to
	 * the WP rewrite rule.
	 *
	 * @return string
	 */
	public function template_regex(): string;

	/**
	 * This method should provide the query that will make the WP understand
	 * what to do when the custom URL visited.
	 *
	 * @return string
	 */
	public function template_query(): string;
}
