<?php

namespace DevWael\WpSeoCrawler;

use DevWael\WpSeoCrawler\admin\Admin_Page;
use DevWael\WpSeoCrawler\Background_Workers\HourlyCrawl;
use DevWael\WpSeoCrawler\Background_Workers\ImmediateCrawl;

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
	 * @var ImmediateCrawl $immediate_crawl instance of the immediate crawl.
	 */
	private $immediate_crawl;

	/**
	 * Hourly Crawl object.
	 *
	 * @var HourlyCrawl $hourly_crawl instance of the hourly crawl.
	 */
	private $hourly_crawl;

	/**
	 * WpSeoCrawler constructor.
	 *
	 * @param Admin_Page     $admin_page      instance of the admin page.
	 * @param ImmediateCrawl $immediate_crawl instance of the immediate crawl.
	 * @param HourlyCrawl    $hourly_crawl    instance of the hourly crawl.
	 */
	private function __construct(
		Admin_Page $admin_page,
		ImmediateCrawl $immediate_crawl,
		HourlyCrawl $hourly_crawl
	) {
		$this->admin_page      = $admin_page;
		$this->immediate_crawl = $immediate_crawl;
		$this->hourly_crawl    = $hourly_crawl;
	}

	/**
	 * Load class singleton instance.
	 *
	 * @param Admin_Page|null     $admin_page      instance of the admin page.
	 * @param ImmediateCrawl|null $immediate_crawl instance of the immediate crawl.
	 * @param HourlyCrawl|null    $hourly_crawl    instance of the hourly crawl.
	 *
	 * @return WpSeoCrawler singleton instance
	 */
	public static function instance(
		Admin_Page $admin_page = null,
		ImmediateCrawl $immediate_crawl = null,
		HourlyCrawl $hourly_crawl = null
	): ?WpSeoCrawler {
		if ( null === self::$instance ) {
			$admin_page_object      = $admin_page ?? new Admin_Page(); // new instance of Admin_Page object.
			$immediate_crawl_object = $immediate_crawl ?? new ImmediateCrawl(); // new instance of ImmediateCrawl object.
			$hourly_crawl_object    = $hourly_crawl ?? new HourlyCrawl(); // new instance of Hourly_Crawl object.
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
