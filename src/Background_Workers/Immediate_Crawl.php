<?php

namespace DevWael\WpSeoCrawler\Background_Workers;

use DevWael\WpSeoCrawler\Crawler\WebCrawler;

class Immediate_Crawl implements Process_Manager {
	/**
	 * Object of the crawler.
	 *
	 * @var WebCrawler
	 */
	private $crawler;

	/**
	 * The task arguments.
	 *
	 * @var array
	 */
	private $args;

	/**
	 * The task group.
	 *
	 * @var string
	 */
	public const GROUP = 'wpseoc_crawler';

	/**
	 * The task action.
	 *
	 * @var string
	 */
	public const ACTION = 'wpseoc_crawler_immediate_crawl';

	/**
	 * LinksCrawler constructor.
	 *
	 * @param WebCrawler|null $crawler The Symfony DomCrawler object.
	 * @param array           $args    The task arguments.
	 *
	 * @throws \InvalidArgumentException If the url is not set.
	 */
	public function __construct( WebCrawler $crawler = null, array $args = [] ) {
		if ( ! isset( $args['url'] ) ) {
			throw new \InvalidArgumentException( esc_html__( 'The url is required', 'wp-seo-crawler' ) );
		}
		$this->crawler = $crawler ?? new WebCrawler();
		$this->args    = $args;
	}

	/**
	 * Schedule the crawl task.
	 *
	 * @return void
	 */
	public function schedule_task(): void {
		\as_enqueue_async_action( self::ACTION, $this->args, self::GROUP );
	}

	/**
	 * Check if the task is scheduled.
	 *
	 * @return bool
	 */
	public function is_scheduled(): bool {
		return \as_has_scheduled_action( self::ACTION, $this->args, self::GROUP );
	}

	/**
	 * Process the crawl task.
	 *
	 * @param string $url The url to crawl.
	 *
	 * @return void
	 */
	public function run_task( string $url ): void {
		$this->crawler->set_url( $url );
		$crawl_data = $this->crawler->crawl();
		// todo: handle the data and add storage.
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
