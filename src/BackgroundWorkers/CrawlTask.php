<?php

namespace DevWael\WpSeoCrawler\BackgroundWorkers;

use DevWael\WpSeoCrawler\Crawler\WebCrawler;
use DevWael\WpSeoCrawler\Storage\DataController;

abstract class CrawlTask implements ProcessManager {
	/**
	 * Object of the crawler.
	 *
	 * @var WebCrawler
	 */
	protected $crawler;

	/**
	 * The task arguments.
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * The task group.
	 *
	 * @var string
	 */
	protected const GROUP = 'wpseoc_crawler';

	/**
	 * The task action.
	 *
	 * @var string
	 */
	protected $action;

	/**
	 * Object of the data controller.
	 *
	 * @var DataController
	 */
	private $data_controller;

	/**
	 * Crawl Task constructor.
	 *
	 * @param array               $args            The task arguments.
	 * @param WebCrawler|null     $crawler         The Symfony DomCrawler object.
	 * @param DataController|null $data_controller The data controller object.
	 */
	public function __construct( array $args = [], WebCrawler $crawler = null, DataController $data_controller = null ) {
		$this->crawler         = $crawler ?? new WebCrawler();
		$this->data_controller = $data_controller ?? new DataController();
		$this->args            = $args;
	}

	/**
	 * Schedule the crawl task in the next hour.
	 *
	 * @return void
	 */
	abstract public function schedule(): void;

	/**
	 * Check if the task is scheduled.
	 *
	 * @return bool
	 */
	public function is_scheduled(): bool {
		return \as_has_scheduled_action( $this->action, $this->args, self::GROUP );
	}

	/**
	 * Unschedule the crawl task.
	 *
	 * @return void
	 */
	public function unschedule(): void {
		\as_unschedule_all_actions( $this->action, $this->args, self::GROUP );
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
		$this->data_controller->update_url( $url );
		$this->data_controller->delete();
		$this->data_controller->save( $crawl_data );
	}

	/**
	 * Load the hooks.
	 *
	 * @return void
	 */
	abstract public function load_hooks(): void;
}
