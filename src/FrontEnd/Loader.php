<?php

declare( strict_types=1 );

namespace DevWael\WpSeoCrawler\FrontEnd;

use DevWael\WpSeoCrawler\FrontEnd\Routes\RewriteRule;
use DevWael\WpSeoCrawler\FrontEnd\Routes\Rule;

/**
 * The class that responsible for loading all frontend logic.
 *
 * @package DevWael\WpSeoCrawler\FrontEnd
 */
class Loader {
	/**
	 * Rewrite rules class instance.
	 *
	 * @var Rule $rewrite_rule
	 */
	private $rewrite_rule;

	/**
	 * Loader constructor.
	 *
	 * @param Rule|null $rewrite_rule Rule interface compatible class.
	 */
	public function __construct(
		Rule $rewrite_rule = null
	) {
		/**
		 * Instance of RewriteRule class to load the assets
		 */
		$this->rewrite_rule = $rewrite_rule ?? new RewriteRule();
	}

	/**
	 * Attach the class functions to WordPress hooks
	 *
	 * @return void
	 */
	public function load_hooks(): void {
		$this->rewrite_rule->load_hooks();
		\do_action( 'wpseoc_plugin_frontend_loaded' );
	}
}
