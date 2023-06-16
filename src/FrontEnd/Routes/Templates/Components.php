<?php

declare(strict_types=1);

namespace DevWael\WpSeoCrawler\FrontEnd\Routes\Templates;

/**
 * This class is responsible for loading the header and footer components
 * for the front-end template.
 *
 * @package DevWael\WpSeoCrawler
 */
class Components {
	/**
	 * The header template.
	 */
	public static function header(): void {
		?><!DOCTYPE html>
		<html <?php \language_attributes(); ?>>
			<head>
				<meta charset="<?php \bloginfo( 'charset' ); ?>">
				<?php \wp_head(); ?>
				<style>
					body {
						font-family: Arial, sans-serif;
						margin: 0;
						padding: 20px;
						background-color: #f5f5f5;
					}

					.container{
						max-width: 1200px;
						margin: 0 auto;
					}

					h1 {
						color: #333;
						font-size: 32px;
						margin-bottom: 30px;
						text-align: center;
					}

					ul {
						list-style: none;
						padding: 0;
					}

					li {
						margin-bottom: 15px;
					}

					a {
						color: #337ab7;
						text-decoration: none;
						transition: color 0.3s ease;
					}

					a:hover {
						color: #23527c;
					}

					/* Additional Styling */
					ul {

					}

					li::before {
						content: "»";
						display: inline-block;
						margin-right: 8px;
						color: #999;
						font-weight: bold;
					}

					li:last-child {
						margin-bottom: 0;
					}

					@media (max-width: 600px) {
						h1 {
							font-size: 28px;
						}
					}

					body {
						background-color: #ffffff;
					}

					body h1 {
						color: #333333;
					}

					body ul {
						border: 1px solid #ccc;
						background-color: #f1f1f1;
						padding: 20px;
						border-radius: 5px;
						box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
						margin: 0 0 30px;
					}

					body li::before {
						color: #999999;
					}

					body a {
						color: #337ab7;
					}

					@media (prefers-color-scheme: dark) {
						body {
							background-color: #222222;
						}

						body h1 {
							color: #ffffff;
						}

						body ul {
							border: 1px solid #555555;
							background-color: #333333;
						}

						body li::before {
							color: #999999;
						}

						body a {
							color: #66b3ff;
						}
					}

					.creation-date{
						text-align: center;
						color: #bbbbbb;
						font-size: 12px;
					}
				</style>
			</head>
		<body <?php \body_class(); ?>>
		<?php
		\wp_body_open();
	}

	/**
	 * The footer template.
	 */
	public static function footer(): void {
		?>
				</div>
				<?php \wp_footer(); ?>
			</body>
		</html>
		<?php
	}
}
