<?php

namespace DevWael\WpSeoCrawler\Crawler;

class WebCrawler implements CrawlerEngine {
	/**
	 * The links loader object.
	 *
	 * @var Links|LinksLoader
	 */
	private $links_loader;

	/**
	 * LinksCrawler constructor.
	 *
	 * @param Links|null $links_loader The links loader object.
	 */
	public function __construct( Links $links_loader ) {
		$this->links_loader = $links_loader;
	}
}
