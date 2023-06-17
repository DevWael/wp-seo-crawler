<?php

namespace DevWael\WpSeoCrawler\BackgroundWorkers;

class HourlyCrawl extends CrawlTask {

	/**
	 * The task action.
	 *
	 * @var string
	 */
	protected $action = 'wpseoc_crawler_hourly_crawl';

	/**
	 * The task interval (Hour in seconds).
	 *
	 * @var int
	 */
	protected const INTERVAL = 3600;

	/**
	 * Schedule the crawl task in the next hour.
	 *
	 * @throws \InvalidArgumentException If the url is not set.
	 * @return int The task id.
	 */
	public function schedule(): int {
		if ( ! isset( $this->args['url'] ) ) {
			throw new \InvalidArgumentException( esc_html__( 'The url is required', 'wp-seo-crawler' ) );
		}
		return \as_schedule_recurring_action(
			time() + self::INTERVAL, // run the action after an hour from now.
			self::INTERVAL, // set the action to run every 1 hour.
			$this->action, // the action to run.
			$this->args, // the action arguments.
			self::GROUP // the action group.
		);
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
