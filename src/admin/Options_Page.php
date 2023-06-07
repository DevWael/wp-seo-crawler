<?php

namespace DevWael\WpSeoCrawler\admin;

interface Options_Page {

	/**
	 * Add options page to the admin menu.
	 */
	public function options_page(): void;

	/**
	 * Render the options page.
	 */
	public function render(): void;
}
