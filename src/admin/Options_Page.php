<?php

namespace DevWael\WpSeoCrawler\admin;

interface Options_Page {

	/**
	 * Add options page to the admin menu.
	 */
	public function admin_page(): void;

	/**
	 * Render the options page.
	 */
	public function render(): void;
}
