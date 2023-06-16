<?php

namespace DevWael\WpSeoCrawler;

use DevWael\WpSeoCrawler\Admin\AdminPage;
use DevWael\WpSeoCrawler\BackgroundWorkers\HourlyCrawl;
use DevWael\WpSeoCrawler\BackgroundWorkers\ImmediateCrawl;
use DevWael\WpSeoCrawler\FrontEnd\Loader;

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
	 * @var AdminPage $admin_page instance of the admin page
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
	 * Frontend Loader object.
	 *
	 * @var Loader
	 */
	private $loader;

	/**
	 * WpSeoCrawler constructor.
	 *
	 * @param AdminPage      $admin_page      instance of the admin page.
	 * @param ImmediateCrawl $immediate_crawl instance of the immediate crawl.
	 * @param HourlyCrawl    $hourly_crawl    instance of the hourly crawl.
	 * @param Loader         $loader          instance of the frontend loader.
	 */
	private function __construct(
		AdminPage $admin_page,
		ImmediateCrawl $immediate_crawl,
		HourlyCrawl $hourly_crawl,
		Loader $loader
	) {
		$this->admin_page      = $admin_page;
		$this->immediate_crawl = $immediate_crawl;
		$this->hourly_crawl    = $hourly_crawl;
		$this->loader          = $loader;
	}

	/**
	 * Load class singleton instance.
	 *
	 * @param AdminPage|null      $admin_page      instance of the admin page.
	 * @param ImmediateCrawl|null $immediate_crawl instance of the immediate crawl.
	 * @param HourlyCrawl|null    $hourly_crawl    instance of the hourly crawl.
	 * @param Loader|null         $loader          instance of the frontend loader.
	 *
	 * @return WpSeoCrawler singleton instance
	 */
	public static function instance(
		AdminPage $admin_page = null,
		ImmediateCrawl $immediate_crawl = null,
		HourlyCrawl $hourly_crawl = null,
		Loader $loader = null
	): ?WpSeoCrawler {
		if ( null === self::$instance ) {
			$admin_page_object      = $admin_page ?? new AdminPage(); // new instance of Admin_Page object.
			$immediate_crawl_object = $immediate_crawl ?? new ImmediateCrawl(); // new instance of ImmediateCrawl object.
			$hourly_crawl_object    = $hourly_crawl ?? new HourlyCrawl(); // new instance of Hourly_Crawl object.
			$loader_object          = $loader ?? new Loader(); // new instance of Loader object.
			self::$instance         = new self( $admin_page_object, $immediate_crawl_object, $hourly_crawl_object, $loader_object );
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
		$this->loader->load_hooks(); // load all frontend actions.
	}
}
