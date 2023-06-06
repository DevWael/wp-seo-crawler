<?php

namespace DevWael\WpSeoCrawler\Crawler;

class LinksLoader implements Links {

	/**
	 * The links loader object.
	 *
	 * @var array $links The links array.
	 */
	private $links = [];

	/**
	 * Add a link to the links loader.
	 *
	 * @param string $link The link to add.
	 *
	 * @return void
	 */
	public function add_link( $link ): void {
		$this->links[] = $link;
	}

	/**
	 * Get the links from the links loader.
	 *
	 * @return array
	 */
	public function get_links(): array {
		return $this->links;
	}
}
