<?php

declare( strict_types=1 );

namespace DevWael\WpSeoCrawler\FrontEnd\Routes;

use DevWael\WpSeoCrawler\FrontEnd\Routes\Templates\Template;
use DevWael\WpSeoCrawler\FrontEnd\Routes\Templates\SitemapTemplate;

/**
 * The class that responsible for loading all custom rewrite rules logic.
 *
 * @package DevWael\WpSeoCrawler\FrontEnd\Routes
 */
class RewriteRule implements Rule {
	/**
	 * SitemapTemplate class instance or any class implements Template.
	 *
	 * @var Template $template
	 */
	private $template;

	/**
	 * The query var name.
	 *
	 * @var string $query_var
	 */
	private $query_var = 'wpseoc_template';

	/**
	 * The query var value.
	 *
	 * @var string $query_var_val
	 */
	private $query_var_val = 'sitemap-html';

	/**
	 * RewriteRule constructor.
	 *
	 * @param Template|null $template Template interface.
	 */
	public function __construct( Template $template = null ) {
		$template_object = $template ?? new SitemapTemplate();
		/**
		 * Instance of SitemapTemplate class to load the template.
		 */
		$this->template = \apply_filters(
			'wpseoc_template_object',
			$template_object
		);
	}

	/**
	 * Register custom WordPress rewrite rule to catch the page URL
	 * and let the WP knows how to deal with it.
	 *
	 * @return void
	 */
	public function register(): void {
		/**
		 * String Template Regex
		 *
		 * @see /plugin/src/FrontEnd/Templates/SitemapTemplate.php
		 */
		$regex = \apply_filters(
			'wpseoc_template_regex',
			$this->template->template_regex()
		);

		/**
		 * String Template Query
		 *
		 * @see /plugin/src/FrontEnd/Templates/SitemapTemplate.php
		 */
		$query = \apply_filters(
			'wpseoc_template_query',
			$this->template->template_query()
		);

		/**
		 * Adds a rewrite rule to WordPress to transforms it to query vars
		 */
		\add_rewrite_rule( $regex, $query, 'top' );

		/**
		 * This action is being fired after wp init action and after registering
		 * the rewrite rule.
		 *
		 * @param string $regex the rewrite rule regex
		 * @param string $query the rewrite rule query
		 */
		\do_action(
			'wpseoc_rewrite_rule_added',
			$regex,
			$query,
			'top'
		);
	}

	/**
	 * Add table_template to WordPress main query.
	 *
	 * @param array $vars query vars.
	 *
	 * @return array $vars
	 */
	public function register_query_var( array $vars ): array {
		$vars[] = $this->query_var;

		/**
		 * Array $vars
		 * Add the ability to modify vars data.
		 */
		return \apply_filters( 'wpseoc_template_query_vars', $vars );
	}

	/**
	 * Provide template path for WordPress.
	 *
	 * @param string $template path.
	 *
	 * @return string path
	 */
	public function load_template( string $template ): string {
		if ( \get_query_var( $this->query_var ) === $this->query_var_val ) {
			$this->adjust_wp_query_param();

			/**
			 * String template path.
			 * Set the path of the template that will be loaded
			 * when the custom url is being visited.
			 * Can be overridden with it the active theme under the following directory:
			 * /wp-seo-crawler/sitemap-html.php
			 */
			return \apply_filters(
				'wpseoc_template_path',
				$this->template->template_path()
			);
		}

		return $template;
	}

	/**
	 * Set the rule page title.
	 *
	 * @param array $title_parts strings of the page title.
	 *
	 * @return array strings of the page title
	 */
	public function template_title( array $title_parts ): array {
		if ( \get_query_var( $this->query_var ) === $this->query_var_val ) {
			$title                = \esc_html__( 'Sitemap', 'wp-seo-crawler' );
			$title_parts['title'] = \apply_filters( 'wpseoc_template_tab_title', $title );
		}

		return $title_parts;
	}

	/**
	 * Set the is_home() to false on the sitemap page.
	 *
	 * @return void
	 */
	private function adjust_wp_query_param(): void {
		global $wp_query;
		$wp_query->is_home = false;
	}

	/**
	 * Attach the class functions to WordPress hooks
	 *
	 * @return void
	 */
	public function load_hooks(): void {
		\add_action( 'init', [ $this, 'register' ] );
		\add_filter( 'query_vars', [ $this, 'register_query_var' ] );
		\add_filter( 'template_include', [ $this, 'load_template' ] );
		\add_filter( 'document_title_parts', [ $this, 'template_title' ] );
	}
}
