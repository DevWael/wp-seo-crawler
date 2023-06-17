<?php

namespace DevWael\WpSeoCrawler\BackgroundWorkers;

interface ProcessManager {
	/**
	 * Schedule the crawl task.
	 *
	 * @return void
	 */
	public function schedule(): int;

	/**
	 * Check if the task is scheduled.
	 *
	 * @return bool
	 */
	public function is_scheduled(): bool;

	/**
	 * Unschedule the crawl task.
	 *
	 * @return void
	 */
	public function unschedule(): void;

	/**
	 * Process the crawl task.
	 *
	 * @param string $url The url to crawl.
	 *
	 * @return void
	 */
	public function run_task( string $url ): void;

	/**
	 * Load the hooks.
	 *
	 * @return void
	 */
	public function load_hooks(): void;
}
