<?php

namespace DevWael\WpSeoCrawler\Admin;

use DevWael\WpSeoCrawler\BackgroundWorkers\HourlyCrawl;
use DevWael\WpSeoCrawler\BackgroundWorkers\ImmediateCrawl;

/**
 * Admin page
 */
class AdminPage implements OptionsPage {

	/**
	 * Request object.
	 *
	 * @var Request $request request object.
	 */
	private $request;

	/**
	 * Immediate Crawl background task object.
	 *
	 * @var ImmediateCrawl
	 */
	private $immediate_crawl;

	/**
	 * Hourly Crawl background task object.
	 *
	 * @var HourlyCrawl
	 */
	private $hourly_crawl;

	/**
	 * Admin_Page constructor.
	 *
	 * @param Request|null        $request         request object.
	 * @param ImmediateCrawl|null $immediate_crawl Immediate Crawl background task object.
	 * @param HourlyCrawl|null    $hourly_crawl    Hourly Crawl background task object.
	 */
	public function __construct(
		Request $request = null,
		ImmediateCrawl $immediate_crawl = null,
		HourlyCrawl $hourly_crawl = null
	) {
		$this->request         = $request ?? new Request();
		$args                  = \apply_filters(
			'wpseoc_crawler_task_args',
			[
				'url' => \esc_url( \home_url() ),
			]
		);
		$this->immediate_crawl = $immediate_crawl ?? new ImmediateCrawl( $args );
		$this->hourly_crawl    = $hourly_crawl ?? new HourlyCrawl( $args );
	}

	/**
	 * Add options page to the admin menu.
	 */
	public function admin_page(): void {
		\add_menu_page(
			\esc_html__( 'Seo Crawler', 'wp-seo-crawler' ),     // Page title.
			\esc_html__( 'Seo Crawler', 'wp-seo-crawler' ),     // Menu title.
			'manage_options',         // Capability required to access the page.
			'wp-seo-crawler',     // Menu slug.
			[ $this, 'crawl_report' ], // Callback function to render the page.
			'dashicons-admin-links',
			40
		);

		\add_submenu_page(
			'wp-seo-crawler',     // Parent slug.
			\esc_html__( 'Settings', 'wp-seo-crawler' ),     // Page title.
			\esc_html__( 'Settings', 'wp-seo-crawler' ),     // Menu title.
			'manage_options',         // Capability required to access the page.
			'wp-seo-crawler-settings',     // Menu slug.
			[ $this, 'render' ] // Callback function to render the page.
		);
	}

	/**
	 * Render the crawl report page.
	 */
	public function crawl_report(): void {
		$file = \plugin_dir_path( \dirname( __FILE__, 2 ) ) . 'admin-templates/crawl-report.php';
		if ( file_exists( $file ) ) {
			include $file;
		}
	}

	/**
	 * Render the options page.
	 */
	public function render(): void {
		$this->admin_notices();
		$file = \plugin_dir_path( \dirname( __FILE__, 2 ) ) . 'admin-templates/admin-form.php';
		if ( file_exists( $file ) ) {
			include $file;
		}
	}

	/**
	 * Verify the wp nonce.
	 *
	 * @throws \RuntimeException If the nonce didn't verify.
	 */
	private function check_nonce(): void {
		$request_query = $this->request->post();
		if ( ! isset( $request_query['_wpnonce'] ) || ! \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $request_query['_wpnonce'] ) ) )
		) {
			throw new \RuntimeException( \esc_html__( 'Sorry, your nonce did not verify.', 'wp-seo-crawler' ) );
		}
	}

	/**
	 * Check if the current user has the required permissions.
	 *
	 * @throws \RuntimeException If the user doesn't have the permissions.
	 */
	private function check_permissions(): void {
		if ( ! \current_user_can( 'manage_options' ) ) {
			throw new \RuntimeException( \esc_html__( 'Sorry, you are not allowed to access this page.', 'wp-seo-crawler' ) );
		}
	}

	/**
	 * Process the options page form data
	 */
	public function process_save_options(): void {
		try {
			// Check the nonce.
			$this->check_nonce();

			// Check the user has administrator permissions.
			$this->check_permissions();

			// Save the options.
			$result = $this->save_options();

			// Start or cancel the background tasks.
			if ( 'success' === $result['status'] ) {
				if ( 'on' === $result['value'] ) {
					// Start the background tasks.
					$this->start_instant_crawl();
					$this->start_hourly_crawl();
				} else {
					// Cancel the background tasks.
					$this->cancel_instant_crawl();
					$this->cancel_hourly_crawl();
				}
			}

			// Redirect to the options page.
			$this->safe_redirect( $result['status'] );
		} catch ( \Throwable  $exception ) {
			$this->display_error_message( $exception->getMessage() );
		}
	}


	/**
	 * Display error message when error occurs.
	 *
	 * @param string $message message string to be displayed.
	 *
	 * @return void
	 */
	private function display_error_message( string $message ): void {
		\wp_die( \esc_html( $message ) );
	}

	/**
	 * Save the options page form data
	 *
	 * @return array status of the operation and the user selected value on success.
	 */
	private function save_options(): array {
		$request_query = $this->request->post();
		if ( ! isset( $request_query['wpseoc_crawl_status'] ) ) {
			return [ 'status' => 'error_1' ];
		}
		$status = \sanitize_text_field( \wp_unslash( $request_query['wpseoc_crawl_status'] ) );
		if ( ! in_array( $status, [ 'on', 'off' ], true ) ) {
			return [ 'status' => 'error_2' ];
		}
		$settings = [
			'wpseoc_crawl_active' => $status,
		];
		// Save the settings.
		\update_option( 'wpseoc_options', $settings );

		return [
			'status' => 'success',
			'value'  => $status,
		];
	}

	/**
	 * Start the immediate crawl background task.
	 *
	 * @return int The task ID.
	 */
	public function start_instant_crawl(): int {
		$task = $this->immediate_crawl;
		if ( false === $task->is_scheduled() ) {
			return $task->schedule();
		}

		return 0;
	}

	/**
	 * Cancel the immediate crawl background task.
	 *
	 * @return void
	 */
	public function cancel_instant_crawl(): void {
		$task = $this->immediate_crawl;
		if ( true === $task->is_scheduled() ) {
			$task->unschedule();
		}
	}

	/**
	 * Start the hourly crawl background task.
	 *
	 * @return int The task ID.
	 */
	public function start_hourly_crawl(): int {
		$task = $this->hourly_crawl;
		if ( false === $task->is_scheduled() ) {
			return $task->schedule();
		}

		return 0;
	}

	/**
	 * Cancel the hourly crawl background task.
	 *
	 * @return void
	 */
	public function cancel_hourly_crawl(): void {
		$task = $this->hourly_crawl;
		if ( true === $task->is_scheduled() ) {
			$task->unschedule();
		}
	}

	/**
	 * Safely redirect user to the options page after saving the form data
	 *
	 * @param string $status status of the operation.
	 *
	 * @return void
	 */
	private function safe_redirect( string $status = 'success' ): void {
		$url = \admin_url( 'admin.php?page=wp-seo-crawler-settings' );
		\wp_safe_redirect( \add_query_arg( [ 'status' => \sanitize_text_field( $status ) ], \esc_url( $url ) ) );
		exit;
	}

	/**
	 * Display admin notices
	 */
	public function admin_notices(): void {
		$request_query = $this->request->get();
		if ( isset( $request_query['page'], $request_query['status'] ) && 'wp-seo-crawler-settings' === $request_query['page'] ) {
			$status = \sanitize_text_field( \wp_unslash( $request_query['status'] ) );
			switch ( $status ) {
				case 'success':
					// todo: add a message to tell the user that the crawl has started and they should wait.
					\add_settings_error(
						'wpseoc_notice_messages',
						'wpseoc_message',
						\esc_html__( 'Settings Saved', 'wp-seo-crawler' ),
						'updated'
					);
					break;
				case 'error_1':
					\add_settings_error(
						'wpseoc_notice_messages',
						'wpseoc_message',
						\esc_html__( 'Crawl status not set!', 'wp-seo-crawler' ),
						'error'
					);
					break;
				case 'error_2':
					\add_settings_error(
						'wpseoc_notice_messages',
						'wpseoc_message',
						\esc_html__( 'Failed to validate crawl status', 'wp-seo-crawler' ),
						'error'
					);
					break;
			}
		}
	}

	/**
	 * Load the hooks
	 */
	public function load_hooks(): void {
		\add_action( 'admin_menu', [ $this, 'admin_page' ] );
		\add_action( 'admin_post_wpseoc_save_options', [ $this, 'process_save_options' ] );
	}
}
