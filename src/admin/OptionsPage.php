<?php

namespace DevWael\WpSeoCrawler\admin;

interface OptionsPage {

	/**
	 * Add options page to the admin menu.
	 */
	public function admin_page(): void;

	/**
	 * Render the options page.
	 */
	public function render(): void;
}
