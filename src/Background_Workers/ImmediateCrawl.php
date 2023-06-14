<?php

namespace DevWael\WpSeoCrawler\Background_Workers;

class ImmediateCrawl extends CrawlTask {
	/**
	 * The task arguments.
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * The task action.
	 *
	 * @var string
	 */
	public const ACTION = 'wpseoc_crawler_immediate_crawl';

	/**
	 * Schedule the crawl task.
	 *
	 * @return void
	 */
	public function schedule(): void {
		if ( ! isset( $this->args['url'] ) ) {
			throw new \InvalidArgumentException( esc_html__( 'The url is required', 'wp-seo-crawler' ) );
		}
		\as_enqueue_async_action( self::ACTION, $this->args, self::GROUP );
	}

	/**
	 * Load the hooks.
	 *
	 * @return void
	 */
	public function load_hooks(): void {
		\add_action( self::ACTION, [ $this, 'run_task' ] );
	}
}
