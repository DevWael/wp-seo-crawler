<?php
/**
 * WP SEO Crawler
 *
 * @package           DevWael\WpSeoCrawler
 * @author            Ahmad Wael
 * @copyright         2023 Ahmad Wael
 * @license           GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: WP SEO Crawler
 * Plugin URI: https://github.com/DevWael/wp-seo-crawler
 * Description: WordPress plugin that crawl the website and generate a report for SEO issues and HTML sitemap.
 * Version: 1.0.0
 * Author: Ahmad Wael
 * Author URI: https://www.bbioon.com
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-seo-crawler
 * Domain Path: /languages
 */

namespace DevWael\WpSeoCrawler;

/**
 * Check if loaded inside a WordPress environment.
 */
defined( '\ABSPATH' ) || exit;

/**
 * Load composer packages
 */
$wpseoc_auto_load = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
if ( ! class_exists( WpSeoCrawler::class ) && is_readable( $wpseoc_auto_load ) ) {
	// check if the Main plugin class is loaded.
	require_once $wpseoc_auto_load;
}

/**
 * Create instance from the main plugin class
 */
class_exists( WpSeoCrawler::class ) && WpSeoCrawler::instance();
