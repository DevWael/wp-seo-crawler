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
	protected $action = 'wpseoc_crawler_immediate_crawl';

	/**
	 * Schedule the crawl task.
	 *
	 * @return void
	 * @throws \InvalidArgumentException If the url is not set.
	 */
	public function schedule(): void {
		if ( ! isset( $this->args['url'] ) ) {
			throw new \InvalidArgumentException( esc_html__( 'The url is required', 'wp-seo-crawler' ) );
		}
		\as_enqueue_async_action( $this->action, $this->args, self::GROUP );
	}

	/**
	 * Load the hooks.
	 *
	 * @return void
	 */
	public function load_hooks(): void {
		\add_action( $this->action, [ $this, 'run_task' ] );
	}
}
