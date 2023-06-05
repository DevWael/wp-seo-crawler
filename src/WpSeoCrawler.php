<?php

namespace DevWael\WpSeoCrawler;

/**
 * The plugin main class that responsible for loading all plugin logic.
 *
 * @package DevWael\WpSeoCrawler
 */
final class WpSeoCrawler {

	/**
	 * Unique instance of the WpSeoCrawler class.
	 *
	 * @var WpSeoCrawler|null
	 */
	private static $instance = null;

	/**
	 * WpSeoCrawler constructor.
	 */
	private function __construct() {
	}

	/**
	 * Load class singleton instance.
	 *
	 * @return WpSeoCrawler singleton instance
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initialize the logic
	 */
	public function init() {

	}
}
