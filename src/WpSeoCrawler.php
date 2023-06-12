<?php

namespace DevWael\WpSeoCrawler;

use DevWael\WpSeoCrawler\admin\Admin_Page;

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
	 * Admin page object.
	 *
	 * @var Admin_Page $admin_page instance of the admin page
	 */
	private $admin_page;

	/**
	 * WpSeoCrawler constructor.
	 *
	 * @param Admin_Page $admin_page instance of the admin page.
	 */
	private function __construct( Admin_Page $admin_page ) {
		$this->admin_page = $admin_page;
	}

	/**
	 * Load class singleton instance.
	 *
	 * @param Admin_Page|null $admin_page instance of the admin page.
	 *
	 * @return WpSeoCrawler singleton instance
	 */
	public static function instance( Admin_Page $admin_page = null ) {
		if ( null === self::$instance ) {
			$admin_page_object = $admin_page ?? new Admin_Page(); // new instance of Admin_Page object.
			self::$instance    = new self( $admin_page_object );
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initialize the logic
	 */
	public function init(): void {
		/**
		 * Load all admin side logic
		 */
		if ( \is_admin() ) {
			$this->admin_page->load_hooks(); // load all admin page actions.
		}
	}
}
