<?php

namespace DevWael\WpSeoCrawler\admin;

/**
 * Admin page
 */
class Admin_Page implements Options_Page {

	/**
	 * Add options page to the admin menu.
	 */
	public function options_page(): void {
		\add_options_page(
			\esc_html__( 'Seo Crawler', 'wp-seo-crawler' ),     // Page title.
			\esc_html__( 'Seo Crawler', 'wp-seo-crawler' ),     // Menu title.
			'manage_options',         // Capability required to access the page.
			'wp-seo-crawler-settings',     // Menu slug.
			[ $this, 'render' ] // Callback function to render the page.
		);
	}

	/**
	 * Render the options page.
	 */
	public function render(): void {
		include \plugin_dir_path( \dirname( __FILE__, 2 ) ) . 'admin-templates/admin-form.php';
	}

	/**
	 * Verify the wp nonce.
	 */
	private function check_nonce(): void {
		if ( ! isset( $_POST['_wpnonce'] ) || ! \wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) ) ) {
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
	}

	/**
	 * Display admin notices
	 */
	public function admin_notices(): void {
	}

	/**
	 * Load the hooks
	 */
	public function load_hooks(): void {
		\add_action( 'admin_menu', [ $this, 'options_page' ] );
		\add_action( 'admin_post_wpseoc_save_options', [ $this, 'process_save_options' ] );
		\add_action( 'admin_notices', [ $this, 'admin_notices' ] );
	}
}