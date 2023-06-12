<?php
/**
 * Admin form template.
 *
 * @package wp-seo-crawler
 */

defined( '\ABSPATH' ) || exit;

$wpseoc_settings = \get_option( 'wpseoc_options' );
if ( isset( $wpseoc_settings['wpseoc_crawl_active'] ) ) {
	$wpseoc_crawl_active = sanitize_text_field( $wpseoc_settings['wpseoc_crawl_active'] );
} else {
	$wpseoc_crawl_active = 'off';
}
?>
<div class="wrap">
	<h1>
		<?php
		echo esc_html( get_admin_page_title() );
		?>
	</h1>
	<?php
	settings_errors( 'wpseoc_notice_messages' );
	?>
	<?php
	// phpcs:disable
	?>
	<form method="post" action="<?php
	echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php
		// phpcs:enable
		?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<label for="wpseoc_crawl_type">
						<?php
						esc_html_e( 'Activate Crawler', 'wp-seo-crawler' );
						?>
					</label>
				</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<span>
								<?php
								esc_html_e( 'Activate Crawl', 'wp-seo-crawler' );
								?>
							</span>
						</legend>
						<label for="wpseoc_crawl_deactivate">
							<input type="radio" name="wpseoc_crawl_status" id="wpseoc_crawl_deactivate" value="off" 
							<?php
							checked( $wpseoc_crawl_active, 'off' )
							?>
							>
							<?php
							esc_html_e( 'No', 'wp-seo-crawler' );
							?>
						</label>
						<br>
						<label for="wpseoc_crawl_active">
							<input type="radio" name="wpseoc_crawl_status" id="wpseoc_crawl_active" value="on" 
							<?php
							checked( $wpseoc_crawl_active, 'on' )
							?>
							>
							<?php
							esc_html_e( 'Yes', 'wp-seo-crawler' );
							?>
						</label>
						<p class="description">
							<?php
							esc_html_e( 'Once the crawler is activated, it will crawl the website once every hour.', 'wp-seo-crawler' );
							?>
						</p>
					</fieldset>
				</td>
			</tr>
		</table>
		<input type="hidden" name="action" value="wpseoc_save_options">
		<?php
		// add nonce verification field.
		wp_nonce_field();

		// add submit button.
		submit_button();
		?>
	</form>
</div>
