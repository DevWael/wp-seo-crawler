<?php

use DevWael\WpSeoCrawler\admin\ReportView;

$wpseoc_data = new ReportView();
$wpseoc_data->prepare_items();
$wpseoc_latest_update = $wpseoc_data->get_latest_update();
?>
<div class="wrap">
	<h1>
		<?php
		echo esc_html( get_admin_page_title() );
		?>
	</h1>
	<?php
	settings_errors( 'wpseoc_notice_messages' );
	if ( $wpseoc_latest_update ) {
		?>
		<strong>
			<?php
			esc_html_e( 'Crawl Report Date: ', 'wp-seo-crawler' );
			echo esc_html( $wpseoc_data->get_latest_update() );
			?>
		</strong>
		<?php
	}
	$wpseoc_data->display();
	?>

</div>
