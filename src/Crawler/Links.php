<?php

namespace DevWael\WpSeoCrawler\Crawler;

interface Links {

	/**
	 * Add a link to the links loader.
	 *
	 * @param string $link The link to add.
	 *
	 * @return void
	 */
	public function add_link( $link ): void;

	/**
	 * Get the links from the links loader.
	 *
	 * @return array
	 */
	public function get_links(): array;
}
