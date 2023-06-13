<?php

namespace DevWael\WpSeoCrawler\Background_Workers;

class HourlyCrawl extends CrawlTask {

	/**
	 * The task action.
	 *
	 * @var string
	 */
	public const ACTION = 'wpseoc_crawler_hourly_crawl';

	/**
	 * The task interval (Hour in seconds).
	 *
	 * @var int
	 */
	public const INTERVAL = 3600;

	/**
	 * Schedule the crawl task in the next hour.
	 *
	 * @return void
	 */
	public function schedule(): void {
		if ( ! isset( $this->args['url'] ) ) {
			throw new \InvalidArgumentException( esc_html__( 'The url is required', 'wp-seo-crawler' ) );
		}
		\as_schedule_recurring_action(
			time() + self::INTERVAL, // run the action after an hour from now.
			self::INTERVAL, // set the action to run every 1 hour.
			self::ACTION, // the action to run.
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
		\add_action( self::ACTION, [ $this, 'run_task' ] );
	}
}
