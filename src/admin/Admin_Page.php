<?php

namespace DevWael\WpSeoCrawler\admin;

/**
 * Admin page
 */
class Admin_Page implements Options_Page {

	/**
	 * Request object.
	 *
	 * @var Request $request request object.
	 */
	private $request;

	/**
	 * Admin_Page constructor.
	 *
	 * @param Request|null $request request object.
	 */
	public function __construct( Request $request = null ) {
		$this->request = $request ?? new Request();
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
		include \plugin_dir_path( \dirname( __FILE__, 2 ) ) . 'admin-templates/crawl-report.php';
	}

	/**
	 * Render the options page.
	 */
	public function render(): void {
		$this->admin_notices();
		include \plugin_dir_path( \dirname( __FILE__, 2 ) ) . 'admin-templates/admin-form.php';
	}

	/**
	 * Verify the wp nonce.
	 */
	private function check_nonce(): void {
		$request_query = $this->request->post();
		if ( ! isset( $request_query['_wpnonce'] ) || ! \wp_verify_nonce( sanitize_text_field( wp_unslash( $request_query['_wpnonce'] ) ) )
		) {
			\wp_die( \esc_html__( 'Sorry, your nonce did not verify.', 'wp-seo-crawler' ) );
		}
	}

	/**
	 * Check if the current user has the required permissions.
	 */
	private function check_permissions(): void {
		if ( ! \current_user_can( 'manage_options' ) ) {
			\wp_die( \esc_html__( 'Sorry, you are not allowed to access this page.', 'wp-seo-crawler' ) );
		}
	}

	/**
	 * Process the options page form data
	 */
	public function process_save_options(): void {
		$this->check_nonce();
		$this->check_permissions();
		$result = $this->save_options();
		$this->safe_redirect( $result );
	}

	/**
	 * Save the options page form data
	 *
	 * @return string status of the operation.
	 */
	private function save_options(): string {
		$request_query = $this->request->post();
		if ( ! isset( $request_query['wpseoc_crawl_status'] ) ) {
			return 'error_1';
		}
		$status = \sanitize_text_field( \wp_unslash( $request_query['wpseoc_crawl_status'] ) );
		if ( ! in_array( $status, [ 'on', 'off' ], true ) ) {
			return 'error_2';
		}
		$settings = [
			'wpseoc_crawl_active' => $status,
		];
		\update_option( 'wpseoc_options', $settings );

		return 'success';
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
