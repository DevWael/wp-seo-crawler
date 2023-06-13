<?php

namespace DevWael\WpSeoCrawler;

use DevWael\WpSeoCrawler\admin\Admin_Page;
use DevWael\WpSeoCrawler\Background_Workers\Hourly_Crawl;
use DevWael\WpSeoCrawler\Background_Workers\Immediate_Crawl;

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
	 * Immediate Crawl object.
	 *
	 * @var Immediate_Crawl $immediate_crawl instance of the immediate crawl.
	 */
	private $immediate_crawl;

	/**
	 * Hourly Crawl object.
	 *
	 * @var Hourly_Crawl $hourly_crawl instance of the hourly crawl.
	 */
	private $hourly_crawl;

	/**
	 * WpSeoCrawler constructor.
	 *
	 * @param Admin_Page      $admin_page      instance of the admin page.
	 * @param Immediate_Crawl $immediate_crawl instance of the immediate crawl.
	 * @param Hourly_Crawl    $hourly_crawl    instance of the hourly crawl.
	 */
	private function __construct(
		Admin_Page $admin_page,
		Immediate_Crawl $immediate_crawl,
		Hourly_Crawl $hourly_crawl
	) {
		$this->admin_page      = $admin_page;
		$this->immediate_crawl = $immediate_crawl;
		$this->hourly_crawl    = $hourly_crawl;
	}

	/**
	 * Load class singleton instance.
	 *
	 * @param Admin_Page|null      $admin_page      instance of the admin page.
	 * @param Immediate_Crawl|null $immediate_crawl instance of the immediate crawl.
	 * @param Hourly_Crawl|null    $hourly_crawl    instance of the hourly crawl.
	 *
	 * @return WpSeoCrawler singleton instance
	 */
	public static function instance(
		Admin_Page $admin_page = null,
		Immediate_Crawl $immediate_crawl = null,
		Hourly_Crawl $hourly_crawl = null
	): ?WpSeoCrawler {
		if ( null === self::$instance ) {
			$admin_page_object      = $admin_page ?? new Admin_Page(); // new instance of Admin_Page object.
			$immediate_crawl_object = $immediate_crawl ?? new Immediate_Crawl(); // new instance of Immediate_Crawl object.
			$hourly_crawl_object    = $hourly_crawl ?? new Hourly_Crawl(); // new instance of Hourly_Crawl object.
			self::$instance         = new self( $admin_page_object, $immediate_crawl_object, $hourly_crawl_object );
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

		$this->immediate_crawl->load_hooks(); // load all immediate crawl actions.
		$this->hourly_crawl->load_hooks(); // load all hourly crawl actions.
	}
}
