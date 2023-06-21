<?php
/**
 * The template for displaying users table content within the custom route.
 *
 * This template can be overridden by copying it to yourtheme/wp-seo-crawler/sitemap-html.php.
 *
 * @package DevWael\WpSeoCrawler
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit; // stop loading if accessed directly.

use DevWael\WpSeoCrawler\FrontEnd\Routes\Templates\Components;
use DevWael\WpSeoCrawler\Storage\DataController;

$wpseoc_controller  = new DataController();
$wpseoc_crawl_data  = $wpseoc_controller->read();
$wpseoc_time_format = \get_option( 'date_format' ) . ' ' . \get_option( 'time_format' );
Components::header();
?>
	<h1>
		<?php
		\esc_html_e( 'Sitemap', 'wp-seo-crawler' );
		?>
	</h1>
	<?php do_action( 'wpseoc_before_sitemap_container' ); ?>
	<div class="container">
		<?php
		if ( ! empty( $wpseoc_crawl_data['links'] ) ) {
			?>
			<?php do_action( 'wpseoc_before_sitemap' ); ?>
			<ul>
				<?php
				foreach ( $wpseoc_crawl_data['links'] as $wpseoc_link ) {
					if ( ! empty( $wpseoc_link['href'] ) ) {
						if ( ! empty( $wpseoc_link['text'] ) ) {
							$wpseoc_link_text = $wpseoc_link['text'];
						} elseif ( ! empty( $wpseoc_link['title'] ) ) {
							$wpseoc_link_text = $wpseoc_link['title'];
						} else {
							$wpseoc_link_text = \esc_html__( 'Link without info', 'wp-seo-crawler' );
						}
						?>
						<li><a href="<?php echo \esc_url( $wpseoc_link['href'] ); ?>"><?php echo \esc_html( $wpseoc_link_text ); ?></a></li>
						<?php
					}
				}
				?>
			</ul>
			<?php do_action( 'wpseoc_before_sitemap_creation_date' ); ?>
			<div class="creation-date">
				<?php
				\esc_html_e( 'Created at: ', 'wp-seo-crawler' );
				echo \esc_html( \get_date_from_gmt( gmdate( 'Y-m-d H:i:s', $wpseoc_crawl_data['time'] ), $wpseoc_time_format ) );
				?>
			</div>
			<?php do_action( 'wpseoc_after_sitemap' ); ?>
			<?php
		} else {
			\esc_html_e( 'No links found, please try again later!', 'wp-seo-crawler' );
		}
		?>
	</div>
<?php do_action( 'wpseoc_after_sitemap_container' ); ?>
<?php
Components::footer();
