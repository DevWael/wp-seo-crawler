<?php

namespace DevWael\WpSeoCrawler\Admin;

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
