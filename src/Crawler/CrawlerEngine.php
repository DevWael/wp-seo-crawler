<?php

namespace DevWael\WpSeoCrawler\Crawler;

use Symfony\Component\DomCrawler\Crawler;

interface CrawlerEngine {
	/**
	 * Crawl the URL and get all links.
	 *
	 * @return array the extracted links.
	 * @throws \RuntimeException If the page is not loaded.
	 */
	public function crawl(): array;
}
