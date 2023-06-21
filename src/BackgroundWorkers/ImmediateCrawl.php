<?php

namespace DevWael\WpSeoCrawler\BackgroundWorkers;

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
	 * @return int The task id.
	 * @throws \InvalidArgumentException If the url is not set.
	 */
	public function schedule(): int {
		if ( ! isset( $this->args['url'] ) ) {
			throw new \InvalidArgumentException( esc_html__( 'The url is required', 'wp-seo-crawler' ) );
		}

		/**
		 * Filter the task arguments.
		 *
		 * @param array  $args   The task arguments.
		 * @param string $action The task action.
		 */
		$args = \apply_filters( 'wpseoc_crawler_task_args', $this->args, $this->action );

		return \as_enqueue_async_action( $this->action, $args, self::GROUP );
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
