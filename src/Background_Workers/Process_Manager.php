<?php

namespace DevWael\WpSeoCrawler\Background_Workers;

interface Process_Manager {

	/**
	 * Schedule the crawl task.
	 *
	 * @return void
	 */
	public function schedule_task(): void;

	/**
	 * Check if the task is scheduled.
	 *
	 * @return bool
	 */
	public function is_scheduled(): bool;

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
