<?php

declare( strict_types=1 );

namespace DevWael\WpSeoCrawler\FrontEnd\Routes\Templates;

/**
 * This class provide template information like
 * 1. Template file path.
 * 2. Template regex.
 * 3. Template query.
 *
 * @package DevWael\WpSeoCrawler\FrontEnd\Routes\Templates
 */
class SitemapTemplate implements Template {
	/**
	 * Load template file path.
	 * Can be overridden with it the active theme under the following directory:
	 * /wp-seo-crawler/sitemap-html.php
	 *
	 * @return string template file path.
	 */
	public function template_path(): string {
		$template = \locate_template( 'wp-seo-crawler/sitemap-html.php' );

		/**
		 * Check if the file located in the active theme, then load it.
		 */
		if ( $template ) {
			return $template;
		}

		/**
		 * Load the plugin provided template file.
		 */
		return dirname( __FILE__, 5 ) . '/templates/sitemap-html.php';
	}

	/**
	 * Return string template regex to be used in add_rewrite_rule function.
	 */
	public function template_regex(): string {
		return '^sitemap.html$';
	}

	/**
	 * Return string template query to be used in add_rewrite_rule function
	 */
	public function template_query(): string {
		return 'index.php?wpseoc_template=sitemap-html';
	}
}
